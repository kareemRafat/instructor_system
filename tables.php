<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';

// use it when finish group header me here
$_SESSION['current_branch_id'] = $_GET['branch'] ?? 1;

?>

<?php
// Fetch instructors
$instructors = [];
$stmt = $pdo->prepare("SELECT i.id, i.username
                        FROM instructors i
                        JOIN branch_instructor bi ON bi.instructor_id = i.id
                        WHERE bi.branch_id = :branch AND i.is_active = 1 AND i.role IN ('instructor', 'admin')");
$stmt->execute([':branch' => $_GET['branch'] ?? 1]);
$instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

/** Fetch groups */
// To handle the logic "use g.second_instructor_id if it's not NULL, otherwise use g.instructor_id" when joining with the instructors table, you can use a LEFT JOIN and COALESCE.
$groups = [];
$stmt = $pdo->prepare("SELECT 
                            IF(g.name LIKE '%training%', 'training', g.name) AS name,
                            g.id,
                            g.day,
                            g.time,
                            g.branch_id,
                            g.is_active,
                            DATE_FORMAT(g.start_date, '%d-%m-%Y') AS start,
                            COALESCE(g.second_instructor_id, g.instructor_id) AS instructor_id
                        FROM `groups` g
                        JOIN instructors i ON i.id = COALESCE(g.second_instructor_id, g.instructor_id)
                        WHERE g.is_active = 1 AND g.branch_id = :branch");
$stmt->execute([
    ':branch' => $_GET['branch'] ?? 1
]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize groups by instructor, day, and time
$schedule = [];
$groupsCount = [] ;
foreach ($groups as $group) {
    $instructor_id = $group['instructor_id'];
    $day = $group['day'];
    $time = $group['time'];
    $schedule[$instructor_id][$day][$time]['id'] = $group['id'];
    $schedule[$instructor_id][$day][$time]['name'] = $group['name'];
    $schedule[$instructor_id][$day][$time]['start'] = $group['start'];

    // get group count for each instructor
    if (!isset($groupsCount[$instructor_id])) {
        $groupsCount[$instructor_id] = 0;
    }
    $groupsCount[$instructor_id]++;
}

// Time slots and days for the table
$days = ['saturday', 'sunday', 'monday'];
$times = ['10.00', '12.30-4.00', '3.00-6.10', '6.00-8.00'];

// tables bg and font random color based on branch
if (isset($_GET['branch']) and $_GET['branch'] == 1) {
    $color = 'bg-[#1b5180]';
    $text = 'text-[#0F4C81]';
} else if (isset($_GET['branch']) and $_GET['branch'] == 2) {
    $color = 'bg-teal-800';
    $text = 'text-teal-700';
} else if (isset($_GET['branch']) and $_GET['branch'] == 3) {
    $color = 'bg-[#5F4B8B]';
    $text = 'text-violet-800';
} else if (isset($_GET['branch']) and $_GET['branch'] == 4) {
    $color = 'bg-blue-600';
    $text = 'text-blue-800';
} else {
    $color = 'bg-[#1b5180]';
    $text = 'text-[#0F4C81]';
}

$rowHoverColors = ['hover:bg-orange-50',  'hover:bg-indigo-50', 'hover:bg-green-50',  'hover:bg-rose-50', 'hover:bg-purple-50', 'hover:bg-blue-50'];

$cellHoverColor = ['hover:bg-orange-100', 'hover:bg-indigo-100', 'hover:bg-green-100',  'hover:bg-rose-100', 'hover:bg-purple-100', 'hover:bg-blue-100'];

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
                    foreach ($branches as $index => $branch):
                        $checked = (isset($_GET['branch']) && $_GET['branch'] == $branch['id']) ? 'checked' : '';
                        // Check the first branch if no branch is selected
                        if (!isset($_GET['branch']) && $index === 0) {
                            $checked = 'checked';
                        }
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
            <table class="w-full border-collapse">
                <!-- Table Header -->
                <thead>
                    <tr class="<?= $color ?> bg-[#1b5180] text-white">
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
                    <tr class="<?= $color ?> bg-[#1b5180] text-white">
                        <th class="border border-gray-300 p-2"></th>
                        <?php foreach ($days as $dayIndex => $day): ?>
                            <?php foreach ($times as $index => $time): ?>
                                <th class="border border-gray-300 <?php echo ($index === 3 && $dayIndex < 2) ? 'border-r-2 border-r-slate-400' : ''; ?> p-2 text-sm font-medium">
                                    <?php
                                    if ($time === '12.30-4.00') {
                                        echo '12.30';
                                    } elseif ($time === '3.00-6.10') {
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
                <tbody id="tables-table-body">
                    <?php foreach ($instructors as $index => $instructor): ?>
                        <?php $hoverColor = $rowHoverColors[$index % count($rowHoverColors)]; ?>
                        <?php $tdHoverColor = $cellHoverColor[$index % count($cellHoverColor)]; ?>
                        <tr class="bg-gray-50 <?= $hoverColor ?> transition-colors duration-300">
                            <td class="h-20 relative border border-gray-300 p-4 font-semibold <?= $text ?> bg-gray-200 overflow-hidden">
                                <div class="flex justify-between ">
                                    <span><?= htmlspecialchars(ucwords($instructor['username'])) ?></span>
                                    <span class="text-gray-100 text-8xl absolute right-0 top-0 font-bold opacity-80"><?=  $groupsCount[$instructor['id']] ?? 0 ?></span>
                                </div>
                            </td>
                            <?php foreach ($days as $dayIndex => $day): ?>
                                <?php foreach ($times as $index => $time): ?>
                                    <td class="border border-gray-300 <?php echo ($index === 3 && $dayIndex < 2) ? 'border-r-2 border-r-slate-400' : ''; ?> p-2 text-center h-16 w-20 <?= $tdHoverColor ?> cursor-pointer transition-colors duration-300">
                                        <?php
                                        // For combined time slots
                                        if ($time === '12.30-4.00') {
                                            $firstSlot = isset($schedule[$instructor['id']][$day]['12.30']);
                                            $secondSlot = isset($schedule[$instructor['id']][$day]['4.00']);
                                            if ($firstSlot || $secondSlot) { ?>
                                                <div class="flex flex-col items-center gap-1">
                                                    <?php if ($firstSlot) { ?>
                                                        <div>
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['12.30']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['12.30']['name']) ?>
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['12.30']['start'] ?? ''; ?></span>
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($secondSlot) { ?>
                                                        <div>
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['4.00']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['4.00']['name']) ?>(4)
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['4.00']['start'] ?? ''; ?></span>
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                        } elseif ($time === '3.00-6.10') {
                                            $firstSlot = isset($schedule[$instructor['id']][$day]['3.00']);
                                            $secondSlot = isset($schedule[$instructor['id']][$day]['6.10']);
                                            if ($firstSlot || $secondSlot) { ?>
                                                <div class="flex flex-col items-center gap-1">
                                                    <?php if ($firstSlot) { ?>
                                                        <div>
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['3.00']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['3.00']['name']) ?>
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['3.00']['start'] ?? ''; ?></span>
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($secondSlot) { ?>
                                                        <div>
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['6.10']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['6.10']['name']) ?>(6)
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['6.10']['start'] ?? ''; ?></span>
                                                            </button>
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
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['6.00']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['6.00']['name']) ?>
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['6.00']['start'] ?? ''; ?></span>
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($secondSlot) { ?>
                                                        <div>
                                                            <button
                                                                data-group-id="<?= $schedule[$instructor['id']][$day]['8.00']['id'] ?>"
                                                                class="outline-none"
                                                                type="button"
                                                                data-drawer-target="drawer-left-example"
                                                                data-drawer-show="drawer-left-example"
                                                                data-drawer-placement="left"
                                                                aria-controls="drawer-left-example">
                                                                <span class="<?= $text ?> font-semibold text-base">
                                                                    <?= ucwords($schedule[$instructor['id']][$day]['8.00']['name']) ?>(8)
                                                                </span>
                                                                <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day]['8.00']['start'] ?? ''; ?></span>
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                        } else {
                                            // Original code for other time slots
                                            if (isset($schedule[$instructor['id']][$day][$time])) { ?>
                                                <div class="flex flex-col items-center">
                                                    <button
                                                        data-group-id="<?= $schedule[$instructor['id']][$day][$time]['id'] ?>"
                                                        class="outline-none"
                                                        type="button"
                                                        data-drawer-target="drawer-left-example"
                                                        data-drawer-show="drawer-left-example"
                                                        data-drawer-placement="left"
                                                        aria-controls="drawer-left-example">
                                                        <span class="<?= $text ?> font-semibold text-base">
                                                            <?= ucwords($schedule[$instructor['id']][$day][$time]['name']) ?>
                                                        </span>
                                                        <span class="text-sm md:block hidden font-semibold"><?= $schedule[$instructor['id']][$day][$time]['start'] ?? ''; ?></span>
                                                    </button>
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
<script src="dist/tables-main.js"></script>
<?php
include_once "Design/Modals/tables_drawer.php";
include_once "Design/includes/notFy-footer.php";
?>
</body>

</html>