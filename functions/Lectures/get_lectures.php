<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {
    // Query to fetch all lectures
    if (isset($_GET['branch_id'])) {
        // Query to fetch lectures along with group name
        $query = "SELECT * FROM (
                        SELECT 
                            lectures.*,
                            `groups`.name AS group_name,
                            `groups`.time AS group_time,
                            `tracks`.name AS track_name,
                            instructors.username AS instructor_name,
                            DATE_FORMAT(lectures.date, '%M   %d-%m-%Y') AS formatted_date,
                            ROW_NUMBER() OVER (PARTITION BY lectures.group_id ORDER BY lectures.date DESC) AS rn
                        FROM lectures 
                        JOIN `groups` ON lectures.group_id = `groups`.id 
                        JOIN `tracks` ON lectures.track_id = `tracks`.id 
                        JOIN instructors ON lectures.instructor_id = instructors.id
                        WHERE 
                            `groups`.is_active = 1 
                            AND (
                                (:branch IS NULL OR `groups`.branch_id = :branch)
                                AND
                                (:time IS NULL OR `groups`.time = :time)
                            )
                    ) AS ranked_lectures
                    WHERE rn = 1
                    ORDER BY date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':branch', $_GET['branch_id'], PDO::PARAM_INT);
        $stmt->bindParam(':time', $_GET['time']);
    } elseif (isset($_GET['time'])) {

        $query = "SELECT * FROM (
                        SELECT 
                            lectures.*,
                            `groups`.name AS group_name,
                            `groups`.time AS group_time,
                            `tracks`.name AS track_name,
                            instructors.username AS instructor_name,
                            DATE_FORMAT(lectures.date, '%M   %d-%m-%Y') AS formatted_date,
                            ROW_NUMBER() OVER (PARTITION BY lectures.group_id ORDER BY lectures.date DESC) AS rn
                        FROM lectures 
                        JOIN `groups` ON lectures.group_id = `groups`.id 
                        JOIN `tracks` ON lectures.track_id = `tracks`.id 
                        JOIN instructors ON lectures.instructor_id = instructors.id
                        WHERE 
                            `groups`.time = :time 
                            AND `groups`.is_active = 1
                    ) AS ranked_lectures
                    WHERE rn = 1
                    ORDER BY date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':time', $_GET['time']);
    } else {
        // $_GET['instructor_id']
        $query = "SELECT * FROM (
                        SELECT 
                            lectures.*,
                            `groups`.name AS group_name,
                            `groups`.time AS group_time,
                            `tracks`.name AS track_name,
                            instructors.username AS instructor_name,
                            DATE_FORMAT(lectures.date, '%M   %d-%m-%Y') AS formatted_date,
                            ROW_NUMBER() OVER (PARTITION BY lectures.group_id ORDER BY lectures.date DESC) AS rn
                        FROM lectures 
                        JOIN `groups` ON lectures.group_id = `groups`.id 
                        JOIN `tracks` ON lectures.track_id = `tracks`.id 
                        JOIN instructors ON lectures.instructor_id = instructors.id
                        WHERE 
                            lectures.instructor_id = :instructor
                            AND `groups`.is_active = 1
                    ) AS ranked_lectures
                    WHERE rn = 1
                    ORDER BY date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':instructor', $_GET['instructor_id'], PDO::PARAM_INT);
    }


    $stmt->execute();
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $lectures]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
