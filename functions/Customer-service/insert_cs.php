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

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new instructor
        $query = "INSERT INTO instructors (username, password, is_active , role , branch_id) VALUES (:username, :password, 1 , 'cs' , :branch)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':branch' => $branch
        ]);

        $_SESSION['success'] = "customer service agent added successfully";
        header('Location: ../../customer-service.php');
    } catch (Exception $e) {
        echo $e->getMessage();
        $_SESSION['errors'] = $e->getMessage();
        header('Location: ../../customer-service.php');
    }
}


/** check errors */
function checkErrors($formData, $pdo):bool
{

    $errors = [];

    // Validate username length
    if (strlen($formData['username']) < 3) {
        $errors['username'] = "Username must be at least 3 characters long";
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

    // Check if username already exists
    $query = "SELECT id FROM instructors WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $formData['username']]);

    if ($stmt->rowCount() > 0) {
        $errors['username'] = 'Username already exists';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}
