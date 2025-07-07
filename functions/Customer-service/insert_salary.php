<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkSalaryFormErrors($_POST, $pdo)) {
        header("Location: ../../customer-service.php?action=add&id=" . $_POST['instructor_id']);
        exit();
    }

    try {
        // Retrieve POST data
        $cs_name = $_POST['cs_name'] ?? null;
        $instructor_id   = $_POST['instructor_id'] ?? 0;
        $basic_salary    = (float)($_POST['basic_salary'] ?? 0);
        $overtime_days   = (int)($_POST['overtime_days'] ?? 0);
        $day_value       = (float)($_POST['day_value'] ?? 0);
        $target          = (float)($_POST['target'] ?? 0);
        $bonuses         = (float)($_POST['bonuses'] ?? 0);
        $advances        = (float)($_POST['advances'] ?? 0);
        $absent_days     = (int)($_POST['absent_days'] ?? 0);
        $deduction_days  = (int)($_POST['deduction_days'] ?? 0);
        $created_at_raw  = $_POST['created_at'] ?? '01-1970';
        $formatted_date = DateTime::createFromFormat('m-Y', $created_at_raw)->format('Y-m-d');

        // Calculate total
        $total = ($basic_salary + ($overtime_days * $day_value) + $target + $bonuses - $advances)
            - ($absent_days * $day_value) - ($deduction_days * $day_value);

        if ($total < 0) {
            $_SESSION['errors'][] = "القيمة النهائية قيمة سالبة !";
            header("Location: ../../customer-service.php?action=add&id=$instructor_id");
            exit();
        }

        // Prepare data array once
        $salaryData = [
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
            ':created_at'     => $formatted_date
        ];

        // Check for existing record
        $checkStmt = $pdo->prepare("
        SELECT COUNT(*) FROM salary_records 
        WHERE instructor_id = :instructor_id AND created_at = :created_at
    ");
        $checkStmt->execute([
            ':instructor_id' => $instructor_id,
            ':created_at' => $formatted_date
        ]);

        if ($checkStmt->fetchColumn()) {
            updateSalaryRecord($pdo, $salaryData);
            $_SESSION['success'] = "Month Salary Updated";
        } else {
            insertSalaryRecord($pdo, $salaryData);
            $_SESSION['success'] = "Month Salary Added Successfully";
        }

        // Send email if requested
        if (isset($_POST['send_report'])) {
            $salaryDataToEmail = [
                'basic_salary'   => $basic_salary,
                'overtime_days'  => $overtime_days,
                'day_value'      => $day_value,
                'target'         => $target,
                'bonuses'        => $bonuses,
                'advances'       => $advances,
                'absent_days'    => $absent_days,
                'deduction_days' => $deduction_days,
                'total'          => $total,
                'cs_name'        => ucwords($cs_name),
                'month'          => getMonthName($created_at_raw)
            ];
            sendMail($salaryDataToEmail);
        }

        header("Location: ../../customer-service.php?action=add&id=$instructor_id");
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../../customer-service.php?action=add");
        exit();
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

function sendMail($salaryDataToEmail)
{
    // send report when click on send report
    // email html design path
    include_once "../../Design/Partials/customer_service/cs-email.php";
    $emailBody = renderEmailTemplate($salaryDataToEmail);
    // send email 
    include_once("../send-email.php");
}


/** get month name to send it with email */
function getMonthName($created_at)
{
    $month_map = [
        1 => 'يناير',
        2 => 'فبراير',
        3 => 'مارس',
        4 => 'أبريل',
        5 => 'مايو',
        6 => 'يونيو',
        7 => 'يوليو',
        8 => 'أغسطس',
        9 => 'سبتمبر',
        10 => 'أكتوبر',
        11 => 'نوفمبر',
        12 => 'ديسمبر'
    ];
    $month_number = DateTime::createFromFormat('m-Y', $created_at)->format('n'); // Numeric month (1-12)
    return $month_map[$month_number];
}


function insertSalaryRecord(PDO $pdo, array $data): bool {
    $sql = "INSERT INTO salary_records (
                instructor_id, basic_salary, overtime_days, day_value, target,
                bonuses, advances, absent_days, deduction_days, total , created_at
            ) VALUES (
                :instructor_id, :basic_salary, :overtime_days, :day_value, :target,
                :bonuses, :advances, :absent_days, :deduction_days, :total , :created_at
            )";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function updateSalaryRecord(PDO $pdo, array $data): bool {
    $sql = "UPDATE salary_records SET
                basic_salary = :basic_salary,
                overtime_days = :overtime_days,
                day_value = :day_value,
                target = :target,
                bonuses = :bonuses,
                advances = :advances,
                absent_days = :absent_days,
                deduction_days = :deduction_days,
                total = :total
            WHERE instructor_id = :instructor_id AND created_at = :created_at";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}