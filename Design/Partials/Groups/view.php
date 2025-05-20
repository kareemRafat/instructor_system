 <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";
    $query = "SELECT 
                        `groups`.id AS group_id,
                        `groups`.name AS group_name,
                        `groups`.time AS group_time,
                        instructors.username AS instructor_name,
                        branches.name AS branch_name,
                        DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
                        DATE_FORMAT(`groups`.start_date, '%M') AS month
                FROM `groups` 
                JOIN instructors ON `groups`.instructor_id = instructors.id 
                JOIN branches ON `groups`.branch_id = branches.id
                WHERE `groups`.is_active = 1
                ORDER BY `groups`.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $count = $stmt->rowCount();
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
             <option selected>Select a Branch</option>
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
             placeholder="Search for Groups">
     </div>
 </div>
 <!-- table -->
 <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-10">
     <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
         <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
             <tr class="text-base">
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
         <tbody class="font-semibold text-base">
             <?php if ($count == 0) : ?>
                 <tr class="bg-white dark:bg-gray-800 dark:border-gray-700">
                     <td colspan="6" class="px-6 py-4 text-gray-500 dark:text-gray-400 font-semibold">
                         No Groups found
                     </td>
                 </tr>
             <?php endif; ?>
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
                        <?= $row['month'] ?>
                        <br>
                         <?= $row['formatted_date'] ?? 'No date added' ?> 
                     </td>
                     <td class="px-6 py-4">
                         <a href="?action=edit&group_id=<?= $row['group_id'] ?>" class="cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-blue-600 hover:underline mr-2"><i class="fa-solid fa-pen-to-square mr-2 inline-block mb-2"></i>
                             Edit
                         </a>
                         <button data-group-id="<?= $row['group_id'] ?>" class="finish-group-btn cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-regular fa-circle-check mr-2"></i>Finish
                         </button>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>