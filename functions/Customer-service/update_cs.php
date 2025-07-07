<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php?action=edit&id=".$_POST['id']);
        return;
    }

    try {

        $id = $_POST['id'] ?? null;
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $branch = trim($_POST['branch']);
        $role = $_POST['role'] ?? 'cs';

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $pdo->beginTransaction();

        //  password logic
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE  instructors SET password = :password WHERE id = $id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':password' => $hashedPassword,
            ]);
        }

        // update customer service
        $query = "UPDATE instructors SET username = :username , role = :role , email = :email WHERE id = $id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':role' => $role,
            ':email' => $email
        ]);

        // update branch
        $queryBranch = "UPDATE branch_instructor SET branch_id = :branch WHERE instructor_id = $id";
        $stmtBranch = $pdo->prepare($queryBranch);
        $stmtBranch->execute([
            ':branch' => $branch
        ]);

        // Commit transaction if all queries succeeded
        $pdo->commit();

        $_SESSION['success'] = "Customer Service Agent updated Successfully";
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

    if (isAgentNameDuplicated($formData['username'], $formData['id'], $pdo)) {
        $errors['username'] = "Customer Service Agent name alreay Exists";
    }

    if (empty($formData['username'])) {
        $errors['username'] = "username is required.";
    }

    if (empty($formData['email'])) {
        $errors['email'] = "Email is required.";
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
function isAgentNameDuplicated($name, $instructorId, $pdo): bool
{
    // Check if username already exists
    $query = "SELECT id FROM instructors WHERE username = :username AND id != :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':username' => $name,
        ':id' => $instructorId
    ]);
    return $stmt->rowCount() > 0;
}
