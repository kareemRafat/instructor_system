<?php

session_start();
require_once "../../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get parameters from fetch request (POST or GET)
    $agent_id = $_POST['agent_id'] ?? 0;
    $createdAt = $_POST['created_at']  ?? 0;


    if (!$agent_id || !$createdAt) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }

    // Expecting format: 7-2025 or 07-2025
    [$month, $year] = explode('-', $createdAt);

    // Normalize month (e.g., 7 → 07)
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    $start_date = "$year-$month-01";
    $end_date = date('Y-m-d', strtotime("$start_date +1 month"));

    // Fetch advances
    $stmt = $pdo->prepare("
                SELECT id, agent_id, amount, reason, created_at, advances_created_at
                FROM salary_advances
                WHERE agent_id = :agent_id AND created_at >= :startDate AND created_at < :endDate
                ORDER BY advances_created_at DESC
            ");
    $stmt->execute([
        ':agent_id' => $agent_id,
        ':startDate' => $start_date,
        ':endDate' => $end_date
    ]);

    $advances = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success' , 'advances' => $advances]);
}
