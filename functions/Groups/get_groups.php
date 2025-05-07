<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}


try {
    // Query to fetch all groups
    $stmt = $pdo->prepare("SELECT * FROM groups WHERE instructor_id = :instructor AND is_active = 1");
    $stmt->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $groups]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>