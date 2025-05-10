<?php
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>



<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Groups</h1>
    <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";
    $query = "SELECT 
                        groups.id,
                        groups.name AS group_name,
                        groups.time AS group_time,
                        instructors.username AS instructor_name,
                        branches.name AS branch_name,
                        DATE_FORMAT(groups.start_date, '%d-%m-%Y') AS formatted_date

                FROM groups 
                JOIN instructors ON groups.instructor_id = instructors.id 
                JOIN branches ON groups.branch_id = branches.id
                WHERE groups.is_active = 1
                ORDER BY groups.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
        <!-- Add Group Button -->
        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
            class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 
        font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add <span class="hidden md:inline-block">Group</span>
        </button>

        <!-- Country Dropdown -->
        <div class="w-full md:flex-1">
            <select id="branchSelect"
                class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choose a Branch</option>
            </select>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:flex-1">
            <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <input type="search" id="table-search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg 
            bg-gray-50 focus:ring-blue-500 focus:border-blue-500 
            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search for items">
        </div>
    </div>
    <!-- table -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Group
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Instructor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Branch
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Start Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span>Action</span>
                    </th>
                </tr>
            </thead>
            <tbody class="font-semibold">
                <?php
                foreach ($result as $row) :
                ?>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= ucwords($row['group_name']) ?>
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?=
                            $row['group_time'] == 2 || $row['group_time'] == 5
                                ? $row['group_time'] . " - Friday"
                                : $row['group_time'] ?>
                        </th>
                        <td class="px-6 py-4">
                            <?= ucwords($row['instructor_name']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= ucwords($row['branch_name']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $row['formatted_date'] ?? 'No date added' ?>
                        </td>
                        <td class="px-6 py-4">
                            <button data-group-id="<?= $row['id'] ?>" class="finish-group-btn font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-regular fa-circle-check mr-2"></i>Finish
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<footer class="bg-white rounded-lg shadow-sm dark:bg-gray-900">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://createivo.com/" class="hover:underline">Createivo™</a>. All Rights Reserved.</span>
    </div>
</footer>


<?php
// Main modal -->
include_once 'Design/Modals/insert_group.php';
?>

<script src="js/groups-main.js"></script>
</body>

</html>