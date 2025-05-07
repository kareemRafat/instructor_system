<?php 

session_start();


if(!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

session_unset();

session_destroy();

// Expire the cookie
setcookie(
    'remember_token',
    $token,
    time() - 3600, 
    '/',
    '',
    true,    // Secure flag
    true     // HttpOnly flag
);

header("Location: ../../login.php");