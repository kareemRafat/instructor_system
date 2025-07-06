<?php
$stmt = $pdo->prepare("
    SELECT 
        sr.*, 
        i.username AS instructor_name 
    FROM 
        salary_records sr
    JOIN 
        instructors i ON sr.instructor_id = i.id
    ORDER BY 
        sr.created_at DESC
");
$stmt->execute();
$salaryRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table class="w-full text-base text-left rtl:text-right text-gray-600">
    <thead class="text-sm text-gray-800 uppercase bg-gray-200">
        <tr>
            <th class="px-6 py-4 font-semibold">الموظف</th>
            <th class="px-6 py-4 font-semibold">المرتب الأساسي</th>
            <th class="px-6 py-4 font-semibold">أوفر تايم</th>
            <th class="px-6 py-4 font-semibold">قيمة اليوم</th>
            <th class="px-6 py-4 font-semibold">التارجت</th>
            <th class="px-6 py-4 font-semibold">المكافآت</th>
            <th class="px-6 py-4 font-semibold">السلف</th>
            <th class="px-6 py-4 font-semibold">الغياب</th>
            <th class="px-6 py-4 font-semibold">الخصم</th>
            <th class="px-6 py-4 text-green-800 font-bold">الإجمالي</th>
            <!-- <th class="px-6 py-4">Created At</th> -->
        </tr>
    </thead>
    <tbody class="text-base font-medium">
        <?php foreach ($salaryRows as $row): ?>
            <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-300 hover:bg-gray-100">
                <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($row['instructor_name']) ?></td>
                <td class="px-6 py-4"><?= $row['basic_salary'] ?></td>
                <td class="px-6 py-4"><?= $row['overtime_days'] ?></td>
                <td class="px-6 py-4"><?= $row['day_value'] ?></td>
                <td class="px-6 py-4"><?= $row['target'] ?></td>
                <td class="px-6 py-4"><?= $row['bonuses'] ?></td>
                <td class="px-6 py-4"><?= $row['advances'] ?></td>
                <td class="px-6 py-4"><?= $row['absent_days'] ?></td>
                <td class="px-6 py-4"><?= $row['deduction_days'] ?></td>
                <td class="px-6 py-4 font-bold text-green-700"><?= $row['total'] ?></td>
                <!-- <td class="px-6 py-4 text-gray-500"><?= $row['created_at'] ?></td> -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

