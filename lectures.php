
    <?php 
        include_once 'Design/includes/header.php';
        include_once 'Design/includes/navbar.php';
    ?>


    <div class="max-w-7xl mx-auto pt-6 px-6">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Instructor Lectures</h1>

        <form id="lectureForm" class="mb-8" action="functions/Lectures/get_lectures.php">
            <!-- Select Row -->
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <!-- Branch Select -->
                <div class="w-full">
                    <label for="branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch</label>
                    <select id="branch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Choose a branch</option>
                    </select>
                </div>

                <!-- Instructor Select -->
                <div class="w-full">
                    <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instructor</label>
                    <select id="instructor" name="instructor_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Select branch first</option>
                    </select>
                </div>
            </div>
        </form>

        <ol id="lecturesCards" class="flex flex-col md:flex-row md:flex-wrap gap-4 px-4 max-w-7xl mx-auto">
            <p><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>
        </ol>

    </div>
    <script src="js/lectures-main.js"></script>
</body>

</html>