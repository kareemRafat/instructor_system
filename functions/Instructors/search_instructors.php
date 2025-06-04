<?php
header('Content-Type: application/json');
require_once "../../Database/connect.php";

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $query = "
        SELECT 
            instructors.id,
            instructors.username,
            instructors.is_active,
            GROUP_CONCAT(branches.name SEPARATOR ', ') AS branch_names
        FROM instructors
        LEFT JOIN branch_instructor ON instructors.id = branch_instructor.instructor_id
        LEFT JOIN branches ON branches.id = branch_instructor.branch_id
        WHERE instructors.username LIKE :search
        AND role IN ('admin' ,'instructor')
        GROUP BY instructors.id, instructors.username, instructors.is_active
        ORDER BY instructors.is_active DESC
    ";

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
