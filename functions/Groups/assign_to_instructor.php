<?php
session_start();
require_once '../../Database/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newInstructor = $_POST['new-instructor'] ?? null;
    $group_id = $_POST['group'] ?? null;
    $group = getGroupById($group_id, $pdo);

    if (!checkGoupTimeDuplication($pdo, $group, $newInstructor)) {
        $_SESSION['errors']['exists'] = "الوقت المحدد لهذه المجموعة متاح بالفعل مع نفس المدرب";
        header("Location: ../../groups.php?action=edit&group_id=$group_id");
        exit();
    }

    if (empty($newInstructor)) {
        $errors['new-instructor'] = "New Instructor is required.";
        $_SESSION['errors'] = $errors;
        header("Location: ../../groups.php?action=edit&group_id=$group_id");
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE `groups` SET second_instructor_id = :second_instructor_id WHERE id = :id");
        $stmt->execute([
            ':second_instructor_id' => $newInstructor,
            ':id' => $group_id
        ]);

        $_SESSION['success'] = "Group Assigned To Other instructor";

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

/** check if the group time with the same instructor and branch is already exists */
function checkGoupTimeDuplication($pdo, $group, $newInstructor): bool
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM `groups` 
        WHERE 
            is_active = 1
            AND branch_id = :branch
            AND time = :groupTime
            AND day = :groupDay
            AND id != :currentGroupId
            AND (
                instructor_id = :instructor OR 
                second_instructor_id = :instructor
            )
    ");

    $stmt->bindParam(':instructor', $newInstructor);
    $stmt->bindParam(':branch', $group['branch_id']);
    $stmt->bindParam(':groupTime', $group['time']);
    $stmt->bindParam(':groupDay', $group['day']);
    $stmt->bindParam(':currentGroupId', $group['id']); // Exclude current group
    $stmt->execute();
    $count = $stmt->fetchColumn();

    return $count == 0;
}

/** get group details */
function getGroupById($groupId, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `groups` WHERE id = :id AND is_active = 1");
    $stmt->execute([':id' => $groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    return $group;
}
