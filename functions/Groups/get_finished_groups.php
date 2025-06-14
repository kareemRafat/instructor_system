<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {
    if (isset($_GET['branch_id'])) {
        $query = "SELECT 
                `groups`.id,
                `groups`.name AS group_name,
                `groups`.time AS group_time,
                `groups`.day AS group_day,
                `groups`.has_bonus AS has_bonus,
                instructors.username AS instructor_name,
                branches.name AS branch_name,
                DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
                DATE_FORMAT(`groups`.start_date, '%M') AS month,
                DATE_FORMAT(bonus.finish_date, '%d-%m-%Y') AS group_end_date,
                MONTHNAME(bonus.finish_date) AS group_end_month
        FROM `groups` 
        JOIN instructors ON `groups`.instructor_id = instructors.id 
        JOIN branches ON `groups`.branch_id = branches.id
        JOIN bonus ON bonus.group_id = `groups`.id
        WHERE `groups`.is_active = 0 AND (:branch = '' OR branches.id = :branch)
        AND (:instructor IS NULL OR instructors.id = :instructor)
        ORDER BY `groups`.start_date DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':branch' => $_GET['branch_id'],
            ':instructor' => $_GET['instructor_id'] ?? null,
        ]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Combine group info with track name
        $final = [];
        foreach ($groups as $gr) {
            $final[] = $gr;
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $final]);
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
