<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php");
        return;
    }

    try {

        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $branch = trim($_POST['branch']);
        $role = $_POST['role'] ?? 'cs';

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $pdo->beginTransaction();
        
        // Insert new instructor
        $query = "INSERT INTO instructors (username, password, is_active , role) VALUES (:username, :password, 1 , :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':role' => $role,
        ]);

        // Get the new instructor ID
        $instructorId = $pdo->lastInsertId();

        // Insert into pivot table
        $pivotQuery = "INSERT INTO branch_instructor (instructor_id, branch_id) VALUES (:instructor_id, :branch_id)";
        $pivotStmt = $pdo->prepare($pivotQuery);

        $pivotStmt->execute([
            ':instructor_id' => $instructorId,
            ':branch_id' => $branch
        ]);

        // Commit transaction if both queries succeeded
        $pdo->commit();

        $_SESSION['success'] = "Customer Service Agent Added Successfully";
        header('Location: ../../customer-service.php');
    } catch (Exception $e) {
        // Roll back transaction if any error occurs
        $pdo->rollBack();

        echo $e->getMessage();
        $_SESSION['errors'] = $e->getMessage();
        header('Location: ../../customer-service.php');
    }
}


/** check errors */
function checkErrors($formData, $pdo): bool
{

    $errors = [];

    // Validate username length
    if (strlen($formData['username']) < 3) {
        $errors['username'] = "Username must be at least 3 characters long";
    }

    if (isAgentNameDuplicated($formData['username'], $pdo)) {
        $errors['username'] = "Customer Service Agent name alreay Exists";
    }

    if (empty($formData['username'])) {
        $errors['username'] = "username is required.";
    }

    if (empty($formData['password'])) {
        $errors['password'] = "Password is required.";
    }

    if (empty($formData['branch'])) {
        $errors['branch'] = "Branch is required.";
    }

    if ($_SESSION['role'] === 'admin') {
        if (empty($formData['role'])) {
            $errors['role'] = "Role is required.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['old'] = $_POST;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}


/** check Agent name duplicated */
function isAgentNameDuplicated($name, $pdo): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `instructors` WHERE username = :username");
    $stmt->bindParam(':username', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}
