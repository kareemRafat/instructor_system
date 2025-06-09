<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';


// Add SlimSelect CDN links
echo '<link href="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.css" rel="stylesheet">';
echo '<script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.js"></script>';


try {

    $selectedMonth = $_GET['month'] ?? date('F');
    $selectedYear = $_GET['year'] ?? date('Y');

    // get groups data
    $query = "SELECT 
                    b.name AS branch_name,
                    i.username AS instructor_username,
                    g.name AS group_name,
                    bo.total_students,
                    bo.unpaid_students,
                    ROUND((bo.unpaid_students / NULLIF(bo.total_students, 0) * 100), 2) AS percentage
                FROM 
                    bonus bo
                    INNER JOIN groups g ON bo.group_id = g.id
                    INNER JOIN branches b ON g.branch_id = b.id
                    INNER JOIN instructors i ON g.instructor_id = i.id
                    INNER JOIN branch_instructor bi ON i.id = bi.instructor_id AND b.id = bi.branch_id";

    $whereClauses = [];
    $params = [];

    if ($selectedMonth) {
        $whereClauses[] = "MONTHNAME(bo.finish_date) = :month";
        $params[':month'] = $selectedMonth;
    }
    if ($selectedYear) {
        $whereClauses[] = "YEAR(bo.finish_date) = :year";
        $params[':year'] = $selectedYear;
    }
    if (!empty($whereClauses)) {
        $query .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $query .= " ORDER BY b.name, i.username, g.name";


    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $bonusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $organizedData = [];
    $totalBonusLec = [];
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
        if ($row['percentage'] < 20) {
            $totalBonusLec[$branch]['percentages'][] = $row['percentage'];
        }
    }
} catch (PDOException $e) {
    print_r($e->getMessage());
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
            </select>
        </div>
    </div>

    <!-- Display data by branch and instructor -->
    <?php if (empty($organizedData)): ?>
        <p class="text-left text-gray-600 font-semibold">No bonus data available
            <span class="font-bold"><?= $selectedMonth ? " for $selectedMonth" : '' ?>.</span>
            year
            <span class="font-bold"><?= $selectedYear ? " $selectedYear" : '' ?>. yet</span>
        </p>
    <?php else: ?>

        <div class="flex justify-between items-center mb-5">
            <h1 class="text-xl font-bold leading-none tracking-tight text-gray-900 md:text-xl lg:text-xl">Bonus data For <span class="text-blue-600"><?= ucwords($selectedMonth) ?></span> year
                <span class="font-bold text-blue-600"><?= $selectedYear ? " $selectedYear" : '' ?>.</span>
            </h1>
            <p class="text-white bg-blue-600 border border-gray-200 rounded-lg shadow-md text-base px-3 py-1"><a href="bonus.php" class="inline-flex items-center font-medium hover:underline">
                    <svg class="w-4 h-4 me-2 rotate-90 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                    Reset
                </a></p>
        </div>
        <div class="relative overflow-x-auto grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($organizedData as $branch => $instructors): ?>
                <div class="overflow-x-auto">
                    <div class="<?= headerColor($branch) ?> p-3 w-full mb-5 rounded-md text-white font-semibold text-base flex justify-between">
                        <?= htmlspecialchars($branch) ?>
                        <span class="flex flex-row items-center text-white">
                            <?php if ($totalBonusLec) { ?>
                                <span class="font-bold"><?= count($totalBonusLec[$branch]['percentages']) ?></span>
                                <svg class="ml-2 w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z" />
                                </svg>
                            <?php } else { ?>
                                <span> No bonus </span>
                            <?php } ?>
                        </span>
                    </div>
                    <table class="w-full md:w-[80%] lg:w-full text-sm text-left rtl:text-right text-gray-500  mb-4">
                        <?php foreach ($instructors as $instructor => $groups): ?>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-2 font-bold text-lg underline underline-offset-4 decoration-2 tracking-wider">
                                        <?= htmlspecialchars($instructor) ?>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Total <span class="hidden md:inline-block">Students</span>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Unpaid <span class="hidden md:inline-block">Students</span>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        <span class="inline-block md:hidden">%</span>
                                        <span class="hidden md:inline-block">Percentage</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="border-b-2 border-gray-400">
                                <?php foreach ($groups as $group): ?>
                                    <tr class="bg-white <?= $group['percentage'] < 20 ? ' bg-green-100 text-green-600 font-bold' : 'font-semibold' ?> border-b border-gray-200">
                                        <th scope="row" class="px-4 py-4  text-gray-900 whitespace-nowrap">
                                            <span class="hidden md:inline-block">
                                                <?= ucwords(htmlspecialchars($group['group_name'])) ?>
                                            </span>
                                            <span class="inline-block md:hidden">
                                                <?= breakTwoWordPhrase(ucwords(htmlspecialchars($group['group_name']))) ?>
                                            </span>
                                        </th>
                                        <td class="px-4 py-4">
                                            <?= htmlspecialchars($group['total_students']) ?>
                                        </td>
                                        <td class="px-4 py-4">
                                            <?= htmlspecialchars($group['unpaid_students']) ?>
                                        </td>
                                        <td class="px-4 py-4">
                                            <?= htmlspecialchars($group['percentage']) ?>%
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script type="module" src="dist/bonus.js"></script>
</div>

<?php

function headerColor($branchName)
{
    $branchName = strtolower($branchName);
    if ($branchName == 'mansoura') {
        return "bg-[#1b5180]";
    } elseif ($branchName == 'tanta') {
        return "bg-teal-800";
    } elseif ($branchName == 'zagazig') {
        return "bg-[#5F4B8B]";
    }
}

function breakTwoWordPhrase($text)
{
    $parts = explode(' ', trim($text));
    if (count($parts) === 2) {
        return implode('<br>', $parts);
    }
    return $text; // Return original text if not exactly two words
}
?>