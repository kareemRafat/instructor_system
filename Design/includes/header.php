<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if (isset($_SESSION['user_id'])) {
    // bootstrap Helper function
    checkAccess(ROLE);
} else {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en" class="light" style="height: -webkit-fill-available !important;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lecture Schedule</title>
    <meta name="branch" content="<?= BRANCH ?>" />
    <meta name="role" content="<?= ROLE ?>" />
    <link rel="shortcut icon" href="icon/sah.png" type="image/x-icon">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <!-- font awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- tailwind cli output -->
    <link rel="stylesheet" href="css/output.css">

    <!-- Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- notyf -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap');
    </style>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen" style="height: -webkit-fill-available !important;">