<?php
include_once "../../Database/connect.php";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT 
        groups.id,
        groups.name AS group_name,
        instructors.username AS instructor_name,
        branches.name AS branch_name 
    FROM groups 
    JOIN instructors ON groups.instructor_id = instructors.id 
    JOIN branches ON groups.branch_id = branches.id
    WHERE groups.is_active = 1 
    AND groups.name LIKE :search
    ORDER BY groups.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} else {
    $query = "SELECT 
        groups.id,
        groups.name AS group_name,
        instructors.username AS instructor_name,
        branches.name AS branch_name 
    FROM groups 
    JOIN instructors ON groups.instructor_id = instructors.id 
    JOIN branches ON groups.branch_id = branches.id
    WHERE groups.is_active = 1 
    ORDER BY groups.start_date DESC";

    $stmt = $pdo->prepare($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
}
