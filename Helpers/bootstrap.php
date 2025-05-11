<?php 

session_start();

require_once "Database/connect.php";
require_once 'functions/Auth/auth_helper.php';

// fetch user informatio
$user_id = $_SESSION['user_id'];
$query = "SELECT username , role FROM instructors WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

define('ROLE', $result['role']);
define('USERNAME', $result['username']);
