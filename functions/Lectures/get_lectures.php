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

    // Query to fetch lectures along with group name
    $query = "SELECT lectures.*,
                groups.name AS group_name ,
                DATE_FORMAT(lectures.date, '%Y-%m-%d') AS formatted_date
              FROM lectures 
              JOIN groups ON lectures.group_id = groups.id
              WHERE lectures.instructor_id = :instructor
              GROUP BY lectures.group_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':instructor', $_POST['instructor_id'], PDO::PARAM_INT);
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
