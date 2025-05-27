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

    .group-name , .group-time{
        font-family: "Poetsen One", sans-serif;
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
    <div class="flex justify-between items-start md:items-center mb-4 flex-col md:flex-row">
        <h2 class="text-2xl font-semibold text-blue-700 mb-5">
            <i class="fa-solid fa-fire mr-1"></i>
            <a class="hover:underline" href="https://tinyurl.com/createivo-track" target="_blank">Track</a>
            <i class="fa-solid fa-circle-arrow-left text-xl ml-5 my-arrow"></i>
        </h2>
        <h4 class="flex items-center text-sm font-medium text-gray-900">
            <span class="flex items-center text-sm font-medium text-gray-900 me-3"><span class="flex w-2.5 h-2.5 bg-green-500 rounded-full me-1.5 shrink-0"></span>HTML - CSS</span>
            <span class="flex items-center text-sm font-medium text-gray-900 me-3"><span class="flex w-2.5 h-2.5 bg-cyan-500 rounded-full me-1.5 shrink-0"></span>JavaScript</span>
            <span class="flex items-center text-sm font-medium text-gray-900 me-3"><span class="flex w-2.5 h-2.5 bg-red-500 rounded-full me-1.5 shrink-0"></span>Php - MySQL</span>
        </h4>
    </div>

    <form id="lectureForm" class="mb-8" action="functions/Lectures/get_lectures.php">
        <!-- Select Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Branch Select -->
            <div> <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
                <select id="branch"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="" selected>Choose a branch</option>
                </select>
            </div>

            <!-- Track Select -->
            <div> <label for="tracks" class="block mb-2 text-sm font-medium text-gray-900">Track</label>
                <select id="tracks"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option class="font-semibold" value="" selected>Select Track</option>
                </select>
            </div>

            <!-- Time Select -->
            <div>
                <label for="group-time" class="block mb-2 text-sm font-medium text-gray-900">Group Time</label>
                <select id="group-time"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option class="font-semibold" value="" selected>Select Branch First</option>
                </select>
            </div>

            <!-- Instructor Select -->
            <div> <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900">Instructor</label>
                <select id="instructor" name="instructor_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option selected>Select Branch first</option>
                </select>
            </div>
        </div>
    </form>

    <div id="cards" class="pb-1">

        <div id="skeleton" role="status" class="max-w-sm animate-pulse mb-4">
            <div class="h-2.5 bg-gray-200 rounded-full w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full max-w-[360px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full max-w-[330px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full max-w-[300px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full max-w-[360px]"></div>
            <span class="sr-only">Loading...</span>
        </div>

        <p id="arrow-warning" class="hidden"><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>

        <ol id="lecturesCards" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-11">
        </ol>
    </div>

</div>
<script type="module" src="dist/lectures-main.js"></script>



</body>

</html>