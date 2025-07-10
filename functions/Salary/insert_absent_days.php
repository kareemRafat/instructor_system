<?php

session_start();
require_once "../../Database/connect.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Retrieve POST data
        $absent_days = $_POST['absent_days'] ?? null;
        $absent_reason = $_POST['absent-reason'] ?? null;
        $agentId   = $_POST['id'] ?? 0;
        $created_at   = $_POST['created_at'] ?? 0;
        $absent_created_at  = $_POST['absent_created_at'] ?? 0;

        // conver 07-2025 to mysql format
        list($month, $year) = explode("-", $created_at);
        $mysqlDate = "$year-$month-01";

        $data = [
            ':agent_id'  => $agentId,
            ':absent_days'   => $absent_days,
            ':reason'  => $absent_reason,
            ':created_at' => $mysqlDate,
            ':absent_created_at' => $absent_created_at
        ];

        if (!checkErrors($_POST, $pdo)) {
            header("Location: ../../customer-service.php?action=add&id=" . $_POST['id'] . "&month={$month}&year={$year}");
            exit();
        }

        // insert sql
        insertAbsentDay($pdo, $data);

        $_SESSION['success'] = "Absent Day Added Successfully";
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
    if (empty($formData['absent_days'])) {
        $errors['absent_days'] = "عدد ايام الإجازة مطلوب";
    }

    if (empty($formData['absent-reason'])) {
        $errors['absent-reason'] = "سبب  الاجازة مطلوب";
    }

    if (empty($formData['absent_created_at'])) {
        $errors['absent_created_at'] = "تاريخ الاجازة مطلوب";
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
