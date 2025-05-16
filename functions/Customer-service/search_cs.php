<?php
session_start();
header('Content-Type: application/json');
require_once "../../Database/connect.php";

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $query = "SELECT 
        instructors.id,
        instructors.username,
        instructors.is_active,
        instructors.role AS instructor_role,
        branches.name as branch_name
    FROM instructors 
    LEFT JOIN branches ON instructors.branch_id = branches.id
    WHERE instructors.username LIKE :search 
    AND role IN ('cs' ,'cs-admin')
    ORDER BY instructors.is_active DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'logged_instructor_role' => $_SESSION['role'] ,// the logged in user ROLE
        'data' => $result
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error'
    ]);
}
