<?php

$errors = $_SESSION['errors'] ?? [];

function getInstructors($pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            i.id ,
                            i.username AS username ,
                            bi.branch_id AS branch_id,
                            b.name AS branch_name
                        FROM `instructors` i
                        JOIN branch_instructor bi ON i.id = bi.instructor_id
                        JOIN branches b ON b.id = bi.branch_id 
                        WHERE i.role IN ('cs-admin' , 'cs')");
    $stmt->execute();
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $finalData = [];

    foreach ($instructors as $instructor) {
        $finalData[$instructor['branch_name']][] = $instructor;
    }

    return $finalData;
}

?>

<div class="p-3 md:p-3 flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
    <div>
        <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Add <span class="text-blue-600"> SALARY</span> info </h3>
    </div>
    <a href="customer-service.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
        <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
        </svg>
        <span class="w-full">Back</span>
    </a>
</div>


<form class="max-w-8xl mx-auto p-6 rounded-lg">
    <div class="gap-5 grid grid-cols-1  md:grid-cols-2 lg:grid-cols-3">
        <!-- الاسم -->
        <div>
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 ">اختر الموظف</label>
            <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option selected>اختر الموظف</option>
                <?php foreach (getInstructors($pdo) as $branch => $instructors): ?>
                    <optgroup label="<?= ucwords($branch) ?>" class="tracking-wider">
                        <?php foreach ($instructors as $instructor) : ?>
                            <option value="<?= $instructor['id'] ?>"><?= ucwords($instructor['username']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- المرتب الأساسي -->
        <div>
            <label for="basic_salary" class="block mb-2 text-sm font-medium text-gray-900">المرتب الأساسي</label>
            <input type="number" id="basic_salary" name="basic_salary" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- أوفر تايم+مكافأت -->
        <div>
            <label for="overtime_days" class="block mb-2 text-sm font-medium text-gray-900">أوفر تايم + مكافأت (بالأيام)</label>
            <input type="number" id="overtime_days" name="overtime_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- قيمة اليوم -->
        <div>
            <label for="day_value" class="block mb-2 text-sm font-medium text-gray-900">قيمة اليوم</label>
            <input type="number" id="day_value" name="day_value" class="calc-field bg-gray-50 border border-gray-300 text-blue-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold">
        </div>

        <!-- التارجت -->
        <div>
            <label for="target" class="block mb-2 text-sm font-medium text-gray-900">التارجت</label>
            <input type="number" id="target" name="target" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- المكافآت -->
        <div>
            <label for="bonuses" class="block mb-2 text-sm font-medium text-gray-900">المكافآت</label>
            <input type="number" id="bonuses" name="bonuses" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- السلف -->
        <div>
            <label for="advances" class="block mb-2 text-sm font-medium text-gray-900">السلف</label>
            <input type="number" id="advances" name="advances" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- الغياب -->
        <div>
            <label for="absent_days" class="block mb-2 text-sm font-medium text-gray-900">الغياب (بالأيام)</label>
            <input type="number" id="absent_days" name="absent_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <!-- خصم -->
        <div>
            <label for="deduction_days" class="block mb-2 text-sm font-medium text-gray-900">خصم (بالأيام)</label>
            <input type="number" id="deduction_days" name="deduction_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
    </div>
    <!-- الإجمالي -->
    <div class="flex flex-col items-end mt-10">
        <!-- Stylish result display -->
        <div 
            class="bg-blue-50 text-blue-700 font-extrabold text-3xl rounded px-6 py-3 border-b-2 border-blue-500 w-[300px]">
            <label class="block mr-7 mb-2 text-sm font-medium text-gray-900">الإجمالي</label>
            <span id="totalDisplayBox">0.00</span>
        </div>

    </div>
    <!-- Submit Button -->
    <button type="submit" class="mt-5 w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  sm:w-auto px-5 py-2.5 text-center">
        حفظ البيانات
    </button>

</form>






<script>
    // salary formula auto calculate
    const fields = document.querySelectorAll(".calc-field");
    const totalField = document.getElementById("total");
    const dayValue = document.getElementById('day_value');

    fields.forEach(field => {
        field.addEventListener("input", calculateTotal);
    });

    function getVal(id) {
        return parseFloat(document.getElementById(id).value) || 0;
    }

    function calculateTotal() {
        const basic = getVal("basic_salary");
        const otDays = getVal("overtime_days");
        const dayVal = getVal("day_value");
        const target = getVal("target");
        const bonuses = getVal("bonuses");
        const advances = getVal("advances");
        const absent = getVal("absent_days");
        const deduction = getVal("deduction_days");

        dayValue.value = Math.ceil(basic / 30);

        const total = (basic + (otDays * dayVal) + target + bonuses - advances) -
            (absent * dayVal) -
            (deduction * dayVal);

        const formatted = total.toFixed(2);
        // update the div instead of input
        document.getElementById("totalDisplayBox").textContent = formatted;
    }
</script>