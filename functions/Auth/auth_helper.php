<?php


$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM instructors WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);


function validateRememberMeToken($pdo)
{
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);

        $stmt = $pdo->prepare('SELECT instructor_id, expiry FROM remember_tokens WHERE token = :token');
        $stmt->execute(['token' => $hashedToken]);
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tokenData && strtotime($tokenData['expiry']) > time()) {
            // Token is valid and not expired
            $_SESSION['user_id'] = $tokenData['user_id'];
            return true;
        }

        // Remove expired or invalid token
        $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE token = :token');
        $stmt->execute(['token' => $hashedToken]);
        setcookie('remember_token', '', time() - 3600, '/');
    }
    return false;
}
