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
        $stmt = $pdo->prepare("
        SELECT DISTINCT instructors.id, instructors.username
        FROM instructors
        JOIN branch_instructor ON instructors.id = branch_instructor.instructor_id
        WHERE branch_instructor.branch_id = :branch_id
            AND instructors.is_active = 1
            AND instructors.role IN ('instructor', 'admin')");
        $stmt->bindParam(':branch_id', $_GET['branch_id'], PDO::PARAM_INT);
    } else {
        $stmt = $pdo->prepare("
        SELECT id, username 
        FROM instructors 
        WHERE is_active = 1 
            AND role IN ('instructor', 'admin')");
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
