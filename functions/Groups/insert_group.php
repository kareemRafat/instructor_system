<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../groups.php");
        return;
    }

    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $groupTime = $_POST['grouptime'] ?? null;
    $groupDay = $_POST['groupDay'] ?? null;
    $name = $_POST['name'] ?? null;
    $instructor = $_POST['instructor'] ?? null;
    $branch = $_POST['branch'] ?? null;

    if ($date) {
        $date = date('Y-m-d', strtotime($date));
        $time = date('H:i:s', strtotime($time));
        $date = $date . ' ' . $time;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO `groups` (name, branch_id, instructor_id, is_active , start_date , time , day) VALUES (:name, :branch, :instructor,  1 , :date , :groupTime , :groupDay )");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':branch', $branch);
        $stmt->bindParam(':instructor', $instructor);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':groupTime', $groupTime);
        $stmt->bindParam(':groupDay', $groupDay);
        $stmt->execute();

        $_SESSION['success'] = "Group added successfully";
        header("Location: ../../groups.php");
    } catch (PDOException $e) {
        $_SESSION['errors'] = $e->getMessage();
        header("Location: ../../groups.php");
        exit();
    }
}


/** check errors */
function checkErrors($formData , $pdo)
{
    $errors = [];

    if (empty($formData['name'])) {
        $errors['name'] = "Name is required.";
    }

    if (isGroupNameDuplicated($formData['name'], $pdo)) {
        $errors['name'] = "Group name already Exists";
    }

    if (empty($formData['date'])) {
        $errors['date'] = "Date is required.";
    }

    if (empty($formData['grouptime'])) {
        $errors['grouptime'] = "Date is required.";
    }

    if (empty($formData['groupDay'])) {
        $errors['groupDay'] = "Day is required.";
    }

    if (empty($formData['instructor'])) {
        $errors['instructor'] = "Instructor is required.";
    }

    if (empty($formData['branch'])) {
        $errors['branch'] = "Branch is required.";
    }

    
    if (!empty($errors)) {
        $_SESSION['old'] = $_POST ;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}


/** check group name duplicated */
function isGroupNameDuplicated($name, $pdo):bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM `groups` WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}