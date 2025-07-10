<?php

session_start();
require_once "../../Database/connect.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Retrieve POST data
        $target = $_POST['target'] ?? null;
        $created_at   = $_POST['created_at'] ?? 0;
        $target_created_at   = $_POST['target_created_at'] ?? 0;

        // conver 07-2025 to mysql format
        list($month, $year) = explode("-", $created_at);
        $mysqlDate = "$year-$month-01";

        if (!checkErrors($_POST, $pdo)) {
            header("Location: ../../customer-service.php?action=add&id=" . $_POST['id'] . "&month={$month}&year={$year}");
            exit();
        }

        $data = [
            ':target'   => $target,
            ':created_at' => $mysqlDate,
            ':target_created_at' => $target_created_at,
        ];

        // insert sql
        insertOrUpdateTarget($pdo, $data);

        $_SESSION['success'] = "Target Added Successfully";
        header("Location: ../../customer-service.php?action=add&id=" . $_POST['id'] . "&month={$month}&year={$year}");
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../../customer-service.php?action=add");
        exit();
    }
}

function checkErrors(array $formData, PDO $pdo): bool
{
    $errors = [];

    // Required: instructor_id
    if (empty($formData['target'])) {
        $errors['target'] = "التارجت مطلوب";
    }

    // Return false with error storage if any
    if (!empty($errors)) {
        $_SESSION['old'] = $formData;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}


function insertOrUpdateTarget(PDO $pdo, array $data): bool
{
    // Extract month & year from the incoming created_at date
    $createdAt = new DateTime($data[':created_at']);
    $month = $createdAt->format('m');
    $year = $createdAt->format('Y');



    // 1. Check if a record exists for the same month and year
    $checkSql = "SELECT id FROM salary_target 
                 WHERE MONTH(created_at) = :month AND YEAR(created_at) = :year";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute(['month' => $month, 'year' => $year]);
    $existingId = $checkStmt->fetchColumn();

    if ($existingId) {
        // 2. Update if exists
        $updateSql = "UPDATE salary_target 
                      SET target = :target, target_created_at = :target_created_at 
                      WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        return $updateStmt->execute([
            'target' => $data[':target'],
            'target_created_at' => $data[':target_created_at'],
            'id' => $existingId,
        ]);
    } else {
        // 3. Insert if not found
        $insertSql = "INSERT INTO salary_target (target, created_at, target_created_at) 
                      VALUES (:target, :created_at, :target_created_at)";
        $insertStmt = $pdo->prepare($insertSql);
        return $insertStmt->execute($data);
    }
}
