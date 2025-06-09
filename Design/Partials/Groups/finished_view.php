 <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";

    $query = "SELECT 
                    g.id AS group_id,
                    g.name AS group_name,
                    g.time AS group_time,
                    g.day AS group_day,
                    g.has_bonus AS has_bonus,
                    i.username AS instructor_name,
                    b.name AS branch_name,
                    DATE_FORMAT(g.start_date, '%d-%m-%Y') AS formatted_date,
                    DATE_FORMAT(g.start_date, '%M') AS month,
                    DATE_FORMAT(bonus.finish_date, '%d-%m-%Y') AS group_end_date,
                    DATE_FORMAT(bonus.finish_date, '%M') AS group_end_month
                FROM `groups` g
                JOIN instructors i ON g.instructor_id = i.id
                JOIN branches b ON g.branch_id = b.id
                JOIN bonus ON bonus.group_id = g.id
                WHERE g.is_active = 0
                AND (:branch IS NULL OR g.branch_id = :branch)
                ORDER BY bonus.finish_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':search' => isset($_GET['search']) ? $_GET['search'] : null,
        ':branch' => isset($_GET['branch']) ? $_GET['branch'] : null
    ]);
    $count = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

 <div class="flex flex-col md:flex-row md:items-center mb-7 gap-4">
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
 </div>


 <div class="py-5 flex flex-col md:flex-row justify-between items-center gap-4">

     <div class="w-full">
        <h1 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900">Finished <span class="text-red-600">Groups</span>  </h1>
    </div>

     <div class="flex flex-col md:flex-row justify-end items-center gap-4 w-full">
         <a class="w-full md:w-fit px-4 py-1.5 mb-0 bg-green-600 text-base rounded-md tracking-wider font-medium capitalize text-center text-white" href="groups.php">
             Groups
         </a>
         <a href="groups.php?action=finished" class="w-full md:w-fit px-4 py-1.5 mb-3 md:mb-0 bg-blue-600 text-base rounded-md tracking-wider font-medium capitalize text-center text-white inline-flex items-center hover:underline justify-center">
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
                     Verified End Date
                 </th>
                 <th scope="col" class="px-4 py-3">
                     <span>Has Bonus</span>
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
                         <?= ucwords($row['branch_name']) ?>
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
                     <td class="px-4 py-3.5">
                         <?=
                            $row['has_bonus']
                                ? '<i class="fa-solid fa-square-check text-green-600 mr-2"></i> <span class="text-green-600">Has Bonus'
                                : '<i class="fa-solid fa-square-xmark text-red-600 mr-2"></i> No Bonus Granted' ?>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
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