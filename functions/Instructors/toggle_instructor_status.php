<?php
header('Content-Type: application/json');
require_once "../../Database/connect.php";

try {
    if (!isset($_POST['instructor_id'])) {
        throw new Exception('Instructor ID is required');
    }

    $instructorId = $_POST['instructor_id'];

    // First get the current status
    $query = "SELECT is_active FROM instructors WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $instructorId]);
    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instructor) {
        throw new Exception('Instructor not found');
    }

    // Toggle the status
    $newStatus = $instructor['is_active'] ? 0 : 1;

    $query = "UPDATE instructors SET is_active = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'status' => $newStatus,
        'id' => $instructorId
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Instructor status updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
