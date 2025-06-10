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
         <h1 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900">Finished <span class="text-red-600">Groups</span> </h1>
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
        <tbody id="groupsTableBody" class="font-semibold text-base">
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
                         <svg class=" w-5 h-5 mr-1.5 md:inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                             <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                         </svg>
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
     <div id="paginationControls" class="flex justify-end items-center gap-2 text-sm m-4"></div>

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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rowsPerPage = 10;
        const tableBody = document.getElementById("groupsTableBody");
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        const paginationControls = document.getElementById("paginationControls");

        let currentPage = 1;

        function renderTablePage(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? "" : "none";
            });
        }

        function createPaginationButtons() {
            paginationControls.innerHTML = "";

            // Prev button
            const prevBtn = document.createElement("button");
            prevBtn.textContent = "Prev";
            prevBtn.disabled = currentPage === 1;
            prevBtn.className = `px-3 py-1 border rounded-md ${prevBtn.disabled ? "bg-gray-200 text-gray-500 cursor-not-allowed" : "bg-white text-gray-800 hover:bg-gray-100"}`;
            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTablePage(currentPage);
                    createPaginationButtons();
                }
            });
            paginationControls.appendChild(prevBtn);

            // Determine which page numbers to show (10 max at a time)
            const maxVisiblePages = 10;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = startPage + maxVisiblePages - 1;

            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement("button");
                btn.textContent = i;
                btn.className = `px-3 py-1 border rounded-md ${i === currentPage ? "bg-blue-600 text-white" : "bg-white text-gray-800 hover:bg-gray-100"}`;
                btn.addEventListener("click", () => {
                    currentPage = i;
                    renderTablePage(currentPage);
                    createPaginationButtons();
                });
                paginationControls.appendChild(btn);
            }

            // Next button
            const nextBtn = document.createElement("button");
            nextBtn.textContent = "Next";
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.className = `px-3 py-1 border rounded-md ${nextBtn.disabled ? "bg-gray-200 text-gray-500 cursor-not-allowed" : "bg-white text-gray-800 hover:bg-gray-100"}`;
            nextBtn.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTablePage(currentPage);
                    createPaginationButtons();
                }
            });
            paginationControls.appendChild(nextBtn);
        }

        // Initialize
        renderTablePage(currentPage);
        createPaginationButtons();
    });
</script>
