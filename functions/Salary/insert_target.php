<?php

session_start();
require_once "../../Database/connect.php";

echo "<pre>"; 
print_r($_POST);
die();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php?action=add&id=" . $_POST['id']);
        exit();
    }

    try {
        // Retrieve POST data
        $target = $_POST['target'] ?? null;
        $agentId   = $_POST['id'] ?? 0;
        $created_at   = $_POST['created_at'] ?? 0;

        // conver 07-2025 to mysql format
        list($month, $year) = explode("-", $created_at);
        $mysqlDate = "$year-$month-01";

        $data = [
            ':agent_id'  => $agentId,
            ':target'   => $target,
            ':created_at' => $mysqlDate,
        ];

        // insert sql
        insertAbsentDay($pdo, $data);

        $_SESSION['success'] = "Target Added Successfully";
        header("Location: ../../customer-service.php?action=add&id=$agentId");
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
        $_SESSION['error'] = $errors;
        return false;
    }

    return true;
}

function insertAbsentDay(PDO $pdo, array $data): bool
{
    $sql = "INSERT INTO salary_absent_days (
                agent_id, days , reason, created_at ,absent_created_at
            ) VALUES (
                :agent_id, :absent_days, :reason, :created_at, :absent_created_at
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}
