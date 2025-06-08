<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';


try {

    // get groups data
    $query = "SELECT 
                b.name AS branch_name,
                i.username AS instructor_username,
                g.name AS group_name,
                bo.total_students,
                bo.unpaid_students,
                ROUND((bo.unpaid_students / bo.total_students * 100), 2) AS percentage
            FROM 
                bonus bo
                INNER JOIN groups g ON bo.group_id = g.id
                INNER JOIN branches b ON g.branch_id = b.id
                INNER JOIN instructors i ON g.instructor_id = i.id
                INNER JOIN branch_instructor bi ON i.id = bi.instructor_id AND b.id = bi.branch_id
            ORDER BY 
                b.name, i.username, g.name";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $bonusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $organizedData = [];
    foreach ($bonusData as $row) {
        $branch = $row['branch_name'];
        $instructor = $row['instructor_username'];
        if (!isset($organizedData[$branch])) {
            $organizedData[$branch] = [];
        }
        if (!isset($organizedData[$branch][$instructor])) {
            $organizedData[$branch][$instructor] = [];
        }
        $organizedData[$branch][$instructor][] = $row;
    }
} catch (PDOException $e) {
    echo "<pre>";
    print_r($e);
}

?>
<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Instructor's Bonus</h1>

    <!-- filter row -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-7 space-y-4 md:space-y-0 md:space-x-4">
        <!-- Month Dropdown -->
        <div class="w-full md:flex-1">
            <label for="month-select" class="block mb-2 text-sm font-medium text-gray-900">Month</label>
            <select id="month-select"
                class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option selected>Select a Month</option>
            </select>
        </div>
    </div>

    <!-- Display data by branch and instructor -->
    <?php if (empty($organizedData)): ?>
        <p class="text-center text-gray-600">No bonus data available<?= $selectedMonth ? " for $selectedMonth" : '' ?>.</p>
    <?php else: ?>
        <?php foreach ($organizedData as $branch => $instructors): ?>
            <div class="relative overflow-x-auto">
                <div class="<?= headerColor($branch) ?> p-3 w-full mb-5 rounded-md text-white font-semibold text-base">
                    <?= htmlspecialchars($branch) ?>
                </div>
                <?php foreach ($instructors as $instructor => $groups): ?>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-4">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold text-base">
                                    <?= htmlspecialchars($instructor) ?>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total Students
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Unpaid Students
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Percentage
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups as $group): ?>
                                <tr class="bg-white <?= $group['percentage'] < 20 ? 'bg-green-100 text-green-600 font-semibold' : 'font-normal' ?> border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4  text-gray-900 whitespace-nowrap dark:text-white">
                                        <?= ucwords(htmlspecialchars($group['group_name'])) ?>
                                    </th>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($group['total_students']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($group['unpaid_students']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($group['percentage']) ?>%
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script type="module" src="js/bonus.js"></script>
</div>

<?php 

    function headerColor($branchName) {
        $branchName = strtolower($branchName);
        if ($branchName == 'mansoura') {
            return "bg-[#1b5180]";
        } elseif ($branchName == 'tanta'){
            return "bg-teal-800";
        } elseif ($branchName == 'zagazig'){
            return "bg-[#5F4B8B]";
        }
    }

?>