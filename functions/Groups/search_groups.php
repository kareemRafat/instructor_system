<?php
include_once "../../Database/connect.php";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT 
        groups.id,
        groups.name AS group_name,
        groups.time AS group_time,
        instructors.username AS instructor_name,
        branches.name AS branch_name,
        DATE_FORMAT(groups.start_date, '%d-%m-%Y') AS formatted_date
    FROM groups 
    JOIN instructors ON groups.instructor_id = instructors.id 
    JOIN branches ON groups.branch_id = branches.id
    WHERE groups.is_active = 1 
    AND groups.name LIKE :search
    AND (:branch_id IS NULL OR groups.branch_id = :branch_id)
    ORDER BY groups.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':search' => "%$search%",
        ':branch_id' => $_GET['branch_id'] ?? Null 
    ]);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $groups]);
}
