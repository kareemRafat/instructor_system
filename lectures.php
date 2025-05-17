<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>

<style>
    .my-arrow {
        transition: 1s;
        animation: moving 1s alternate 8;
    }

    ol li {
        opacity: 0;
        animation: fading 0.4s forwards;
    }

    @keyframes moving {
        to {
            translate: -20px;
        }
    }

    @keyframes fading {
        to {
            opacity: 1;
        }
    }
</style>

<script>
    // track animation
    document.addEventListener('DOMContentLoaded', function() {
        let arrow = document.querySelector('.my-arrow');
        arrow.onanimationend = function() {
            this.style.scale = 2;
            setTimeout(() => {
                arrow.style.opacity = 0
            }, 100);
        }
    })
</script>


<div class="max-w-7xl mx-auto pt-6 px-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Instructor Lectures</h1>

    <h2 class="text-2xl font-semibold text-blue-700 mb-5">
        <i class="fa-solid fa-fire mr-1"></i>
        <a class="hover:underline" href="https://tinyurl.com/createivo-track" target="_blank">Track</a>
        <i class="fa-solid fa-circle-arrow-left text-xl ml-5 my-arrow"></i>
    </h2>

    <form id="lectureForm" class="mb-8" action="functions/Lectures/get_lectures.php">
        <!-- Select Row -->
        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <!-- Branch Select -->
            <div class="md:w-1/2">
                <label for="branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch</label>
                <select id="branch"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" selected>Choose a branch</option>
                </select>
            </div>

            <!-- Track Select -->
            <div class="md:w-1/2">
                <label for="tracks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Track</label>
                <select id="tracks"
                    class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option class="font-semibold" value="" selected>Select Track</option>
                </select>
            </div>

            <!-- Time Select -->
            <div class="md:w-1/2">
                <label for="group-time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Group Time</label>
                <select id="group-time"
                    class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option class="font-semibold" value="" selected>Choose a Time</option>
                    <option class="font-semibold" value="10">10</option>
                    <option class="font-semibold" value="12.30">12.30</option>
                    <option class="font-semibold" value="3">3</option>
                    <option class="font-semibold" value="6">6</option>
                    <option class="font-semibold" value="2">2 [Friday]</option>
                    <option class="font-semibold" value="5">5 [Friday]</option>
                </select>
            </div>

            <!-- Instructor Select -->
            <div class="md:w-1/2">
                <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instructor</label>
                <select id="instructor" name="instructor_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Select branch first</option>
                </select>
            </div>
        </div>
    </form>

    <ol id="lecturesCards" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <p><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>
    </ol>

</div>
<script src="js/lectures-main.js"></script>

</body>

</html>