<?php 

session_start();

// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {

    $queryMonth = "SELECT 
                    bonus.group_id,
                    DATE_FORMAT(`bonus`.finish_date, '%M') AS month, 
                    DATE_FORMAT(`bonus`.finish_date, '%Y') AS year
                    FROM bonus
                    ORDER BY bonus.finish_date DESC";
    $stmtMonth = $pdo->prepare($queryMonth);
    $stmtMonth->execute();
    $dates = $stmtMonth->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $dates]);

} catch(PDOException $e) {
    echo json_encode(['status' => 'success', 'data' => $e->getMessage()]);
}