 <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";

    $query = "SELECT 
                        `groups`.id AS group_id,
                        IF(`groups`.name LIKE '%training%', 'training', `groups`.name) AS group_name,
                        `groups`.time AS group_time,
                        `groups`.day AS group_day,
                        instructors.username AS instructor_name,
                        branches.name AS branch_name,
                        DATE_FORMAT(`groups`.start_date, '%d-%m-%Y') AS formatted_date,
                        MONTHNAME(`groups`.start_date) AS month,
                        DATE_FORMAT(
                            DATE_ADD(
                                DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                                INTERVAL 2 WEEK
                            ),
                            '%d-%m-%Y'
                            ) AS group_end_date,
                        DATE_FORMAT(
                            DATE_ADD(
                                DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                                INTERVAL 2 WEEK
                            ),
                            '%M'
                            ) AS group_end_month
                FROM `groups` 
                JOIN instructors ON `groups`.instructor_id = instructors.id 
                JOIN branches ON `groups`.branch_id = branches.id
                WHERE `groups`.is_active = 1
                AND instructors.id = '{$_SESSION['user_id']}'
                ORDER BY `groups`.day ASC , `groups`.time ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $count = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

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
                    Track
                 </th>
                 <th scope="col" class="px-6 py-3">
                    Start Date
                 </th>
                 <th scope="col" class="px-6 py-3">
                    Expected End
                 </th>
                 <th scope="col" class="px-6 py-3">
                    Branch
                 </th>
             </tr>
         </thead>
         <tbody class="font-semibold text-base">
             <?php if ($count == 0) : ?> <tr class="bg-white">
                     <td colspan="7" class="px-6 py-3 text-gray-500 font-semibold">
                         No Groups found
                     </td>
                 </tr>
             <?php endif; ?>
             <?php
                $previousDay = null;
                foreach ($result as $row) :
                    $currentDay = $row['group_day'];
                    if ($previousDay !== $currentDay):
                ?>
                     <!-- Day Section Header -->
                     <tr>
                         <td colspan="7" class="bg-slate-200 text-zinc-800 text-base font-bold px-6 py-1.5 border-y border-zinc-300">
                             <?= strtoupper($currentDay) ?>
                         </td>
                     </tr>
                 <?php
                    endif;
                    $previousDay = $currentDay;
                    ?> <tr class="odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50">
                     <th scope="row" class="<?= dayBadgeColor($row['group_day']) ?> px-6 py-3 font-semibold tracking-wider whitespace-nowrap">
                         <?= ucwords($row['group_name']) ?>
                     </th>
                     <th scope="row" class="px-6 py-2 font-medium text-slate-800 whitespace-nowrap">
                         <i class="fa-solid fa-clock mr-1.5 text-slate-700 fa-spin" style="--fa-animation-duration: 5s"></i>
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
                     <td class="px-6 py-2 text-sky-600 capitalize">
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
                     <td class="px-6 py-2">
                         <span class="text-rose-700"><?= $row['month'] ?></span>
                         <br>
                         <?= $row['formatted_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-6 py-2">
                         <span class="text-purple-700"><?= $row['group_end_month'] ?></span>
                         <br>
                         <?= $row['group_end_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-6 py-2 <?= branchIndicator($row['branch_name'])['textColor'] ?>">
                         <div class="flex flex-row justify-start items-center">
                             <svg class=" w-5 h-5 mr-1.5  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                 <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                             </svg>
                             <?= ucwords($row['branch_name']) ?>
                         </div>
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
            'saturday' => 'text-orange-600',
            'sunday' => 'text-blue-700',
            'monday' => 'text-pink-700',
            'default' => 'text-zinc-700'
        ];

        return $colors[$dayName] ?? $colors['default'];
    }

    ?>