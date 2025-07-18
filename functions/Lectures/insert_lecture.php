<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!checkErrors($_POST, $pdo)) {
        header("Location: ../../index.php");
        return;
    }

    $group = $_POST['group'] ?? null;
    $track = $_POST['track'] ?? null;
    $comment = $_POST['comment'] ?? null;
    $date = $_POST['date'] ?? null;
    $branch = groupBranchId($pdo,$group);

    try {
        
        // insert New Comment
        $stmt = $pdo->prepare("INSERT INTO lectures (group_id, track_id, instructor_id, branch_id, comment , date) VALUES (:group, :track, :instructor, :branch , :comment , :date )");
        $stmt->bindParam(':group', $group);
        $stmt->bindParam(':track', $track);
        $stmt->bindParam(':instructor', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':branch', $branch);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $_SESSION['success'] = "Data added successfully";
        header("Location: ../../index.php");
    } catch (PDOException $e) {
        $_SESSION['errors'] = $e->getMessage();
        echo "Error: " . $e->getMessage();
    }
}

/** get branch id of the selected group */
function groupBranchId($pdo,$group) {
    $stmt = $pdo->prepare("SELECT 
                            id , branch_id 
                        From `groups`
                        WHERE id = $group
    ");

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['branch_id'];
}

/** check errors */
function checkErrors($formData)
{
    $errors = [];

    if (empty($formData['group'])) {
        $errors['group'] = "Group is required.";
    }

    if (empty($formData['track'])) {
        $errors['track'] = "Track is required.";
    }

    if (empty($formData['comment'])) {
        $errors['comment'] = "Comment is required.";
    }

    if (!empty($errors)) {
        $_SESSION['old'] = $_POST;
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}
