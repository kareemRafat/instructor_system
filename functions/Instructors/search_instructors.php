<?php
header('Content-Type: application/json');
require_once "../../Database/connect.php";

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $query = "SELECT 
        instructors.id,
        instructors.username,
        instructors.is_active,
        branches.name as branch_name
    FROM instructors 
    LEFT JOIN branches ON instructors.branch_id = branches.id
    WHERE instructors.username LIKE :search
    ORDER BY instructors.is_active DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $result
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error'
    ]);
}
