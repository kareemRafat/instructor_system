<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkSalaryFormErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php?action=add");
        exit();
    }

    try {

        // Step 2: Retrieve POST data safely
        $instructor_id   = $_POST['instructor_id'] ?? 0;
        $basic_salary    = $_POST['basic_salary'] ?? 0;
        $overtime_days   = $_POST['overtime_days'] ?? 0;
        $day_value       = $_POST['day_value'] ?? 0;
        $target          = $_POST['target'] ?? 0;
        $bonuses         = $_POST['bonuses'] ?? 0;
        $advances        = $_POST['advances'] ?? 0;
        $absent_days     = $_POST['absent_days'] ?? 0;
        $deduction_days  = $_POST['deduction_days'] ?? 0;

        // Convert to float/int safely
        $basic_salary   = (float)$basic_salary;
        $overtime_days  = (int)$overtime_days;
        $day_value      = (float)$day_value;
        $target         = (float)$target;
        $bonuses        = (float)$bonuses;
        $advances       = (float)$advances;
        $absent_days    = (int)$absent_days;
        $deduction_days = (int)$deduction_days;

        // Step 3: Calculate الإجمالي (total)
        $total = ($basic_salary + ($overtime_days * $day_value) + $target + $bonuses - $advances)
            - ($absent_days * $day_value) - ($deduction_days * $day_value);

        if ($total < 0) {
            // It’s a negative value
            $_SESSION['errors'][] = "القيمة النهائية قيمة سالبة !";
            header("Location: ../../customer-service.php?action=add");
            exit();
        }

        // Step 4: Prepare and execute the SQL INSERT
        $sql = "INSERT INTO salary_records (
                    instructor_id, basic_salary, overtime_days, day_value, target,
                    bonuses, advances, absent_days, deduction_days, total
                ) VALUES (
                    :instructor_id, :basic_salary, :overtime_days, :day_value, :target,
                    :bonuses, :advances, :absent_days, :deduction_days, :total
                )";

        $stmt = $pdo->prepare($sql);

        $success = $stmt->execute([
            ':instructor_id'  => $instructor_id,
            ':basic_salary'   => $basic_salary,
            ':overtime_days'  => $overtime_days,
            ':day_value'      => $day_value,
            ':target'         => $target,
            ':bonuses'        => $bonuses,
            ':advances'       => $advances,
            ':absent_days'    => $absent_days,
            ':deduction_days' => $deduction_days,
            ':total'          => $total,
        ]);

        // send email 
        include_once("../../Design/Partials/customer_service/cs-email.php");

        die();

        $_SESSION['success'] = "Month Salary Added Successfully";
        header('Location: ../../customer-service.php');
    } catch (PDOException $e) {
        echo $e->getMessage();
        $_SESSION['errors'][] = $e->getMessage();
        header('Location: ../../customer-service.php?action=add');
    }
}

function checkSalaryFormErrors(array $formData, PDO $pdo): bool
{
    $errors = [];

    // Required: instructor_id
    if (empty($formData['instructor_id'])) {
        $errors['instructor_id'] = "الموظف مطلوب";
    }

    // Required: basic_salary
    if (empty($formData['basic_salary']) || !is_numeric($formData['basic_salary'])) {
        $errors['basic_salary'] = "المرتب الأساسي مطلوب ويجب ان يكون رقم";
    }

    // Optional but must be numeric if set
    $numericFields = [
        'overtime_days' => 'أوفر تايم + مكافأت ',
        'day_value' => 'قيمة اليوم',
        'target' => 'التارجت',
        'bonuses' => 'المكافآت',
        'advances' => 'السلف',
        'absent_days' => 'الغياب',
        'deduction_days' => 'خصم'
    ];

    foreach ($numericFields as $field => $label) {
        if (!isset($formData[$field]) || !is_numeric($formData[$field])) {
            $errors[$field] = "$label يجب ان يكون رقم";
        }
    }

    // Example: Check if instructor exists (optional)
    if (!empty($formData['instructor_id'])) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM instructors WHERE id = ?");
        $stmt->execute([$formData['instructor_id']]);
        if ($stmt->fetchColumn() == 0) {
            $errors['instructor_id'] = "Instructor not found.";
        }
    }

    // Return false with error storage if any
    if (!empty($errors)) {
        $_SESSION['old'] = $formData;
        $_SESSION['error'] = $errors;
        return false;
    }

    return true;
}
