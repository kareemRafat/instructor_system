<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../groups.php?action=finish_group&group_id=" . $_POST['group_id']);
        exit();
    }

    $group_id = $_POST['group_id'] ?? null;
    $group_name = $_POST['group_name'] ?? null;
    $finish_date = $_POST['finish_date'] ?? null;
    $total_students = $_POST['total_students'] ?? null;
    $unpaid_students = $_POST['unpaid_students'] ?? null;

    if ($finish_date) {
        $finish_date = date('Y-m-d', strtotime($finish_date));
        $time = date('H:i:s');
        $finish_date = $finish_date . ' ' . $time;
    }

    $hasBonus = (($unpaid_students / $total_students) * 100) < 20 ? 1 : 0;

    if ($group_id) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Update group to set is_active to 0
            $stmt = $pdo->prepare("UPDATE `groups` SET is_active = 0 , has_bonus = :hasBonus WHERE id = :group_id");
            $stmt->execute([
                ':hasBonus' => $hasBonus,
                ':group_id' => $group_id
            ]);

            // delete from lectures when the group is finished
            $stmtDel = $pdo->prepare("DELETE FROM lectures WHERE group_id = :group_id");
            $stmtDel->execute([':group_id' => $group_id]);

            // check if group exists in bonus table
            if (!checkGroupExists($pdo, $group_id)) {
                // insert in bonus table
                $stmtBonus = $pdo->prepare("INSERT INTO `bonus` (group_id , total_students , unpaid_students , finish_date) VALUES (:group_id , :total , :unpaid , :finish_date )");
                $stmtBonus->execute([
                    ':group_id' => $group_id,
                    ':total' => $total_students,
                    ':unpaid' => $unpaid_students,
                    ':finish_date' => $finish_date
                ]);
            }

            // Commit transaction if both queries succeeded
            $pdo->commit();
            // send email to instructor
            $instructorGroupInfo = [
                'group_name'      => $group_name,
                'end_date'        => $finish_date,
                'has_bonus'       => $hasBonus,
                'bonus_amount'    => 500
            ];
            sendMail($instructorGroupInfo);


            $_SESSION['success'] = 'Group Finish successfully';

            // if the request came from tables.php or groups.php
            if (isset($_SESSION['page'])) {
                header('location: ../../' . $_SESSION['page'] . '?branch=' . $_SESSION['current_branch_id']);
                unset($_SESSION['page']);
                unset($_SESSION['current_branch_id']);
            } else {
                header('location: ../../groups.php');
            }
        } catch (PDOException $e) {
            // Roll back transaction if any error occurs
            $pdo->rollBack();

            $_SESSION['errors']['finish'] = 'Something went Wrong';
            header('location: ../../groups.php');
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Group ID is required']);
    }
}

/** check errors */
function checkErrors($formData, $pdo)
{
    $errors = [];

    if (empty($formData['group_id'])) {
        $errors['group_id'] = "group_id is required.";
    }

    if (empty($formData['finish_date'])) {
        $errors['finish_date'] = "finish_date is required.";
    }

    if (empty($formData['total_students'])) {
        $errors['total_students'] = "Total group Students count is required.";
    }

    if (!isset($formData['unpaid_students']) || $formData['unpaid_students'] === '') {
        $errors['unpaid_students'] = "Unpaid Group Students count is required.";
    }

    if ($formData['unpaid_students'] > $formData['total_students']) {
        $errors['unpaid_studentss'] = "يجب ان يكون عدد المتبقين اقل من إجمالي الطلاب ";
    }

    if (!empty($errors)) {
        $_SESSION['old'] = $_POST;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}

/** check if group in bonus  */
function checkGroupExists($pdo, $group_id)
{
    // Check if the group_id already exists in bonus table
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM bonus WHERE group_id = :group_id");
    $stmtCheck->execute([':group_id' => $group_id]);
    return  $stmtCheck->fetchColumn();
}

/** send email when finish group */
function sendMail($instructorGroupInfo)
{
    // send report when click on send report
    // email html design path
    include_once "../../Design/Partials/Instructors/inst-email.php";
    $emailBody = renderGroupFinishEmail($instructorGroupInfo);
    // send email 
    include_once("../send-email.php");
}
