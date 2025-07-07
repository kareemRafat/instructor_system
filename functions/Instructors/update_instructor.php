<?php
session_start();
require_once "../../Database/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate input
    if (!checkErrors($_POST, $_POST['id'], $pdo)) {
        header("Location: ../../instructors.php");
        return;
    }


    try {

        $id = $_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $branchIds = $_POST['branch_ids'] ?? [];

        $pdo->beginTransaction();

        //  password logic
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE  instructors SET password = :password WHERE id = $id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':password' => $hashedPassword,
            ]);
        }

        // update instructor
        $query = "UPDATE instructors SET username = :username , email = :email WHERE id = $id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);

        // update pivot table
        updateOnChange($pdo, $branchIds, $id);

        // Commit transaction if both queries succeeded
        $pdo->commit();

        $_SESSION['success'] = "Instructor updated successfully";
        header('Location: ../../instructors.php');
    } catch (Exception $e) {
        // Roll back transaction if any error occurs
        $pdo->rollBack();

        echo $e->getMessage();
        $_SESSION['errors'] = $e->getMessage();
        header('Location: ../../instructors.php');
    }
}


/** check errors */
function checkErrors($formData, $id,  $pdo): bool
{

    $errors = [];

    // Validate username length
    if (strlen($formData['username']) < 3) {
        $errors['username'] = "Username must be at least 3 characters long";
    }

    if (empty($formData['username'])) {
        $errors['username'] = "username is required.";
    }

    if (empty($formData['email'])) {
        $errors['email'] = "Email is required.";
    }

    if (empty($formData['branch_ids']) || !is_array($formData['branch_ids'])) {
        $errors['branch'] = "At least one branch must be selected.";
    }


    if (isInstructorNameDuplicated($formData['username'], $id,  $pdo)) {
        $errors['username'] = 'Instructor name already Exists';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        return false;
    }

    return true;
}

function isInstructorNameDuplicated($name, $instructorId, $pdo): bool
{
    // Check if username already exists
    $query = "SELECT id FROM instructors WHERE username = :username AND id != :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':username' => $name,
        ':id' => $instructorId
    ]);
    return $stmt->rowCount() > 0;
}

function updateOnChange($pdo, $branchIds, $id)
{
    // 1. Get existing branch IDs for this instructor
    $existingQuery = "SELECT branch_id FROM branch_instructor WHERE instructor_id = :id";
    $existingStmt = $pdo->prepare($existingQuery);
    $existingStmt->execute([':id' => $id]);
    $existingBranchIds = $existingStmt->fetchAll(PDO::FETCH_COLUMN);

    // 2. Determine which to insert and which to delete
    $toInsert = array_diff($branchIds, $existingBranchIds);
    $toDelete = array_diff($existingBranchIds, $branchIds);

    // 3. Insert new branches
    if (!empty($toInsert)) {
        $insertStmt = $pdo->prepare("INSERT INTO branch_instructor (instructor_id, branch_id) VALUES (:instructor_id, :branch_id)");
        foreach ($toInsert as $branchId) {
            $insertStmt->execute([
                ':instructor_id' => $id,
                ':branch_id' => $branchId
            ]);
        }
    }

    // 4. Delete unselected branches
    if (!empty($toDelete)) {
        $deleteStmt = $pdo->prepare("DELETE FROM branch_instructor WHERE instructor_id = :instructor_id AND branch_id = :branch_id");
        foreach ($toDelete as $branchId) {
            $deleteStmt->execute([
                ':instructor_id' => $id,
                ':branch_id' => $branchId
            ]);
        }
    }
}
