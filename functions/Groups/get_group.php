<?php

session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {
    $query = "SELECT 
                `groups`.id,
                `groups`.name  As group_name,
                DATE_FORMAT(`groups`.start_date, '%M %d, %m-%Y') AS formatted_date,
                DATE_FORMAT(
                    DATE_ADD(
                        DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                        INTERVAL 2 WEEK
                    ),
                    '%d, %m-%Y'
                    ) AS group_end_date
        FROM `groups` 
        WHERE `groups`.is_active = 1 AND id = :group";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':group' => $_GET['group_id']]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $group]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
