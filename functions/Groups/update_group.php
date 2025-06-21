<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['date'] ?? null;
    $group_id = $_POST['group_id'] ?? null;
    $time = $_POST['time'] ?? null;
    $groupTime = $_POST['grouptime'] ?? null;
    $groupDay = $_POST['groupDay'] ?? null;
    $name = trim($_POST['name']) ?? null;
    $instructor = $_POST['instructor'] ?? null;
    $branch = $_POST['branch'] ?? null;


    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../groups.php?action=edit&group_id=$group_id");
        return;
    }

    if ($date) {
        $date = date('Y-m-d', strtotime($date));
        $time = date('H:i:s', strtotime($time));
        $date = $date . ' ' . $time;
    }

    try {
        $stmt = $pdo->prepare("UPDATE `groups` SET name = :name, branch_id = :branch, instructor_id = :instructor, start_date = :date, time = :groupTime , day = :groupDay WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':branch', $branch);
        $stmt->bindParam(':instructor', $instructor);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':groupTime', $groupTime);
        $stmt->bindParam(':groupDay', $groupDay);
        $stmt->bindParam(':id', $group_id);
        $stmt->execute();

        $_SESSION['success'] = "Group updated successfully";

        // if the request came from tables.php or groups.php
        if (isset($_SESSION['page'])) {
            header('location: ../../' . $_SESSION['page'] . '?branch=' . $_SESSION['current_branch_id']);
            unset($_SESSION['page']);
            unset($_SESSION['current_branch_id']);
        } else {
            header('location: ../../groups.php');
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = $e->getMessage();
        header("Location: ../../groups.php?action=edit&id=$group_id");
        exit();
    }
}


/** check errors */
function checkErrors($formData, $pdo)
{
    $errors = [];

    if (empty($formData['name'])) {
        $errors['name'] = "Name is required.";
    }

    if (isGroupNameDuplicated($formData['name'], $_POST['old_name'], $pdo)) {
        $errors['name'] = "Group name already Exists";
    }

    if (empty($formData['date'])) {
        $errors['date'] = "Date is required.";
    }

    if (empty($formData['groupDay'])) {
        $errors['groupDay'] = "Day is required.";
    }

    if (empty($formData['grouptime'])) {
        $errors['grouptime'] = "Date is required.";
    }

    if (empty($formData['instructor'])) {
        $errors['instructor'] = "Instructor is required.";
    }

    if (empty($formData['branch'])) {
        $errors['branch'] = "Branch is required.";
    }


    if (!empty($errors)) {
        $_SESSION['old'] = $_POST;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}


/** check group name duplicated */
function isGroupNameDuplicated($name, $oldName, $pdo): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) as Count , name FROM `groups` WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    return $group['Count'] > 0 and $name !== $oldName;
}
