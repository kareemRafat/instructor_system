<?php
header('Content-Type: application/json');
require_once "../../Database/connect.php";

try {
    if (!isset($_POST['cs_id'])) {
        throw new Exception('Instructor ID is required');
    }

    
    $csId = $_POST['cs_id'];
    
    // First get the current status
    $query = "SELECT is_active FROM instructors WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $csId]);
    $customerService = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if (!$customerService) {
        throw new Exception('Customer Service not found');
    }

    // Toggle the status
    $newStatus = $customerService['is_active'] ? 0 : 1;

    $query = "UPDATE instructors SET is_active = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'status' => $newStatus,
        'id' => $csId
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'customer service agent status updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
