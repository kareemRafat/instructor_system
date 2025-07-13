<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Retrieve POST data
        $cs_name = $_POST['cs_name'] ?? null;
        $email = $_POST['email'] ?? null;
        $agent_id   = $_POST['agent_id'] ?? 0;
        $basic_salary    = (float)($_POST['basic_salary'] ?? 0);
        $overtime_days   = (int)($_POST['overtime_days'] ?? 0);
        $day_value       = (float)($_POST['day_value'] ?? 0);
        $target          = (float)($_POST['target'] ?? 0);
        $bonuses         = (float)($_POST['bonuses'] ?? 0);
        $advances        = (float)($_POST['advances'] ?? 0);
        $absent_days     = (int)($_POST['absent_days'] ?? 0);
        $deduction_days  = (int)($_POST['deduction_days'] ?? 0);
        $created_at_raw  = $_POST['created_at'] ?? null;
        $formatted_date = DateTime::createFromFormat('m-Y', $created_at_raw)->format('Y-m-d');
        $total = $_POST['total'] ?? null;

        if ($total < 0) {
            $_SESSION['errors'][] = "القيمة النهائية قيمة سالبة !";
            header("Location: ../../customer-service.php");
            exit();
        }


        // Send email
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
            'username'       => ucwords($cs_name),
            'month'          => getMonthName($created_at_raw),
            'email'          => $email
        ];

        sendMail($salaryDataToEmail);

        $_SESSION['success'] = 'Report sent successfully';
        header("Location: ../../customer-service.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../../customer-service.php");
        exit();
    }
}

function sendMail($salaryDataToEmail)
{
    // send report when click on send report
    $email = $salaryDataToEmail['email'];
    $username = $salaryDataToEmail['username'];
    $subject = "ملخص الراتب الشهري عن شهر {$salaryDataToEmail['month']}";
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


