<?php

session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {
    $query = "WITH latest_comment AS (
            SELECT
                l.group_id,
                l.comment,
                DATE_FORMAT( l.date, '%d-%m-%Y') AS date,
                ROW_NUMBER() OVER (PARTITION BY l.group_id ORDER BY l.date DESC) AS rn
            FROM lectures l
        )

        SELECT
            lc.comment,
            lc.date,
            g.id,
            g.name AS group_name,
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
        FROM groups g
        JOIN latest_comment lc ON lc.group_id = g.id AND lc.rn = 1
        WHERE g.is_active = 1 AND g.id = :group";

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
