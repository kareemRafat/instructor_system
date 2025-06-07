<?php
// Fetch all instructors from the database
$query = "
        SELECT 
            i.id,
            i.username,
            i.is_active,
            i.role,
            MIN(b.name) AS branch_name,
            GROUP_CONCAT(b.name SEPARATOR ', ') AS branches
        FROM instructors i
        JOIN branch_instructor bi ON i.id = bi.instructor_id
        JOIN branches b ON b.id = bi.branch_id
        WHERE i.role IN ('instructor', 'admin')
        GROUP BY i.id, i.username, i.is_active, i.role
        ORDER BY i.is_active DESC, i.username ASC
    ";

$stmt = $pdo->prepare($query);
$stmt->execute();
$count = $stmt->rowCount();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
    <!-- Add Instructor Button -->
    <button id="addInstructor" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
        class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 
        font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Add Instructor
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
        </div>
        <input type="search" id="table-search"
            class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search for instructors">
    </div>
</div>

<!-- table -->
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
            <tr class="text-base">
                <th scope="col" class="px-6 py-3">
                    Username
                </th>
                <th scope="col" class="px-6 py-3" >
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
            <?php if ($count == 0) : ?>
                <tr class="bg-white">
                    <td colspan="4" class="px-6 py-4 text-gray-500 font-semibold">
                        No instructors found
                    </td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($result as $row) :
            ?>
                <tr
                    class="odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <?= ucwords($row['username']) ?>
                    </th>
                    <td class="pr-6 py-4 md:flex  md:flex-row md:justify-start">
                        <?php
                        if (isset($row['branches'])) {
                            $branchArray = explode(', ', $row['branches']);
                            foreach ($branchArray as $index => $br) {
                                $color =  count($branchArray) > 1 ? 'text-fuchsia-600' : 'text-indigo-500';
                                echo '<div class="flex flex-row justify-start items-center"><svg class=" w-5 h-5 ' . $color . '  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd"/>
                                        </svg>';
                                echo "<span class='m-1.5'>{$br}</span></div>";  
                            }
                        } else {
                            echo 'Not Assigned';
                        }
                        ?>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="isactive-span inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset <?= $row['is_active'] ? 'text-green-700 bg-green-50 ring-green-600/20' : 'text-red-700 bg-red-50 ring-red-600/20' ?>">
                            <?= $row['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <a href="?action=edit&instructor_id=<?= $row['id'] ?>" class="cursor-pointer border border-gray-300 py-0.5 px-2 rounded-lg font-medium text-blue-600 hover:underline mr-2 inline-block mb-2 text-center"><i class="fa-solid fa-pen-to-square mr-2"></i>
                            Edit
                        </a>
                        <button type="button"
                            class="toggle-status-btn cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg <?= $row['is_active'] ? 'text-red-600' : 'text-green-600' ?> hover:underline"
                            data-instructor-id="<?= $row['id'] ?>">
                            <?= $row['is_active'] ? '<i class="fa-solid fa-user-slash mr-1"></i>' : '<i class="fa-solid fa-user mr-1"></i>' ?>
                            <?= $row['is_active'] ? 'Disable' : 'Enable' ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>