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
                g.id,
                g.name AS group_name,
                g.time AS group_time,
                g.day AS group_day,
                instructors.username AS instructor_name,
                branches.name AS branch_name,
                DATE_FORMAT(g.start_date, '%d-%m-%Y') AS formatted_date,
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
                                        ELSE 21
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
                                        ELSE 21
                                    END DAY
                        ),
                        '%M'
                    ) AS group_end_month
        FROM `groups` g
        JOIN instructors ON g.instructor_id = instructors.id 
        JOIN branches ON g.branch_id = branches.id
        WHERE g.is_active = 1 AND (:branch = '' OR branches.id = :branch)
        AND (:instructor IS NULL OR instructors.id = :instructor)
        ORDER BY g.start_date DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':branch' => $_GET['branch_id'],
            ':instructor' => $_GET['instructor_id'] ?? null,
        ]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get groups ids
        $groupsIdsArray = array_map(fn($group) => $group['id'], $groups);
        $groupsIds = implode(',', $groupsIdsArray);

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
        // this section used in index.php page to get the instructors groups
        // Query to fetch all groups
        $stmt = $pdo->prepare("SELECT 
                                    `groups`.*,
                                    branches.name AS branch_name
                                FROM `groups`
                                JOIN branches ON `groups`.branch_id = branches.id
                                WHERE `groups`.is_active = 1
                                AND COALESCE(`groups`.second_instructor_id, `groups`.instructor_id) = :instructor
                    ");

        $stmt->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // check if instructor has two branches or not 
        $stmtBranchCount = $pdo->prepare("
                SELECT COUNT(*) AS branch_count
                FROM branch_instructor
                WHERE instructor_id = :instructor
            ");
        $stmtBranchCount->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmtBranchCount->execute();
        $resultBranchCount = $stmtBranchCount->fetch(PDO::FETCH_ASSOC);

        $branchCount = $resultBranchCount['branch_count'] >= 2;

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $groups, 'isMultiBranch' => $branchCount]);
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
