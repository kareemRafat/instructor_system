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
}

// check access for pages in the website
function checkAccess($role)
{
    $currentPage = basename($_SERVER['PHP_SELF']);

    $accessRules = [
        'admin' => ['*'], // Admin can access all pages,
        'owner' => ['*'],
        'cs' => ['lectures.php', 'groups.php', 'tables.php'],
        'cs-admin' => ['customer-service.php', 'lectures.php', 'groups.php', 'tables.php'],
        'instructor' => ['index.php', 'instructor-groups.php'], // Instructor can access index.php
    ];

    if (isset($accessRules[$role])) {
        if (in_array('*', $accessRules[$role]) || in_array($currentPage, $accessRules[$role])) {
            return true;
        } else {
            header("location: ../" . $accessRules[$role][0]);
            return false;
        }
    }
}

// fetch user information
$user_id = $_SESSION['user_id'];


$query = "
    SELECT i.username, i.email, i.role, b.id AS branch_id, b.name AS branch_name
    FROM instructors i
    JOIN branch_instructor bi ON i.id = bi.instructor_id
    JOIN branches b ON b.id = bi.branch_id
    WHERE i.id = :id
";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $_SESSION['user_id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userInfo = [
    'username' => $results[0]['username'],
    'role' => $results[0]['role']
];

$branches = array_map(function ($row) {
    return [
        'id' => $row['branch_id'],
        'name' => $row['branch_name'],
    ];
}, $results);

// Save user role and branches in session
$_SESSION['role'] = $userInfo['role'];
$_SESSION['branches'] = $branches;

// Constants
define('ROLE', $userInfo['role']);
define('USERNAME', $userInfo['username']);
define('BRANCH', $branches[0]['id'] ?? null); // First branch, optional

