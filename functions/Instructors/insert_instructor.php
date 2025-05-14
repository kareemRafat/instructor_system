<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../instructors.php");
        return;
    }

    try {

        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $branch = trim($_POST['branch']);

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new instructor
        $query = "INSERT INTO instructors (username, password, is_active , role , branch_id ) VALUES (:username, :password, 1 , 'instructor' , :branch)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':branch' => $branch
        ]);

        $_SESSION['success'] = "Instructor added successfully";
        header('Location: ../../instructors.php');
    } catch (Exception $e) {
        echo $e->getMessage();
        $_SESSION['errors'] = $e->getMessage();
        header('Location: ../../instructors.php');
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

    
    if (isInstructorNameDuplicated($formData['username'] , $pdo)) {
        $errors['username'] = 'Instructor name already Exists';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}

function isInstructorNameDuplicated($name , $pdo):bool 
{
    // Check if username already exists
    $query = "SELECT id FROM instructors WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $name]);
    return $stmt->rowCount() > 0 ;
}
