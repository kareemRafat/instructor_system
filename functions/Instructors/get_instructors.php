<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}


try {
    // Query to fetch all instructors
    if (isset($_GET['branch_id'])) {
        $stmt = $pdo->prepare("SELECT username , id  FROM instructors WHERE branch_id = :branch");
        $stmt->bindParam(':branch', $_GET['branch_id'], PDO::PARAM_INT);
    } else {
        $stmt = $pdo->prepare("SELECT username , id  FROM instructors");
    }
    $stmt->execute();
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $instructors]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
