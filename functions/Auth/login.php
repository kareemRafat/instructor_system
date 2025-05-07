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

    if ($instructor && password_verify($inputPassword, $instructor['password'])) {
        $_SESSION['user_id'] = $instructor['id'];

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

        header("Location: ../../index.php");
    } else {
        $_SESSION['error'] = errorDiv();
        header("Location: ../../login.php");
    }
} else {
    http_response_code(401);
}

function errorDiv()
{
    return '<div class="text-center p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">Wrong Credentials</span>
        </div>';
}
