<?php
session_start();
require_once "../../Database/connect.php";

try {    // Validate input
    
    if (!checkErrors($_POST , $pdo)) {
        header("Location: ../../instructors.php");
        return ;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $branch = trim($_POST['branch']);

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new instructor
    $query = "INSERT INTO instructors (username, password, is_active , role , branch_id) VALUES (:username, :password, 1 , 'instructor' , :branch )";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':username' => $username,
        ':password' => $hashedPassword,
        ':branch' => $branch
    ]);

    header('Location: ../../instructors.php');
    exit;
} catch (Exception $e) {
    $_SESSION['errors'] = $e->getMessage();
    header('Location: ../../instructors.php');
    exit;
}



/** check errors */
function checkErrors($formData , $pdo) {

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

    if (!empty($errors)){
        $_SESSION['errors'] = $errors;
        return false ;
    }

    return true ;
}
