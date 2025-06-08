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
        return;
    }

    $group_id = $_POST['group_id'] ?? null;
    $finish_date = $_POST['finish_date'] ?? null;
    $total_students = $_POST['total_students'] ?? null;
    $unpaid_students = $_POST['unpaid_students'] ?? null;



    if ($group_id) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Update group to set is_active to 0 and set finish_date
            $stmt = $pdo->prepare("UPDATE `groups` SET is_active = 0 WHERE id = :group_id");
            $stmt->bindParam(':group_id', $group_id);
            $stmt->execute();

            // delete from lectures when the group is finished
            $stmtDel = $pdo->prepare("DELETE FROM lectures WHERE group_id = :group_id");
            $stmtDel->bindParam(':group_id', $group_id);
            $stmtDel->execute();

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

            header('location: ../../groups.php');
        } catch (PDOException $e) {
            // Roll back transaction if any error occurs
            echo"<pre>";
            print_r($e);
            $pdo->rollBack();

            // header('location: ../../groups.php');
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

    if (empty($formData['unpaid_students'])) {
        $errors['unpaid_students'] = "Unpaid Group Students count is required.";
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
