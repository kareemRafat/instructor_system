<?php

session_start();

require_once __DIR__ . "/../Database/connect.php";
require_once __DIR__ . '/../functions/Auth/auth_helper.php';

if (!isset($_SESSION['user_id'])) {
    // Check for remember-me token if session is not set

    if (!validateRememberMeToken($pdo)) {
        header("Location: login.php");
        exit();
    }

    header("Location: login.php");
    exit();
}

// check access for pages in the website
function checkAccess($role)
{
    $currentPage = basename($_SERVER['PHP_SELF']);

    $accessRules = [
        'admin' => ['*'], // Admin can access all pages
        'cs' => ['lectures.php','groups.php'],
        'cs-admin' => ['customer-service.php','lectures.php','groups.php'],
        'instructor' => ['index.php'], // Instructor can access index.php
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
