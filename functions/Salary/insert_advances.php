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
        $advances = $_POST['advances'] ?? null;
        $advances_reason = $_POST['advances-reason'] ?? null;
        $agentId   = $_POST['id'] ?? 0;
        $created_at   = $_POST['created_at'] ?? 0;
        $advances_created_at  = $_POST['advances_created_at'] ?? 0;

        // conver 07-2025 to mysql format
        list($month, $year) = explode("-", $created_at);
        $mysqlDate = "$year-$month-01";

        $data = [
            ':agent_id'  => $agentId,
            ':advances'   => $advances,
            ':reason'  => $advances_reason,
            ':created_at' => $mysqlDate,
            ':advances_created_at' => $advances_created_at
        ];

        // insert sql
        insertBonus($pdo, $data);

        $_SESSION['success'] = "Advances Added Successfully";
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
    if (empty($formData['advances'])) {
        $errors['advances'] = "قيمة السلف مطلوب";
    }

    if (empty($formData['advances-reason'])) {
        $errors['advances-reason'] = "سبب عملية السلف مطلوب";
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
    $sql = "INSERT INTO salary_advances (
                agent_id, amount, reason, created_at ,advances_created_at
            ) VALUES (
                :agent_id, :advances, :reason, :created_at, :advances_created_at
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}
