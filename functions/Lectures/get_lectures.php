<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

// Base query for lectures
$baseQuery = "SELECT 
        lectures.*,
        `groups`.name AS group_name,
        `groups`.time AS group_time,
        `groups`.day AS group_day,
        `tracks`.name AS track_name,
        instructors.username AS instructor_name,
        DATE_FORMAT(lectures.date, '%M %d-%m-%Y') AS latest_comment_date,
        DATE_FORMAT(`groups`.start_date, '%M %d-%m-%Y') AS group_start_date,
        DATE_FORMAT(
                DATE_ADD(
                    DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                    INTERVAL 2 WEEK
                ),
                '%d-%m-%Y'
                ) AS group_end_date,
        ROW_NUMBER() OVER (PARTITION BY lectures.group_id ORDER BY lectures.date DESC) AS rn
    FROM lectures 
    JOIN `groups` ON lectures.group_id = `groups`.id 
    JOIN `tracks` ON lectures.track_id = `tracks`.id 
    JOIN instructors ON instructors.id = COALESCE(`groups`.second_instructor_id, `groups`.instructor_id)
    WHERE `groups`.is_active = 1";

$params = [];

if (isset($_GET['branch_id'])) {
    $baseQuery .= " AND `groups`.branch_id = :branch";
    $params[':branch'] = $_GET['branch_id'];
    if (isset($_GET['time'])) {
        $baseQuery .= " AND `groups`.time = :time";
        $params[':time'] = $_GET['time'];
    }

    if (isset($_GET['search'])) {
        $baseQuery .= " AND `groups`.name LIKE :search";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }

    if (isset($_GET['instructor_id'])) {
        $baseQuery .= " AND lectures.instructor_id = :instructor";
        $params[':instructor'] = $_GET['instructor_id'];
    }
} elseif (isset($_GET['search'])) {
    $baseQuery .= " AND `groups`.name LIKE :search";
    $params[':search'] = '%' . $_GET['search'] . '%';
} elseif (isset($_GET['time'])) {
    $baseQuery .= " AND `groups`.time = :time";
    $params[':time'] = $_GET['time'];
} else {
    $baseQuery .= " AND lectures.instructor_id = :instructor";
    $params[':instructor'] = $_GET['instructor_id'];
}

try {
    // Final query to fetch latest lectures per group
    $query = "WITH RankedLectures AS (
                $baseQuery
            )
            SELECT 
                branch_id,
                group_id,
                group_name,
                group_time,
                group_day,
                group_start_date,
                group_end_date,
                track_id,
                track_name,
                instructor_name,
                comment,
                latest_comment_date
            FROM RankedLectures
            WHERE rn = 1";

    // Apply track_id filter only to the latest lecture
    if (isset($_GET['track_id'])) {
        $query .= " AND track_id = :track";
        $params[':track'] = $_GET['track_id'];
    }

    $query .= " ORDER BY date DESC";

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
