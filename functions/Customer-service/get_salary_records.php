<?php

session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // Decode the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    $agentId = $input['id'] ?? null;
    $month = $input['month'] ?? null;
    $year = $input['year'] ?? null;

    if (!$agentId || !$month || !$year) {
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }

    $stmt = $pdo->prepare("
                SELECT
                    i.username AS cs_name,
                    i.role,
                    sr.instructor_id,
                    FLOOR(sr.basic_salary) AS basic_salary,
                    sr.overtime_days,
                    sr.day_value,
                    FLOOR(sr.target) AS target,
                    FLOOR(sr.bonuses) AS bonuses,
                    FLOOR(sr.advances) AS advances,
                    sr.absent_days,
                    sr.deduction_days,
                    sr.total,
                    sr.created_at
                FROM instructors i
                JOIN salary_records sr ON i.id = sr.instructor_id
                WHERE i.id = :id AND MONTH(sr.created_at) = :month AND YEAR(sr.created_at) = :year
                LIMIT 1
            ");

    $stmt->execute([
        ':id' => $agentId,
        ':month' => (int)$month,
        ':year' => (int)$year
    ]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data ?: []);
}
