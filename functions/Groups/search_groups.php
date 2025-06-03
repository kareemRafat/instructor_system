<?php
include_once "../../Database/connect.php";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT 
        `groups`.id,
        `groups`.name AS group_name,
        `groups`.time AS group_time,
        `groups`.day AS group_day,
        instructors.username AS instructor_name,
        branches.name AS branch_name,
        DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
        DATE_FORMAT(`groups`.start_date, '%M') AS month
    FROM `groups` 
    JOIN instructors ON `groups`.instructor_id = instructors.id 
    JOIN branches ON `groups`.branch_id = branches.id
    WHERE `groups`.is_active = 1 
    AND `groups`.name LIKE :search
    AND (:branch_id IS NULL OR `groups`.branch_id = :branch_id)
    ORDER BY `groups`.start_date DESC
    LIMIT 10 ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':search' => "%$search%",
        ':branch_id' => $_GET['branch_id'] ?? Null
    ]);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // get groups ids
    $groupsIds = array_map(fn($group) => $group['id'], $groups);
    $groupsIds = implode(',', $groupsIds);

    // get track
    $getTrack = "SELECT *
                    FROM (
                        SELECT 
                            l.group_id as lecGroupId,
                            t.name as track_name,
                            ROW_NUMBER() OVER (PARTITION BY l.group_id ORDER BY l.date DESC) as rn
                        FROM lectures AS l
                        JOIN tracks AS t ON t.id = l.track_id
                        WHERE l.group_id IN ($groupsIds)
                    ) AS sub
                    WHERE rn = 1";
    $stmt = $pdo->prepare($getTrack);
    $stmt->execute();
    $groupsWithTrack = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Index track results by group ID
    $trackMap = [];
    foreach ($groupsWithTrack as $trackRow) {
        $trackMap[$trackRow['lecGroupId']] = $trackRow['track_name'];
    }

    // Combine group info with track name
    $final = [];
    foreach ($groups as $gr) {
        $gr['track'] = $trackMap[$gr['id']] ?? null;
        $final[] = $gr;
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $final]);
}
