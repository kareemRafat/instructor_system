<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>
    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
                Instructor Schedule
            </h1>

            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="w-full bg-white border-collapse">
                    <!-- Table Header -->
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="border border-gray-300 p-4 text-left font-semibold w-7">
                                Instructor
                            </th>
                            <th colspan="4" class="border border-gray-300 p-4 text-center font-semibold">
                                Saturday
                            </th>
                            <th colspan="4" class="border border-gray-300 p-4 text-center font-semibold">
                                Sunday
                            </th>
                            <th colspan="4" class="border border-gray-300 p-4 text-center font-semibold">
                                Monday
                            </th>
                        </tr>
                        <!-- Time slots sub-header -->
                        <tr class="bg-blue-500 text-white">
                            <th class="border border-gray-300 p-2"></th>
                            <!-- Saturday slots -->
                            <th class="border border-gray-300 p-2 text-sm font-medium">10</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">12.30</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">3</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">6</th>
                            <!-- Sunday slots -->
                            <th class="border border-gray-300 p-2 text-sm font-medium">10</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">12.30</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">3</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">6</th>
                            <!-- Monday slots -->
                            <th class="border border-gray-300 p-2 text-sm font-medium">10</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">12.30</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">3</th>
                            <th class="border border-gray-300 p-2 text-sm font-medium">6</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        <!-- Kareem -->
                        <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-200">
                            <td class="border border-gray-300 p-4 font-semibold text-blue-700 bg-blue-100">
                                Kareem
                            </td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                        </tr>
                        <!-- Nora -->
                        <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-200">
                            <td class="border border-gray-300 p-4 font-semibold text-blue-700 bg-blue-100">
                                Nora
                            </td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                            <td class="border border-gray-300 p-4 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">Flower</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</body>

</html>