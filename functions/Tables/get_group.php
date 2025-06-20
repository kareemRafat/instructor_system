<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? null;
    $branch_id = $_POST['branch_id'] ?? null;

    try {
        $stmt = $pdo->prepare("SELECT 
                            IF(g.name LIKE '%training%', 'training', g.name) AS name,
                            g.id,
                            g.day,
                            b.name AS branch_name,
                            g.time,
                            g.is_active,
                            latest_lecture.name AS track_name,
                            i.username AS instructor_name,
                            DATE_FORMAT(g.start_date, '%d-%m-%Y') AS start_date,
                            DATE_FORMAT(g.start_date, '%M') AS month,
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
                                                        ELSE 14
                                                    END DAY
                                        ),
                                    '%d-%m-%Y'
                                ) AS group_end_date,
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
                                                        ELSE 14
                                                    END DAY
                                        ),
                                    '%M'
                                ) AS group_end_month
                        FROM `groups` g
                        JOIN instructors i ON i.id = COALESCE(g.second_instructor_id, g.instructor_id)
                        JOIN branches b ON g.branch_id = b.id
                        LEFT JOIN (
                            SELECT l1.group_id, t.name
                            FROM lectures l1
                            JOIN tracks t ON t.id = l1.track_id
                            WHERE l1.date = (
                                SELECT MAX(l2.date)
                                FROM lectures l2
                                WHERE l2.group_id = l1.group_id
                            )
                        ) latest_lecture ON latest_lecture.group_id = g.id
                        WHERE g.is_active = 1 AND g.branch_id = :branch AND g.id = :group");

        $stmt->execute([
            ':branch' => $branch_id ?? 1,
            ':group' => $group_id ?? 1,
        ]);
        $group = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $group]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => 'Something Went Wrong']);
        die();
    }
}
