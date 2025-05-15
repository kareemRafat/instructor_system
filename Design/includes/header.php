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
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lecture Schedule</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
        <script src="https://cdn.tailwindcss.com"></script>
    <?php endif; ?>


    <!-- Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- notyf -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <style>
        /* ovveride toast width */
        .notyf__toast {
            max-width: fit-content;
        }

        .notyf__ripple {
            width: 500px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">