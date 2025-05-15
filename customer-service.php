<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>

<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Customer Service</h1>
    <?php
    // Fetch all instructors from the database
    require_once 'Database/connect.php';
    $query = "SELECT 
                    instructors.id,
                    instructors.username,
                    instructors.is_active,
                    instructors.role AS instructor_role,
                    branches.name as branch_name
                FROM instructors 
                LEFT JOIN branches ON instructors.branch_id = branches.id
                WHERE role IN ('cs' , 'cs-admin')
                ORDER BY instructors.is_active DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
        <!-- Add Agent Button -->
        <button id="addInstructor" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
            class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 
        font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add <span class="hidden md:inline-block">Agent</span>
        </button>

        <!-- Search Input -->
        <div class="relative w-full md:flex-1">
            <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <input type="search" id="table-search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg 
            bg-gray-50 focus:ring-blue-500 focus:border-blue-500 
            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search for instructors">
        </div>
    </div>

    <!-- table -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr class="text-base">
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Branch
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span>Action</span>
                    </th>
                </tr>
            </thead>
            <tbody class="font-semibold text-base">
                <?php
                foreach ($result as $row) :
                ?>
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= ucwords($row['username']) ?>
                            <?php if ($row['instructor_role'] == 'cs-admin'): ?>
                                <i class="fa-solid fa-user-shield ml-3 text-green-700"></i>
                            <?php endif; ?>
                        </th>
                        <td class="px-6 py-4">
                            <?= ucwords($row['branch_name'] ?? 'Not Assigned') ?>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $row['is_active'] ? 'text-green-700 bg-green-50 ring-green-600/20' : 'text-red-700 bg-red-50 ring-red-600/20' ?>">
                                <?= $row['is_active'] ? 'Active' : 'Disabled' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button
                                class="toggle-status-btn text-sm border py-1 px-2 rounded-lg <?= $row['is_active'] ? 'text-red-600' : 'text-green-600' ?> hover:underline"
                                data-agent-id="<?= $row['id'] ?>">
                                <?= $row['is_active'] ? '<i class="fa-solid fa-user-slash mr-1"></i>' : '<i class="fa-solid fa-user mr-1"></i>' ?>
                                <?= $row['is_active'] ? 'Disable' : 'Enable' ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'Design/Modals/insert_customer_service.php'; ?>

<!-- Add this before closing body tag -->
<script src="js/cs-main.js"></script>

<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>