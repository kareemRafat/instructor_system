<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

// this query included in the main query $query
$baseQuery = "SELECT 
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
    WHERE `groups`.is_active = 1";

$params = [];

if (isset($_GET['branch_id'])) {

    // Query to fetch lectures along with group name and time if exists
    $baseQuery .= " AND (
                        (`groups`.branch_id = :branch)
                        AND
                        (:time IS NULL OR `groups`.time = :time)
                    )";
    $params[':branch'] = $_GET['branch_id'];
    $params[':time'] = $_GET['time'] ?? Null ;
} elseif (isset($_GET['time'])) {

    // Query to fetch lectures along with group time
    $baseQuery .= " AND `groups`.time = :time  ";
    $params[':time'] = $_GET['time'];
} else {

    // Query to fetch lectures along with group instructor name
    $baseQuery .= " AND  lectures.instructor_id = :instructor";
    $params[':instructor'] = $_GET['instructor_id'];
}


try {
    // Query to fetch all lectures
    $query = "SELECT * FROM (
                $baseQuery
            ) AS ranked_lectures
            WHERE rn = 1
            ORDER BY date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $lectures]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
