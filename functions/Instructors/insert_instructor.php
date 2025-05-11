<?php
session_start();
require_once "../../Database/connect.php";

try {    // Validate input
    if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['branch'])) {
        throw new Exception('Username, password and branch are required');
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $branch = trim($_POST['branch']);

    if (empty($branch)) {
        throw new Exception('Please select a branch');
    }

    // Validate username length
    if (strlen($username) < 3) {
        throw new Exception('Username must be at least 3 characters long');
    }

    // Check if username already exists
    $query = "SELECT id FROM instructors WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Username already exists');
    }

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

    $_SESSION['success'] = 'Instructor added successfully';
    header('Location: ../../instructors.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../../instructors.php');
    exit;
}
