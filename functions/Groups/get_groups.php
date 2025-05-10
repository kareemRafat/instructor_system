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
                groups.id,
                groups.name AS group_name,
                groups.time AS group_time,
                instructors.username AS instructor_name,
                branches.name AS branch_name,
                DATE_FORMAT(groups.start_date, '%d-%m-%Y') AS formatted_date
        FROM groups 
        JOIN instructors ON groups.instructor_id = instructors.id 
        JOIN branches ON groups.branch_id = branches.id
        WHERE groups.is_active = 1 AND (:branch = '' OR branches.id = :branch)
        ORDER BY groups.start_date DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':branch' => $_GET['branch_id']]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $groups]);
    } else {

        // Query to fetch all groups
        $stmt = $pdo->prepare("SELECT * FROM groups WHERE instructor_id = :instructor AND is_active = 1");
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
