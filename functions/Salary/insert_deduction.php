<?php

session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php?action=add&id=" . $_POST['id']);
        exit();
    }

    try {
        // Retrieve POST data
        $days = $_POST['deduction'] ?? null;
        $deduction_reason = $_POST['deduction-reason'] ?? null;
        $agentId   = $_POST['id'] ?? 0;
        $created_at   = $_POST['created_at'] ?? 0;
        $deduction_created_at  = $_POST['deduction_created_at'] ?? 0;

        // conver 07-2025 to mysql format
        list($month, $year) = explode("-", $created_at);
        $mysqlDate = "$year-$month-01";

        $data = [
            ':agent_id'  => $agentId,
            ':days'   => $days,
            ':reason'  => $deduction_reason,
            ':created_at' => $mysqlDate,
            ':deductions_created_at' => $deduction_created_at
        ];

        // insert sql
        insertBonus($pdo, $data);

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
    if (empty($formData['deduction'])) {
        $errors['deduction'] = "الموظف مطلوب";
    }

    if (empty($formData['deduction-reason'])) {
        $errors['deduction-reason'] = "تاريخ المحاسبة مطلوب";
    }


    // Return false with error storage if any
    if (!empty($errors)) {
        $_SESSION['old'] = $formData;
        $_SESSION['error'] = $errors;
        return false;
    }

    return true;
}

function insertBonus(PDO $pdo, array $data): bool
{
    $sql = "INSERT INTO salary_deductions (
                agent_id, days, reason, created_at ,deductions_created_at
            ) VALUES (
                :agent_id, :days, :reason, :created_at, :deductions_created_at
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}
