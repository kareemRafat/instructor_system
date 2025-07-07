<?php
$stmt = $pdo->query("
    SELECT s.*, i.username AS instructor_name
    FROM salary_records s
    JOIN instructors i ON i.id = s.instructor_id
    WHERE s.created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
    ORDER BY s.created_at ASC
");

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group records by year-month
$grouped = [];
foreach ($records as $record) {
    $monthKey = date('Y-m', strtotime($record['created_at']));
    $grouped[$monthKey][] = $record;
}
?>

<table class="w-full text-sm text-left rtl:text-right text-gray-600">
    <thead class="text-sm text-gray-800 uppercase bg-gray-200">
        <tr>
            <th class="px-4 py-3">Employee</th>
            <th class="px-4 py-3">Basic Salary</th>
            <th class="px-4 py-3">Overtime</th>
            <th class="px-4 py-3">Day Value</th>
            <th class="px-4 py-3">Target</th>
            <th class="px-4 py-3">Bonuses</th>
            <th class="px-4 py-3">Advances</th>
            <th class="px-4 py-3">Absent</th>
            <th class="px-4 py-3">Deduction</th>
            <th class="px-4 py-3 text-green-800 font-bold">Total</th>
            <th class="px-4 py-3">Created At</th>
        </tr>
    </thead>
    <tbody class="text-base font-medium">
        <?php if(empty($records)): ?>
            <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200 hover:bg-gray-100">
                    <td class="px-4 py-3 text-gray-800" colspan="11">No Records Found</td>
            <tr>
        <?php endif; ?>
        <?php foreach ($grouped as $month => $rows): ?>
            <?php
            $monthlyTotal = 0;
            foreach ($rows as $row):
                $monthlyTotal += $row['total'];
            ?>
                <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200 hover:bg-gray-100">
                    <td class="px-4 py-3 text-blue-800"><?= htmlspecialchars(ucwords($row['instructor_name'])) ?></td>
                    <td class="px-4 py-3"><?= $row['basic_salary'] ?></td>
                    <td class="px-4 py-3"><?= $row['overtime_days'] ?></td>
                    <td class="px-4 py-3"><?= $row['day_value'] ?></td>
                    <td class="px-4 py-3"><?= $row['target'] ?></td>
                    <td class="px-4 py-3"><?= $row['bonuses'] ?></td>
                    <td class="px-4 py-3"><?= $row['advances'] ?></td>
                    <td class="px-4 py-3"><?= $row['absent_days'] ?></td>
                    <td class="px-4 py-3"><?= $row['deduction_days'] ?></td>
                    <td class="px-4 py-3 font-bold text-green-700"><?= $row['total'] ?></td>
                    <td class="px-4 py-3 text-gray-500"><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>

            <!-- Monthly Total Row -->
            <tr class="bg-yellow-100 bg-opacity-65 border-t border-b border-yellow-300 font-bold text-gray-800">
                <td colspan="10" class="px-4 py-2">Total for <?= date('F Y', strtotime($month . '-01')) ?> : <span class="px-4 py-3 text-green-800"><?= number_format($monthlyTotal, 2) ?></span></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>