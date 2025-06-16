<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['id'] ?? '';

    $stmt = $pdo->prepare("UPDATE `groups` SET is_active = 0 WHERE id = :group_id");
    $stmt->execute([
        ':group_id' => $group_id
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Training Group Finished']);
}
