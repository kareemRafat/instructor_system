<?php
include_once "../../Database/connect.php";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $branchId = $_GET['branch_id'] ?? null;

    $query = "SELECT 
                g.id,
                b.id AS branch_id,
                g.name AS group_name,
                g.time AS group_time,
                g.day AS group_day,
                i.username AS instructor_name,
                b.name AS branch_name,
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
            JOIN instructors i ON g.instructor_id = i.id
            JOIN branches b ON g.branch_id = b.id
            WHERE g.is_active = 1
            AND g.name LIKE :search
            " . ($branchId ? "AND g.branch_id = :branch_id" : "") . "
            ORDER BY g.start_date DESC
            LIMIT 10";

    $stmt = $pdo->prepare($query);

    $params = [':search' => "%$search%"];
    if ($branchId) {
        $params[':branch_id'] = $branchId;
    }

    $stmt->execute($params);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // get group IDs
    $groupsIds = array_column($groups, 'id');
    $groupsIds = array_map('intval', $groupsIds);
    $groupsIdList = implode(',', $groupsIds);

    // get latest track per group
    if ($groupsIdList) {
        $getTrack = "
            SELECT *
            FROM (
                SELECT 
                    l.group_id as lecGroupId,
                    t.name as track_name,
                    ROW_NUMBER() OVER (PARTITION BY l.group_id ORDER BY l.date DESC) as rn
                FROM lectures l
                JOIN tracks t ON t.id = l.track_id
                WHERE l.group_id IN ($groupsIdList)
            ) AS sub
            WHERE rn = 1";

        $stmt = $pdo->prepare($getTrack);
        $stmt->execute();
        $groupsWithTrack = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $trackMap = [];
        foreach ($groupsWithTrack as $trackRow) {
            $trackMap[$trackRow['lecGroupId']] = $trackRow['track_name'];
        }

        foreach ($groups as &$gr) {
            $gr['track'] = $trackMap[$gr['id']] ?? 'Not Updated';
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $groups]);
}
