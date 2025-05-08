<?php
session_start();
require_once '../../Database/connect.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? null;
    $name = $_POST['name'] ?? null;
    $instructor = $_POST['instructor'] ?? null;
    $branch = $_POST['branch'] ?? null;

    if ($date) {
        $date = date('Y-m-d', strtotime($date));
    }

    if ($date && $name && $instructor && $branch) {
        try {
            $stmt = $pdo->prepare("INSERT INTO groups (name, branch_id, instructor_id, is_active , start_date) VALUES (:name, :branch, :instructor,  1 , :date )");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':instructor', $instructor);
            $stmt->bindParam(':date', $date);
            $stmt->execute();

            $_SESSION['insert_done'] = true ;

            header("Location: ../../groups.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $errors = checkErrors($_POST);
        header("Location: ../../groups.php");
    }
}

/** check errors */
function checkErrors($formData){
    $errors = [];

    if (empty($formData['name'])) {
        $errors['name'] = "Name is required.";
    }

    if (empty($formData['date'])) {
        $errors['date'] = "Date is required.";
    }

    if (empty($formData['instructor'])) {
        $errors['instructor'] = "Instructor is required.";
    }

    if (empty($formData['branch'])) {
        $errors['branch'] = "Branch is required.";
    }

    if (!empty($errors)){
        $_SESSION['errors'] = $errors;
    }

    return $errors;
}