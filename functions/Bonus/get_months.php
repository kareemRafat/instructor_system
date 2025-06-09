<?php 

session_start();

// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

try {

    $queryMonth = "SELECT DISTINCT  
                    MONTHNAME(`bonus`.finish_date) AS month, 
                    YEAR(`bonus`.finish_date) AS year
                    FROM bonus
                    ORDER BY bonus.finish_date DESC
                    LIMIT 10";
    $stmtMonth = $pdo->prepare($queryMonth);
    $stmtMonth->execute();
    $dates = $stmtMonth->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $dates]);

} catch(PDOException $e) {
    echo json_encode(['status' => 'success', 'data' => $e->getMessage()]);
}