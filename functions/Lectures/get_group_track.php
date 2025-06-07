<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

require_once '../../Database/connect.php';

try {
    $stmt = $pdo->prepare("SELECT track_id FROM lectures WHERE group_id = :group AND instructor_id = :instructor ORDER BY date DESC LIMIT 1");
    $stmt->bindParam(':group', $_GET['group_id'], PDO::PARAM_INT);
    $stmt->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $groupTrack = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $groupTrack, 'empty' => !$groupTrack]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
