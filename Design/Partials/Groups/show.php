<style>
    .tooltip-arrow::before {
        content: '';
        position: absolute;
        border-style: solid;
        border-width: 6px;
        border-color: transparent transparent #64748b transparent;
        /* for top */
        top: -6px;
        left: 50%;
        transform: translateX(-50%);
    }
</style>

<?php

$query = "SELECT 
            l.*, 
            t.name AS track_name,
            i.username AS instructor_name,
            g.name AS group_name,
            DATE_FORMAT(l.date, '%d-%m-%Y') AS formatted_date
            FROM lectures AS l 
            JOIN `groups` AS g ON g.id = l.group_id
            JOIN instructors AS i ON i.id = l.instructor_id
            JOIN tracks AS t ON t.id = l.track_id
            WHERE l.group_id = :group
            ORDER BY date ASC";

$stmt = $pdo->prepare($query);
$stmt->execute([':group' => $_GET['id']]);
$lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($lectures) == 0) {
    include "not_found.php";
    exit();
}

$data = [];

foreach ($lectures as $lecture) {
    $data[$lecture['track_name']][] = [
        'comment' => $lecture['comment'],
        'date' => $lecture['formatted_date'],
        'instructor_name' => $lecture['instructor_name']
    ];
}

?>
<div class=" min-h-screen max-w-8xl mx-auto md:px-6 py-6 pb-20">
    <!-- Track Header -->
    <div class=" flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
        <div>
            <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl"><span class="text-blue-600"><?= ucwords($lecture['group_name']) ?></span> Group </h3>
        </div>
        <!-- back button component -->
        <?php include_once "Design/Components/back-component.php"; ?>
    </div>
    <p class="text-lg font-semibold text-gray-500 mb-4 tracking-wider">Instructor: <?= getLastInstructorName($data) ?></p>
    <?php $id = 0; ?>
    <?php foreach ($data as $key =>  $row) : ?>
        <?php ++$id ?>
        <!-- track Name -->
        <div class="bg-gray-50 pb-4 rounded-md mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-bolt"></i>
                <span><?= ucwords($key) ?></span>
            </h2>

            <!-- Comments Row -->
            <div class="grid gird-cols-1 lg:grid-cols-2 md:gap-4 gap-6">
                <?php foreach ($row as $key => $newData): ?>
                    <!-- single comment -->
                    <div class="flex items-start lg:items-center justify-between flex-col gap-4  bg-white py-4 md:p-4 shadow-sm border border-slate-400 hover:bg-gray-50 relative rounded-md">
                        <div class="flex lg:items-center justify-between lg:justify-start gap-3 px-3 md:ml-0 relative w-full">
                            <div class="flex flex-col gap-2 items-start pr-3">
                                <div class="flex gap-4 items-center">
                                    <i class="hidden md:inline-block fa-solid fa-comment text-slate-600"></i>
                                    <p class="font-medium text-sm md:text-base"><?= $newData['comment'] ?></p>
                                </div>
                            </div>

                        </div>
                        <!-- data md -->
                        <div class="flex items-center gap-3 px-3 w-full">
                            <p class="flex font-medium text-sm text-sky-600 md:w-fit">
                                    <span> <span class="mr-1">|</span><?= $newData['instructor_name'] ?></span>
                                </p>
                            <div class="flex items-center justify-end gap-4 self-end text-slate-600 w-full text-sm md:text-base">
                                <div>
                                    <i class="text-sm fa-solid fa-calendar-week mr-2"></i>
                                    <span class="font-semibold"><?= $newData['date'] ?></span>
                                    <span class="ml-2 hidden md:inline-block"> | </span>
                                </div>

                                <div class="font-semibold">
                                    <i class="fa-solid fa-stopwatch-20 mr-1"></i>
                                    <?= timeago($newData['date']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end single comment -->
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php 

    unset($_SESSION['params']);

?>
<?php
/** get the last instructor for the group */
function getLastInstructorName($data)
{
    return  ucwords(end($data)[0]['instructor_name']);
}

/** time ago */
function timeAgo($dateString)
{
    // Convert string to DateTime
    $date = DateTime::createFromFormat('d-m-Y', $dateString);
    if (!$date) return 'Invalid date';

    $now = new DateTime();
    $diff = $now->diff($date);

    // Past or future
    $isPast = $date < $now;

    if ($diff->y > 0) {
        $value = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
    } elseif ($diff->m > 0) {
        $value = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
    } elseif ($diff->d > 0) {
        $value = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
    } else {
        return 'Today';
    }

    return $isPast ? "$value ago" : "in $value";
}

?>