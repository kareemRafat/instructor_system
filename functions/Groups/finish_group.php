<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? null;
    $finist_date = $_POST['finist_date'] ?? null;

    if ($group_id) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Update group to set is_active to 0 and set finish_date
            $stmt = $pdo->prepare("UPDATE `groups` SET is_active = 0 , finish_date = '$finist_date' WHERE id = :group_id");
            $stmt->bindParam(':group_id', $group_id);
            $stmt->execute();

            // delete from lectures when the group is finished
            $stmtDel = $pdo->prepare("DELETE FROM lectures WHERE group_id = :group_id");
            $stmtDel->bindParam(':group_id', $group_id);
            $stmtDel->execute();

            // Commit transaction if both queries succeeded
            $pdo->commit();

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Group finished successfully']);
        } catch (PDOException $e) {
            // Roll back transaction if any error occurs
            $pdo->rollBack();

            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Group ID is required']);
    }
}
