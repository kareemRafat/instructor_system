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
                instructors.username AS instructor_name,
                branches.name AS branch_name,
                DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
                DATE_FORMAT(`groups`.start_date, '%M') AS month,
                DATE_FORMAT(
                    DATE_ADD(
                        DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                        INTERVAL 2 WEEK
                    ),
                    '%d, %m-%Y'
                    ) AS group_end_date,
                DATE_FORMAT(
                    DATE_ADD(
                        DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                        INTERVAL 2 WEEK
                    ),
                    '%M'
                    ) AS group_end_month
        FROM `groups` 
        JOIN instructors ON `groups`.instructor_id = instructors.id 
        JOIN branches ON `groups`.branch_id = branches.id
        WHERE `groups`.is_active = 1 AND (:branch = '' OR branches.id = :branch)
        AND (:instructor IS NULL OR instructors.id = :instructor)
        ORDER BY `groups`.start_date DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':branch' => $_GET['branch_id'],
            ':instructor' => $_GET['instructor_id'] ?? null,
        ]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get groups ids
        $groupsIds = array_map(fn($group) => $group['id'], $groups);
        $groupsIds = implode(',', $groupsIds);

        // get track
        if (empty($groupsIdsArray)) {
            $groupsWithTrack = [];
        } else {
            // Prepare placeholders for binding
            $placeholders = implode(',', array_fill(0, count($groupsIdsArray), '?'));

            $getTrack = "SELECT *
                    FROM (
                        SELECT 
                            l.group_id as lecGroupId,
                            t.name as track_name,
                            ROW_NUMBER() OVER (PARTITION BY l.group_id ORDER BY l.date DESC) as rn
                        FROM lectures AS l
                        JOIN tracks AS t ON t.id = l.track_id
                        WHERE l.group_id IN ($placeholders)
                    ) AS sub 
                WHERE rn = 1";

            $stmt = $pdo->prepare($getTrack);
            $stmt->execute($groupsIdsArray);
            $groupsWithTrack = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Index track results by group ID
        $trackMap = [];
        foreach ($groupsWithTrack as $trackRow) {
            $trackMap[$trackRow['lecGroupId']] = $trackRow['track_name'];
        }

        // Combine group info with track name
        $final = [];
        foreach ($groups as $gr) {
            $gr['track'] = $trackMap[$gr['id']] ?? 'Not Updated';
            $final[] = $gr;
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $final]);
    } else {

        // Query to fetch all groups
        $stmt = $pdo->prepare("SELECT * FROM `groups` WHERE instructor_id = :instructor AND is_active = 1");
        $stmt->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $groups]);
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
