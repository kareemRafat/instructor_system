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
        <a href="groups.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
            <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
            <span class="w-full">Back</span>
        </a>
    </div>
    <p class="text-lg font-semibold text-gray-500 mb-4 tracking-wider">Instructor: <?= getLastInstructorName($data) ?></p>

    <?php foreach ($data as $key =>  $row) : ?>
        <!-- track Name -->
        <div class="bg-gray-50 pb-4 rounded-md mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-bolt"></i>
                <span><?= ucwords($key) ?></span>
            </h2>

            <!-- Comments Row -->
            <div class="grid gird-cols-1 lg:grid-cols-2 md:gap-4 gap-6">
                <?php $id = 0 ?>
                <?php foreach ($row as $key => $newData): ?>
                    <!-- single comment -->
                    <div class="flex items-start md:items-center justify-between flex-col gap-4 md:flex-row bg-white py-4 md:p-4 shadow-sm border border-slate-400 hover:bg-gray-50 relative rounded-md overflow-hidden">
                        <h1 class="hidden md:flex absolute left-[48%] text-7xl font-bold -z-0 text-gray-200"><?= ++$id ?></h1>
                        <div class="flex md:items-center gap-3 ml-3 md:ml-0 relative z-10">
                            <div class="flex gap-4 items-center">
                                <i class="hidden md:inline-block fa-solid fa-comment text-slate-600"></i>
                                <p class="font-medium text-sm"><?= $newData['comment'] ?></p>
                                <i data-tooltip-target="tooltip-animation<?= $key ?>" class="fa-solid fa-user-group text-sky-600"></i>
                            </div>
                            <!-- tooltip -->
                            <div id="tooltip-animation<?= $key ?>" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-slate-500 rounded-lg shadow-xs opacity-0 tooltip">
                                <?= $newData['instructor_name'] ?>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <!-- data md -->
                        <div class="flex items-center gap-3 px-3 w-full md:w-fit ">
                            <div class="flex items-center justify-between md:justify-start gap-4 self-end text-slate-600 w-full">
                                <div>
                                    <i class="text-sm fa-solid fa-calendar-week mr-2"></i>
                                    <span class="text-base font-semibold"><?= $newData['date'] ?></span>
                                    <span class="ml-2 hidden md:inline-block"> | </span>
                                </div>
                                <div class="text-base font-semibold">
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