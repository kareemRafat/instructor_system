<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>

<?php

// Fetch instructors
$instructors = [];
$stmt = $pdo->prepare("SELECT id, username FROM instructors WHERE is_active = 1 AND role IN ('instructor' , 'admin') AND (branch_id = :branch OR :branch IS NULL)");
$stmt->execute([':branch' => $_GET['branch'] ?? 1]);
$instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch groups
$groups = [];
$stmt = $pdo->prepare("SELECT 
                        g.name,
                        g.day,
                        g.time,
                        g.branch_id,
                        g.is_active,
                        DATE_FORMAT(g.start_date, '%d-%m-%Y') AS start,
                        g.instructor_id 
                        FROM `groups` g 
                        JOIN instructors i ON g.instructor_id = i.id
                        WHERE (:branch IS NULL OR g.branch_id = :branch) AND g.is_active = 1");
$stmt->execute([
    ':branch' => $_GET['branch'] ?? 1
]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize groups by instructor, day, and time
$schedule = [];
foreach ($groups as $group) {
    $instructor_id = $group['instructor_id'];
    $day = $group['day'];
    $time = $group['time'];
    $schedule[$instructor_id][$day][$time]['name'] = $group['name'];
    $schedule[$instructor_id][$day][$time]['start'] = $group['start'];
}

// Time slots and days for the table
$days = ['saturday', 'sunday', 'monday'];
$times = ['10.00', '12.30', '3.00-6.10', '6.00-8.00'];

// tables bg and font random color based on branch
if (isset($_GET['branch']) and $_GET['branch'] == 1) {
    $color = 'bg-blue-500';
    $text = 'text-blue-700';
} else if (isset($_GET['branch']) and $_GET['branch'] == 2) {
    $color = 'bg-teal-600';
    $text = 'text-teal-700';
} else if (isset($_GET['branch']) and $_GET['branch'] == 3) {
    $color = 'bg-purple-500';
    $text = 'text-purple-700';
} else {
    $color = 'bg-blue-500';
    $text = 'text-blue-700';
}
?>



<div class="p-6 bg-gray-50 min-h-screen">
    <div class="mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
            Instructor Schedule
        </h1>

        <!-- filter -->
        <div class="mb-5">
            <form method="get" id="branchForm" class="flex items-center justify-start md:justify-center gap-4">
                <ul id="branches-list" class="flext justify-between items-center lg:w-1/3 md:w-1/2 px-7 text-sm font-medium text-gray-900 bg-white  rounded-lg flex gap-4 shadow">
                    <!-- branch radio buttons -->
                    <?php
                    $branches = $pdo->query("SELECT id, name FROM branches")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($branches as $branch):
                        $checked = (isset($_GET['branch']) && $_GET['branch'] == $branch['id']) ? 'checked' : '';
                    ?>
                        <li class="border-gray-200 sm:border-b-0">
                            <div class="flex items-center">
                                <input
                                    onclick="branchForm.submit()"
                                    id="list-radio-<?= $branch['id'] ?>"
                                    type="radio"
                                    <?= $checked ?>
                                    value="<?= $branch['id'] ?>"
                                    name="branch"
                                    class="list-radio w-4 h-4 <?= $text ?> bg-gray-100 border-gray-300 border">
                                <label for="list-radio-<?= $branch['id'] ?>" class="w-full py-3 ms-2 text-base font-medium text-gray-900"><?= $branch['name'] ?></label>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </form>
        </div>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="w-full bg-white border-collapse">
                <!-- Table Header -->
                <thead>
                    <tr class="<?= $color ?> text-white">
                        <th class="border border-gray-300 p-4 text-left font-semibold w-32">
                            Instructor
                        </th>
                        <?php foreach ($days as $index => $day): ?>
                            <th colspan="4" class="border border-gray-300 <?php echo ($index < 2) ? 'border-r-2 border-r-slate-400' : ''; ?> p-4 text-center font-semibold">
                                <?php echo ucwords($day); ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                    <!-- Time slots sub-header -->
                    <tr class="<?= $color ?> text-white">
                        <th class="border border-gray-300 p-2"></th>
                        <?php foreach ($days as $dayIndex => $day): ?>
                            <?php foreach ($times as $index => $time): ?>
                                <th class="border border-gray-300 <?php echo ($index === 3 && $dayIndex < 2) ? 'border-r-2 border-r-slate-400' : ''; ?> p-2 text-sm font-medium">
                                    <?php
                                    if ($time === '3.00-6.10') {
                                        echo '3.00';
                                    } elseif ($time === '6.00-8.00') {
                                        echo '6.00';
                                    } else {
                                        echo $time;
                                    }
                                    ?>
                                </th>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody>
                    <?php foreach ($instructors as $instructor): ?>
                        <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-200">
                            <td class="border border-gray-300 p-4 font-semibold <?= $text ?> bg-gray-200">
                                <?php echo htmlspecialchars(ucwords($instructor['username'])); ?>
                            </td>
                            <?php foreach ($days as $dayIndex => $day): ?>
                                <?php foreach ($times as $index => $time): ?>
                                    <td class="border border-gray-300 <?php echo ($index === 3 && $dayIndex < 2) ? 'border-r-2 border-r-slate-400' : ''; ?> p-2 text-center h-16 w-20 hover:bg-yellow-50 cursor-pointer transition-colors duration-150">
                                        <?php
                                        // For combined time slots
                                        if ($time === '3.00-6.10') {
                                            $firstSlot = isset($schedule[$instructor['id']][$day]['3.00']);
                                            $secondSlot = isset($schedule[$instructor['id']][$day]['6.10']);
                                            if ($firstSlot || $secondSlot) { ?>
                                                <div class="flex flex-col items-center gap-1">
                                                    <?php if ($firstSlot) { ?>
                                                        <div>
                                                            <span class="<?= $text ?> font-semibold text-base"><?= ucwords($schedule[$instructor['id']][$day]['3.00']['name']) ?></span>
                                                            <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['3.00']['start'] ?? ''; ?></span>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($secondSlot) { ?>
                                                        <div>
                                                            <span class="<?= $text ?> font-semibold text-base"><?= ucwords($schedule[$instructor['id']][$day]['6.10']['name']) ?></span>
                                                            <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['start'] ?? ''; ?></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                        } elseif ($time === '6.00-8.00') {
                                            $firstSlot = isset($schedule[$instructor['id']][$day]['6.00']);
                                            $secondSlot = isset($schedule[$instructor['id']][$day]['8.00']);
                                            if ($firstSlot || $secondSlot) { ?>
                                                <div class="flex flex-col items-center gap-1">
                                                    <?php if ($firstSlot) { ?>
                                                        <div>
                                                            <span class="<?= $text ?> font-semibold text-base"><?= ucwords($schedule[$instructor['id']][$day]['6.00']['name']) ?></span>
                                                            <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['6.00']['start'] ?? ''; ?></span>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($secondSlot) { ?>
                                                        <div>
                                                            <span class="<?= $text ?> font-semibold text-base"><?= ucwords($schedule[$instructor['id']][$day]['8.00']['name']) ?></span>
                                                            <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['8.00']['start'] ?? ''; ?></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                        } else {
                                            // Original code for other time slots
                                            if (isset($schedule[$instructor['id']][$day][$time])) { ?>
                                                <div class="flex flex-col items-center">
                                                    <span class="<?= $text ?> font-semibold text-base"><?= ucwords($schedule[$instructor['id']][$day][$time]['name']) ?></span>
                                                    <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day][$time]['start'] ?? ''; ?></span>
                                                </div>
                                        <?php }
                                        } ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="module" src="dist/tables-main.js"></script>
</body>

</html>