<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

$is_admin = in_array($_SESSION['role'], ['admin', 'cs-admin', 'owner']);

try {
    // Query to fetch all branches
    $stmt = $pdo->prepare(fetchBranchesOnRoles($is_admin));
    $stmt->execute(executeParams($is_admin));

    $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $branches]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

/** fetch Branches based on ROLES */
function fetchBranchesOnRoles($is_admin) {
    if ($is_admin) {
        return "SELECT id, name FROM branches ORDER BY name ASC";
    } else {
        return  "SELECT id, name FROM branches WHERE id = :branch ORDER BY name ASC";
    }

}

/** execute params based on ROLES */
function executeParams($is_admin) {
    if ($is_admin) {
        return [];
    } else {
        return [
            ':branch' => $_SESSION['branch']
        ];
    }
}