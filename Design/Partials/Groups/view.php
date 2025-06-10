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
                    g.id AS group_id,
                    g.name AS group_name,
                    g.time AS group_time,
                    g.day AS group_day,
                    i.username AS instructor_name,
                    b.name AS branch_name,
                    DATE_FORMAT(g.start_date, '%d-%m-%Y') AS formatted_date,
                    DATE_FORMAT(g.start_date, '%M') AS month,
                    DATE_FORMAT(
                        DATE_ADD(DATE_ADD(g.start_date, INTERVAL 5 MONTH), INTERVAL 2 WEEK),
                        '%d, %m-%Y'
                    ) AS group_end_date,
                    DATE_FORMAT(
                        DATE_ADD(DATE_ADD(g.start_date, INTERVAL 5 MONTH), INTERVAL 2 WEEK),
                        '%M'
                    ) AS group_end_month
                FROM `groups` g
                JOIN instructors i ON g.instructor_id = i.id
                JOIN branches b ON g.branch_id = b.id
                WHERE g.is_active = 1
                AND (:branch IS NULL OR g.branch_id = :branch)
                ORDER BY g.start_date DESC
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
             <option value="" selected>Choose Instructor</option>
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
 <div class="flex flex-col md:flex-row justify-between items-center gap-4">
     <div class="w-full">
         <h1 class="text-2xl font-extrabold leading-none text-gray-900 ">Active 
            <span class="text-blue-600">Groups - </span>
            <span class="total-inst-count"></span>
        </h1>
     </div>
     

     <div class="w-full my-4 flex flex-col md:flex-row justify-end gap-4">
         <?php if (ROLE == 'admin' || ROLE == 'cs-admin') : ?>
             <a class="px-4 py-1.5 mb-3 md:mb-0 bg-rose-600 text-base rounded-md tracking-wider font-medium capitalize text-center text-white" href="?action=finished">
                 Finished Groups
             </a>
         <?php endif; ?>
         <a href="groups.php" class="px-4 py-1.5 mb-3 md:mb-0 bg-blue-600 text-base rounded-md tracking-wider font-medium capitalize text-center text-white inline-flex items-center hover:underline justify-center">
             <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
             </svg>
             Reset
         </a>
     </div>
 </div>

 <!-- table -->
 <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-10">
     <table class="w-full text-sm text-left rtl:text-right text-gray-500">
         <thead class="text-xs text-gray-700 uppercase bg-gray-200">
             <tr class="text-base">
                 <th scope="col" class="px-4 py-3 w-10">
                     Group
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Time
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Day
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Track
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Instructor
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Branch
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Start Date
                 </th>
                 <th scope="col" class="px-4 py-3">
                     Expected End
                 </th>
                 <th scope="col" class="px-4 py-3">
                     <span>Action</span>
                 </th>
             </tr>
         </thead>
         <tbody class="font-semibold text-base">
             <?php if ($count == 0) : ?> <tr class="bg-white">
                     <td colspan="7" class="px-4 py-3.5 text-gray-500 font-semibold">
                         No Groups found
                     </td>
                 </tr>
             <?php endif; ?>
             <?php
                foreach ($result as $row) :
                ?> <tr class="odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50">
                     <th scope="row" class="px-4 py-3.5 w-10 font-medium text-gray-900 whitespace-nowrap">
                         <?= ucwords($row['group_name']) ?>
                     </th>
                     <th scope="row" class="px-4 py-3.5 font-medium text-pink-900 whitespace-nowrap">
                         <i class="fa-solid fa-clock mr-1.5"></i>
                         <?php
                            if ($row['group_time'] == 2 || $row['group_time'] == 5) {
                                echo $row['group_time'] . " - Friday";
                            } elseif ($row['group_time'] == 6.10 || $row['group_time'] == 8) {
                                echo "Online " . number_format((int)$row['group_time']);
                            } else {
                                echo $row['group_time'];
                            }
                            ?>
                     </th>
                     <th scope="row" class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">
                         <span class="<?= dayBadgeColor($row['group_day']) ?> text-sm font-medium me-2 px-2.5 py-1.5 rounded-md"><?= $row['group_day'] ?></span>
                     </th>
                     <td class="px-4 py-3.5 text-sky-600 capitalize">
                         <?php
                            $groupId = $row['group_id'];
                            $getTrack = "SELECT 
                                            *
                                            FROM lectures AS l 
                                            JOIN tracks AS t ON t.id =  l.track_id
                                            WHERE group_id = :group ORDER BY date DESC LIMIT 1";
                            $stmt = $pdo->prepare($getTrack);
                            $stmt->execute([':group' => $groupId]);
                            echo $stmt->fetch(PDO::FETCH_ASSOC)['name'] ?? 'Not Updated';
                            ?>
                     </td>
                     <td class="px-4 py-3.5">
                         <span class="w-2 h-2 <?= branchIndicator($row['branch_name'])['bgColor'] ?> inline-block mr-2"></span>
                         <?= ucwords($row['instructor_name']) ?>
                     </td>
                     <td class="px-4 py-3.5 <?= branchIndicator($row['branch_name'])['textColor'] ?>">
                         <div class="flex flex-row justify-start items-center">
                             <svg class=" w-5 h-5 mr-1.5  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                 <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                             </svg>
                             <?= ucwords($row['branch_name']) ?>
                         </div>
                     </td>
                     <td class="px-4 py-3.5">
                         <span class="text-rose-700"><?= $row['month'] ?></span>
                         <br>
                         <?= $row['formatted_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-4 py-3.5">
                         <span class="text-purple-700"><?= $row['group_end_month'] ?></span>
                         <br>
                         <?= $row['group_end_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-4 py-2 grid grid-cols-1 gap-1">
                         <a href="?action=edit&group_id=<?= $row['group_id'] ?>" class="cursor-pointer text-center border border-gray-300 py-1 px-2 rounded-lg font-medium text-blue-600 hover:underline"><i class="fa-solid fa-pen-to-square hidden md:inline-block"></i>
                             <span>Edit</span>
                         </a>
                         <a href="?action=finish_group&group_id=<?= $row['group_id'] ?>" class=" cursor-pointer text-center border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-600 hover:underline">
                             <i class="fa-regular fa-circle-check mr-1.5 hidden md:inline-block"></i>
                             <span>Finish</span>
                         </a>
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