<?php
session_start();
// Database connection
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}


// get count
$countQuery = "SELECT 
                    COUNT(*) AS total 
                FROM `groups` AS g 
                WHERE is_active = 1 
                AND (:branch IS NULL OR g.branch_id = :branch) 
                AND (:instructor IS NULL OR g.instructor_id = :instructor)";

$countStmt = $pdo->prepare($countQuery);
$countStmt->execute([
    ':branch' => $_GET['branch_id'] ?? null,
    ':instructor' => $_GET['instructor_id'] ?? null
]);

$totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

echo $totalCount ;
