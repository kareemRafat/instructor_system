<?php 

session_start();


if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

// empty database
require_once '../../Database/connect.php';
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE instructor_id = :user_id");
$stmt->execute(['user_id' => $userId]);

// Expire the cookie
setcookie('remember_token', '', time() - 3600, '/');

session_unset();

session_destroy();

header("Location: ../../login.php");