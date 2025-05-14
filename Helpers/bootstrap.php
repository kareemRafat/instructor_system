<?php

session_start();

require_once __DIR__ . "/../Database/connect.php";
require_once __DIR__ . '/../functions/Auth/auth_helper.php';

// check access for pages in the website
function checkAccess($role)
{
    $currentPage = basename($_SERVER['PHP_SELF']);

    $accessRules = [
        'admin' => ['*'], // Admin can access all pages
        'cs' => ['lectures.php', 'customer-service.php'], // CS can access lectures.php only
        'instructor' => ['lectures.php', 'index.php'], // Instructor can access lectures.php and index.php
    ];

    if (isset($accessRules[$role])) {
        if (in_array('*', $accessRules[$role]) || in_array($currentPage, $accessRules[$role])) {
            return true;
        }
    }

    header("location: ../login.php");
    return false;
}

// fetch user information
$user_id = $_SESSION['user_id'];
$query = "SELECT username , role FROM instructors WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

define('ROLE', $result['role']);
define('USERNAME', $result['username']);
