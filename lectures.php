<?php

session_start();

require_once "Database/connect.php";
require_once 'functions/Auth/auth_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($result['role'] !== 'admin') {
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
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen p-6">
    <!-- navbar -->
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://createivo.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Createivo</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="index.php" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 mr-5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Add page</a>

                <a href="functions/Auth/logout.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Instructor Lectures</h1>

        <form id="lectureForm" class="mb-8" action="functions/Lectures/get_lectures.php">
            <!-- Select Row -->
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <!-- Branch Select -->
                <div class="w-full md:w-1/4">
                    <label for="branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch</label>
                    <select id="branch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Choose a branch</option>
                    </select>
                </div>

                <!-- Instructor Select -->
                <div class="w-full md:w-1/4">
                    <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instructor</label>
                    <select id="instructor" name="instructor_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Select branch first</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="w-full md:w-1/6 flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md transition-colors">
                        Show Lectures
                    </button>
                </div>
            </div>
        </form>

        <ol id="lecturesCards" class="items-center sm:flex">
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pe-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Flowbite Library v1.0.0</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">Get started with dozens of web components and interactive elements.</p>
                </div>
            </li>
        </ol>

    </div>
    <script src="js/lectures-main.js"></script>
</body>

</html>