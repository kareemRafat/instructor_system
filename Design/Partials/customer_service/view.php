<?php
// Fetch all instructors from the database
require_once 'Database/connect.php';
$query = "SELECT 
                    i.id,
                    i.username,
                    i.is_active,
                    i.role,
                    MIN(b.name) AS branch_name,
                    GROUP_CONCAT(b.name SEPARATOR ', ') AS branches
                FROM instructors i
                JOIN branch_instructor bi ON i.id = bi.instructor_id
                JOIN branches b ON b.id = bi.branch_id
                WHERE role IN ('cs' , 'cs-admin')
                GROUP BY i.id, i.username, i.is_active, i.role
                ORDER BY i.is_active DESC, i.username ASC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
    <!-- Add Agent Button -->
    <button id="addInstructor" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
        class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Add Agent
    </button>
    <!-- Search Input -->
    <div class="relative w-full md:flex-1">
        <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
            </svg>
        </div> <input type="search" id="table-search"
            class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg 
            bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search for instructors">
    </div>
</div>

<!-- table -->
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr class="text-base">
                <th scope="col" class="w-40 px-6 py-3">
                    Username
                </th>
                <!-- add salary -->
                <?php if (hasRole('owner', 'admin')): ?>
                    <th scope="col" class="w-40 px-6 py-3">
                        <span>Salary</span>
                    </th>
                <?php endif; ?>
                <th scope="col" class="w-20 px-6 py-3">
                    Branch
                </th>
                <th scope="col" class="w-20 px-6 py-3">
                    Status
                </th>
                <th scope="col" class="w-20 px-6 py-3">
                    <span>Action</span>
                </th>

            </tr>
        </thead>
        <tbody class="font-semibold text-base">
            <?php
            foreach ($result as $row) :
            ?> <tr
                    class="odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50">
                    <th scope="row" class="w-40 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <?= ucwords($row['username']) ?>
                        <?php if ($row['role'] == 'cs-admin'): ?>
                            <i class="fa-solid fa-user-shield ml-3 text-rose-700"></i>
                        <?php elseif ($row['role'] == 'owner'): ?>
                            <i class="fa-solid fa-user-tie ml-3 text-teal-800 text-lg"></i>
                        <?php endif; ?>
                    </th>
                    <!-- add salary -->
                    <?php if (hasRole('owner', 'admin')): ?>
                        <td class="w-40 px-6 py-4 ">
                            <div class=" w-fit text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded text-sm py-1 px-2 text-center">
                                <a class="flex" id="add-salary" href="?action=add&id=<?= $row['id'] ?>">
                                    <i class="fa-solid fa-money-check-dollar mr-2 text-sm"></i>
                                    <span class="hidden md:block mr-1">Edit </span>
                                    salary
                                </a>
                            </div>
                        </td>
                    <?php endif; ?>
                    <td class="w-40 px-6 py-4 <?= branchIndicator($row['branch_name'])['textColor'] ?>">
                        <div class="flex flex-row justify-start items-center">
                            <svg class=" w-5 h-5 mr-1.5 md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                            </svg>
                            <?= ucwords($row['branch_name'] ?? 'Not Assigned') ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $row['is_active'] ? 'text-green-700 ring-green-600/20' : 'text-red-700 ring-red-600/20' ?>">
                            <?= $row['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 flex flex-col md:flex-row gap-1 w-fit">
                        <a href="?action=edit&instructor_id=<?= $row['id'] ?>" class="cursor-pointer border border-gray-300 py-0.5 px-2 rounded-lg font-medium <?= $row['role'] === 'owner' ? 'pointer-events-none text-gray-500 cursor-not-allowed' : 'text-blue-600' ?>  hover:underline inline-block text-center w-full md:w-fit ">
                            <i class="fa-solid fa-pen-to-square mr-1 hidden md:inline-block text-sm"></i>
                            Edit
                        </a>
                        <button <?= $row['role'] === ROLE || $row['role'] === 'owner' ? 'disabled' : '' ?>
                            class="w-full md:w-fit toggle-status-btn cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg <?= $row['is_active'] ? 'text-red-600' : 'text-green-600' ?> hover:underline disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed disabled:hover:no-underline"
                            data-agent-id="<?= $row['id'] ?>">
                            <?= $row['is_active'] ? '<i class="fa-solid fa-user-slash hidden md:inline-block mr-1"></i>' : '<i class="fa-solid fa-user mr-1"></i>' ?>
                            <?= $row['is_active'] ? 'Disable' : 'Enable' ?>
                        </button>
                        <button <?= $row['role'] === ROLE || $row['role'] == 'owner' ? 'disabled' : '' ?>
                            class="delete-cs-btn w-full md:w-fit cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg text-red-600 hover:underline disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed disabled:hover:no-underline"
                            data-agent-id="<?= $row['id'] ?>">
                            <i class="fa-solid fa-trash mr-1 hidden md:inline-block"></i>Delete
                        </button>
                    </td>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>