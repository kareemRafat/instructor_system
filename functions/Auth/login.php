<?php
session_start();
require_once '../../Database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];
    $rememberMe = isset($_POST['remember']);

    // Prepare and execute the query
    $stmt = $pdo->prepare('SELECT * FROM instructors WHERE username = :username');
    $stmt->bindParam(':username', $inputUsername);
    $stmt->execute();

    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

    define("ROLE", $instructor['role']);

    if ($instructor && $instructor['is_active'] == 0) {
        $_SESSION['error'] = errorDiv('Account Suspended');
        header("Location: ../../login.php");
        exit();
    }

    if ($instructor && password_verify($inputPassword, $instructor['password'])) {

        $_SESSION['user_id'] = $instructor['id'];
        $_SESSION['role'] = ROLE;

        if ($rememberMe) {
            // Generate a secure token
            $token = bin2hex(random_bytes(32));
            $instructorId = $instructor['id'];
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

            // Store the remember-me token in the database
            $stmt = $pdo->prepare('INSERT INTO remember_tokens (instructor_id, token, expiry) VALUES (:instructor_id, :token, :expiry)');
            $stmt->execute([
                'instructor_id' => $instructorId,
                'token' => hash('sha256', $token),
                'expiry' => $expiry
            ]);

            // Set a secure cookie with the token
            setcookie(
                'remember_token',
                $token,
                strtotime('+30 days'),
                '/',
                '',
                true,    // Secure flag
                true     // HttpOnly flag
            );
        }

        // redirect base on ROLE
        redirectBasedOnRole(ROLE);
    } else {
        $_SESSION['old'] = $_POST;
        $_SESSION['error'] = errorDiv('Wrong Credentials');
        header("Location: ../../login.php");
    }
} else {
    http_response_code(401);
}

function errorDiv($errorTxt)
{
    return '<div class="text-center p-4 my-4 text-base text-red-800 rounded-lg bg-red-50" role="alert">
        <span class="font-medium">' . $errorTxt . '</span>
        </div>';
}

function redirectBasedOnRole($role)
{
    if ($role == 'admin' || $role == 'instructor') {
        header("Location: ../../index.php");
    } elseif ($role == 'cs' or $role == 'cs-admin') {
        header("Location: ../../lectures.php");
    }
}
