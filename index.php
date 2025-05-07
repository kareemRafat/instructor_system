<?php
session_start();
require_once 'Database/connect.php';
require_once 'functions/Auth/auth_helper.php';

if (!isset($_SESSION['user_id'])) {
    // Check for remember-me token if session is not set

    if (!validateRememberMeToken($pdo)) {
        header("Location: login.php");
        exit();
    }

    header("Location: login.php");
}

$errors = $_SESSION['errors'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <style>
        .logout {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        footer {
            position: absolute;
            bottom: 0;
            right: 0;
        }
    </style>

</head>

<body class="bg-gray-100">
    <!-- navbar -->
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://createivo.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Createivo</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

            <?php 
            if ($result['role'] === 'admin') {           
            ?>
                <a href="lectures.php" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Lectures</a>
            <?php } ?>
                <a href="functions/Auth/logout.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Logout</a>
            </div>
        </div>
    </nav>


    <!-- card -->
    <div class="flex items-center justify-center my-8 h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">User Groups</h2>
            <form action="functions/Lectures/insert_lecture.php" method="POST">
                <input type="hidden" name="date" id="currentDate">
                <script>
                    const now = new Date();
                    const formatted = now.toLocaleString('sv-SE').replace('T', ' '); // 'YYYY-MM-DD HH:mm:ss'
                    document.getElementById('currentDate').value = formatted;
                </script>
                <div class="mb-4">
                    <label for="group" class="block text-sm font-medium text-gray-600">Group</label>
                    <select id="group" name="group" class="form-select block w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">Select Group</option>
                    </select>
                    <?php
                    if (isset($errors['group'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['group'] .
                            '</div>';
                    }
                    ?>
                </div>
                <div class="mb-7">
                    <label for="track" class="block text-sm font-medium text-gray-600">Track</label>
                    <select id="track" name="track" class="form-select block w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-400 focus:border-blue-400">
                        <option value="1">HTML</option>
                        <option value="2">CSS</option>
                        <option value="3">JavaScript</option>
                        <option value="4">PHP</option>
                        <option value="5">MySQL</option>
                        <option value="6">Project</option>
                    </select>
                    <?php
                    if (isset($errors['track'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['group'] .
                            '</div>';
                    }
                    ?>
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <input name="comment" type="text" id="floating_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="" />
                        <label for="floating_outlined" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Comment</label>
                    </div>
                    <?php
                    if (isset($errors['comment'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['comment'] .
                            '</div>';
                    }
                    ?>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Submit</button>
            </form>
        </div>
    </div>

    <?php
    unset($_SESSION['errors']);
    ?>

    <script src="js/main.js"></script>

</body>

</html>