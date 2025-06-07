 <?php
    // Fetch all groups from the database
    require_once "Database/connect.php";

    $query = "SELECT 
                    g.id AS group_id,
                    g.name AS group_name,
                    g.time AS group_time,
                    g.day AS group_day,
                    i.username AS instructor_name,
                    b.name AS branch_name,
                    DATE_FORMAT(g.start_date, '%d-%m-%Y') AS formatted_date,
                    DATE_FORMAT(g.start_date, '%M') AS month,
                    DATE_FORMAT(g.finish_date, '%d-%m-%Y') AS group_end_date,
                    DATE_FORMAT(g.finish_date, '%M') AS group_end_month
                FROM `groups` g
                JOIN instructors i ON g.instructor_id = i.id
                JOIN branches b ON g.branch_id = b.id
                WHERE g.is_active = 0
                AND (:branch IS NULL OR g.branch_id = :branch)
                ORDER BY g.start_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':search' => isset($_GET['search']) ? $_GET['search'] : null,
        ':branch' => isset($_GET['branch']) ? $_GET['branch'] : null
    ]);
    $count = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
<div class="py-5 flex justify-between items-center">

    <div class="bg-zinc-100 border text-zinc-800 w-3/5 md:w-1/4 py-2.5 p-4 font-semibold text-base capitalize rounded-lg tracking-wider">
        Finished Groups
    </div>

    <a href="groups.php" class="text-white inline-flex items-center bg-orange-400 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fas fa-backward me-2"></i>
        Back
    </a>
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
                     <th scope="row" class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">
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