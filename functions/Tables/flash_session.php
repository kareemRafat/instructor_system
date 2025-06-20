<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit('You are not logged in!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // flash session for tables.php page
    if(isset($_POST['session_name'])) {
        $_SESSION['success'] = $_POST['session_name'];
    }
}