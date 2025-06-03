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
                        DATE_FORMAT(`groups`.start_date, '%M') AS month,
                        DATE_FORMAT(
                            DATE_ADD(
                                DATE_ADD(`groups`.start_date, INTERVAL 5 MONTH),
                                INTERVAL 2 WEEK
                            ),
                            '%d, %m-%Y'
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
                ORDER BY `groups`.start_date DESC
                LIMIT $groupPerPage OFFSET $pageNum"; // Adjust LIMIT and OFFSET as needed for pagination

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
                     Day
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Start Date
                 </th>
                 <th scope="col" class="px-6 py-3">
                    End Date
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
                     <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                         <span class="<?= dayBadgeColor($row['group_day']) ?> text-sm font-medium me-2 px-2.5 py-1.5 rounded-md"><?= $row['group_day'] ?></span>
                     </th>
                     <td class="px-6 py-4">
                         <span class="text-rose-700"><?= $row['month'] ?></span>
                         <br>
                         <?= $row['formatted_date'] ?? 'No date added' ?>
                     </td>
                     <td class="px-6 py-4">
                         <span class="text-purple-700"><?= $row['group_end_month'] ?></span>
                         <br>
                         <?= $row['group_end_date'] ?? 'No date added' ?>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>



 <?php
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