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
                g.id,
                g.name  As group_name,
                DATE_FORMAT(g.start_date, '%M %d-%m-%Y') AS formatted_date,
                DATE_FORMAT(
                        DATE_ADD(
                            DATE_ADD(
                                g.start_date,
                                INTERVAL CASE
                                            WHEN g.name LIKE '%training%' THEN 2
                                            ELSE 5
                                        END MONTH
                            ),
                            INTERVAL CASE
                                        WHEN g.name LIKE '%training%' THEN 15
                                        ELSE 21
                                    END DAY
                        ),
                        '%d-%m-%Y'
                    ) AS group_end_date
        FROM `groups` g
        WHERE g.is_active = 1 AND id = :group";

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
