 <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";
    $groupPerPage = 10; // Number of items per page
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $pageNum = ($_GET['page'] - 1) * $groupPerPage; // Assuming 10 items per page
    } else {
        $pageNum = 0; // Default to the first page
    }

    $query = "SELECT 
                        `groups`.id AS group_id,
                        `groups`.name AS group_name,
                        `groups`.time AS group_time,
                        `groups`.day AS group_day,
                        instructors.username AS instructor_name,
                        branches.name AS branch_name,
                        DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
                        DATE_FORMAT(`groups`.start_date, '%M') AS month
                FROM `groups` 
                JOIN instructors ON `groups`.instructor_id = instructors.id 
                JOIN branches ON `groups`.branch_id = branches.id
                WHERE `groups`.is_active = 1
                -- AND (:search IS NULL OR `groups`.name LIKE CONCAT('%', :search, '%'))
                AND (:branch IS NULL OR branches.id = :branch)
                ORDER BY `groups`.start_date DESC
                LIMIT $groupPerPage OFFSET $pageNum"; // Adjust LIMIT and OFFSET as needed for pagination

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':search' => isset($_GET['search']) ? $_GET['search'] : null,
        ':branch' => isset($_GET['branch']) ? $_GET['branch'] : null
    ]);
    $count = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

 <!-- filter row -->
 <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
     <!-- Add Group Button -->
     <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
         class="self-end w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 
        font-medium rounded-lg text-sm px-5 py-2.5 text-center">
         Add Group
     </button>

     <!-- Country Dropdown -->
     <div class="w-full md:flex-1">
         <label for="branchSelect" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
         <select id="branchSelect"
             class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
             <option selected>Select a Branch</option>
         </select>
     </div>

     <div class="w-full md:flex-1">
         <label for="instructor-select" class="block mb-2 text-sm font-medium text-gray-900">Instructor</label>
         <select id="instructor-select"
             class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
             <option selected>Choose Instructor</option>
         </select>
     </div>

     <!-- Search Input -->
     <div class="w-full md:flex-1">
         <label for="table-search" class="block mb-2 text-sm font-medium text-gray-900">Search Group</label>
         <div class="relative">
             <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                 <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                 </svg>
             </div>
             <input type="search" id="table-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg 
            bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Search for Groups">
         </div>
     </div>
 </div>

 <!-- total count -->
 <div class="my-4 p-2.5 bg-purple-100 border border-purple-300 rounded-md tracking-wider font-medium capitalize text-center ">
     <div class="table-header-count hidden">

     </div>
     <div class="total-group">
         <span class="total-txt text-lg">Total Groups : </span>
         <span class="total-inst-count tracking-widest text-blue-800 text-lg">
             <!-- spinner -->
             <div role="status" class="inline-block">
                 <svg aria-hidden="true" class="inline w-4 h-4 text-gray-400 animate-spin fill-blue-800" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                     <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                 </svg>
                 <span class="sr-only">Loading...</span>
             </div>
         </span>
     </div>
 </div>

 <!-- table -->
 <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-10">
     <table class="w-full text-sm text-left rtl:text-right text-gray-500">
         <thead class="text-xs text-gray-700 uppercase bg-gray-200">
             <tr class="text-base">
                 <th scope="col" class="px-6 py-3">
                     Group
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Time
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Day
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
             <?php if ($count == 0) : ?> <tr class="bg-white">
                     <td colspan="7" class="px-6 py-4 text-gray-500 font-semibold">
                         No Groups found
                     </td>
                 </tr>
             <?php endif; ?>
             <?php
                foreach ($result as $row) :
                ?> <tr class="odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50">
                     <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                         <?= ucwords($row['group_name']) ?>
                     </th>
                     <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                         <?=
                            $row['group_time'] == 2 || $row['group_time'] == 5
                                ? $row['group_time'] . " - Friday"
                                : $row['group_time'] ?>
                     </th>
                     <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                         <span class="<?= dayBadgeColor($row['group_day']) ?> text-sm font-medium me-2 px-2.5 py-1.5 rounded-md"><?= $row['group_day'] ?></span>
                     </th>
                     <td class="px-6 py-4">
                         <span class="w-2 h-2 <?= branchIndicator($row['branch_name'])['bgColor'] ?> inline-block mr-2"></span>
                         <?= ucwords($row['instructor_name']) ?>
                     </td>
                     <td class="px-6 py-4 <?= branchIndicator($row['branch_name'])['textColor'] ?>">
                         <?= ucwords($row['branch_name']) ?>
                     </td>
                     <td class="px-6 py-4">
                         <?= $row['month'] ?>
                         <br>
                         <?= $row['formatted_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-6 py-4">
                         <a href="?action=edit&group_id=<?= $row['group_id'] ?>" class="cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-blue-600 hover:underline mr-2 inline-block mb-2 text-center"><i class="fa-solid fa-pen-to-square mr-2"></i>
                             Edit
                         </a>
                         <button data-group-id="<?= $row['group_id'] ?>" class="finish-group-btn cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-600 hover:underline"><i class="fa-regular fa-circle-check mr-2"></i>Finish
                         </button>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
     <?php include "pagination.php"; ?>
 </div>



 <?php
    // branch indicator color
    function branchIndicator($branch_name)
    {
        $branch_name = strtolower($branch_name);
        $bgColors = [
            'tanta' => 'bg-teal-600',
            'mansoura' => 'bg-blue-600',
            'zagazig' => 'bg-purple-500',
            'default' => 'bg-orange-600'
        ];

        $textColors = [
            'tanta' => 'text-teal-600',
            'mansoura' => 'text-blue-700',
            'zagazig' => 'text-purple-700',
            'default' => 'text-orange-700'
        ];

        $bgClass = $bgColors[$branch_name] ?? $bgColors['default'];
        $textClass = $textColors[$branch_name] ?? $textColors['default'];

        return [
            'bgColor' => $bgClass,
            'textColor' => $textClass
        ];
    }

    function dayBadgeColor($dayName)
    {
        $dayName = strtolower($dayName);

        $colors = [
            'saturday' => 'bg-orange-100 text-orange-600 border border-orange-300',
            'sunday' => 'bg-blue-100 text-blue-700 border border-blue-300',
            'monday' => 'bg-pink-100 text-pink-700 border border-pink-300',
            'default' => 'bg-zinc-100 text-zinc-700 border border-zinc-300'
        ];

        return $colors[$dayName] ?? $colors['default'];
    }

    ?>