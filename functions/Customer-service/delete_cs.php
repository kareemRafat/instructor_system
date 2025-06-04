<?php
require_once '../../Database/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cs_id = $_POST['cs_id'] ?? null;

    if (!$cs_id) {
        echo json_encode(['status' => 'error', 'message' => 'CS ID is required']);
        exit;
    }

    try {

        $pdo->beginTransaction();

        // delete from pivot first
        // First, delete all pivot references to the instructor
        $queryDelPivot = "DELETE FROM branch_instructor WHERE instructor_id = ?";
        $DelPivotstmt = $pdo->prepare($queryDelPivot);
        $DelPivotstmt->execute([$cs_id]);

        // then delete from instructor
        $query = "DELETE FROM instructors WHERE id = ? AND role IN ('cs', 'cs-admin')";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$cs_id]);

        // Commit transaction if both queries succeeded
        $pdo->commit();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'CS Agent Deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'CS Agent not found or could not be deleted']);
        }
    } catch (PDOException $e) {
        // Roll back transaction if any error occurs
        $pdo->rollBack();

        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
