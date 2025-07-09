<?php


if (!hasRole('owner', 'admin')) {
    include_once "not_found.php";
    exit();
}

$agentId = $_GET['id'];
$agentRecores = getAgentSalaryRecords($agentId, $pdo);
$agent = getAgentById($agentId, $pdo);

function getAgentSalaryRecords($agentId, $pdo)
{
    $stmt = $pdo->prepare("SELECT
                            i.username AS cs_name ,
                            i.role,
                            sr.instructor_id AS instructor_id,
                            sr.basic_salary AS basic_salary ,
                            sr.overtime_days AS overtime_days , 
                            sr.day_value AS day_value ,
                            sr.target AS target , 
                            sr.bonuses AS bonuses , 
                            sr.advances AS advances , 
                            sr.absent_days AS absent_days , 
                            sr.deduction_days AS deduction_days , 
                            sr.total AS total ,
                            sr.created_at AS created_at,
                            MONTH(sr.created_at) AS month ,
                            YEAR(sr.created_at) AS year
                            FROM instructors i 
                            JOIN salary_records sr ON i.id = sr.instructor_id
                            WHERE i.id = :id
                            ORDER BY created_at DESC");
    $stmt->execute([':id' => $agentId]);
    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

    return $instructor;
}

function getAgentById($agentId, $pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            * 
                        FROM `instructors` i
                        JOIN branch_instructor ON i.id = branch_instructor.instructor_id
                        WHERE id = :id");
    $stmt->execute([':id' => $agentId]);
    $instructor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($instructor) || $instructor[0]['role'] == 'owner') {
        include "not_found.php";
        exit();
    }

    return $instructor;
}

$errors = $_SESSION['error'] ?? [];

?>
<div id="agent-id" data-agent-id="<?= $agentId ?>"></div>
<div class="p-3 md:p-3 flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
    <div>
        <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Edit <span class="text-blue-600"><?= ucwords($agent[0]['username']) ?></span>'s Salary </h3>
    </div>
    <a href="customer-service.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
        <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
        </svg>
        <span class="w-full">Back</span>
    </a>
</div>

<div class="p-3 md:p-3 grid grid-cols-2 md:grid-cols-6  gap-1 md:w-fit w-full">
    <div class="w-full text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-bonus" data-modal-target="add-bonus-modal" data-modal-toggle="add-bonus-modal">
            <i class="fa-solid fa-award mr-3 text-base"></i>
            إضافة مكافأة
        </a>
    </div>
    <div class="w-full text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-rose-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-deduction_days" data-modal-target="add-deduction-modal" data-modal-toggle="add-deduction-modal">
            <i class="fa-solid fa-rectangle-list mr-3 text-base"></i>
            إضافة خصم
        </a>
    </div>
    <div class="w-full text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-advances" data-modal-target="add-advances-modal" data-modal-toggle="add-advances-modal">
            <i class="fa-solid fa-envelope-circle-check mr-3 text-base"></i>
            إضافة سلفة
        </a>
    </div>
    <div class="w-full text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-absent" data-modal-target="add-absent-modal" data-modal-toggle="add-absent-modal">
            <i class="fa-solid fa-money-check-dollar mr-3 text-base"></i>
            إضافة غياب
        </a>
    </div>
    <div class="w-full text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-overtime" data-modal-target="add-overtime-modal" data-modal-toggle="add-overtime-modal">
            <i class="fa-solid fa-check-to-slot mr-3 text-base"></i>
            إضافة أوفر تايم
        </a>
    </div>

    <div class="w-full text-white bg-fuchsia-700 hover:bg-fuchsia focus:ring-4 focus:outline-none focus:ring-fuchsia font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-target" data-modal-target="add-target-modal" data-modal-toggle="add-target-modal">
            <i class="fa-solid fa-users-viewfinder mr-3 text-base"></i>
            إضافة تارجت
        </a>
    </div>

</div>

<!-- action="functions/Customer-service/insert_salary.php" -->
<form method="post" action="functions/Customer-service/insert_salary.php" class="max-w-8xl mx-auto p-3 rounded-lg">
    <div class="gap-5 grid grid-cols-1  md:grid-cols-2 lg:grid-cols-3">
        <!-- الاسم -->
        <input type="hidden" value="<?= $agent[0]['username'] ?>" name="cs_name">
        <input type="hidden" value="<?= $agent[0]['id'] ?>" name="instructor_id">
        <input type="hidden" value="<?= $agentRecores['created_at'] ?>" name="created_at">

        <!-- الايام  -->
        <div>
            <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">شهر المحاسبة</label>
            <select name="created_at" id="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 font-bold">
                <option value="" selected>Choose a Month</option>
            </select>
            <?php if (isset($errors['created_at'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['created_at'] .
                    '</div>';
            }
            ?>
        </div>
        <!-- المرتب الأساسي -->
        <div>
            <label for="basic_salary" class="block mb-2 text-sm font-medium text-gray-900">المرتب الأساسي</label>
            <input type="number" id="basic_salary" value="<?= floor($agentRecores['basic_salary'] ?? 4500) ?>" name="basic_salary" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['basic_salary'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['basic_salary'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- أوفر تايم+مكافأت -->
        <div>
            <label for="overtime_days" class="block mb-2 text-sm font-medium text-gray-900">أوفر تايم + مكافأت (بالأيام)</label>
            <input type="number" value="<?= $agentRecores['overtime_days'] ?? 0 ?>" id="overtime_days" name="overtime_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['overtime_days'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['overtime_days'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- قيمة اليوم -->
        <div>
            <label for="day_value" class="block mb-2 text-sm font-medium text-gray-900">قيمة اليوم</label>
            <input type="number" value="<?= $agentRecores['day_value'] ?? 0 ?>" placeholder="Auto Generated with Salary" id="day_value" name="day_value" class="calc-field bg-gray-50 border border-gray-300 text-blue-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['day_value'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['day_value'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- التارجت -->
        <div>
            <label for="target" class="block mb-2 text-sm font-medium text-gray-900">التارجت</label>
            <input type="number" value="<?= floor($agentRecores['target'] ?? 0) ?>" id="target" name="target" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['target'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['target'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- المكافآت -->
        <div>
            <label for="bonuses" class="block mb-2 text-sm font-medium text-gray-900">المكافآت</label>
            <input type="number" value="<?= floor($agentRecores['bonuses'] ?? 0) ?>" id="bonuses" name="bonuses" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['bonuses'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['bonuses'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- السلف -->
        <div>
            <label for="advances" class="block mb-2 text-sm font-medium text-gray-900">السلف</label>
            <input type="number" value="<?= floor($agentRecores['advances'] ?? 0) ?>" id="advances" name="advances" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['advances'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['advances'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- الغياب -->
        <div>
            <label for="absent_days" class="block mb-2 text-sm font-medium text-gray-900">الغياب (بالأيام)</label>
            <input type="number" value="<?= $agentRecores['absent_days'] ?? 0 ?>" id="absent_days" name="absent_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['absent_days'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['absent_days'] .
                    '</div>';
            }
            ?>
        </div>

        <!-- خصم -->
        <div>
            <label for="deduction_days" class="block mb-2 text-sm font-medium text-gray-900">خصم (بالأيام)</label>
            <input type="number" value="<?= $agentRecores['deduction_days'] ?? 0 ?>" id="deduction_days" name="deduction_days" class="calc-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold tracking-wider">
            <?php if (isset($errors['deduction_days'])) {
                echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['deduction_days'] .
                    '</div>';
            }
            ?>
        </div>
    </div>
    <!-- الإجمالي -->
    <div class="flex flex-col items-end mt-10">
        <!-- Stylish result display -->
        <div
            class="bg-blue-50 text-blue-700 font-extrabold text-3xl px-6 py-3 border-b-2 border-blue-500 w-full" dir="rtl">
            <div class="flex gap-4 items-center mb-3">
                <i class="fa-solid fa-vault text-sm"></i>
                <label class="block ml-7 text-base font-semibold text-gray-900">الإجمالي</label>
            </div>
            <span id="totalDisplayBox"></span>
        </div>

    </div>
    <!-- Submit Button -->
    <div class="flex flex-col md:flex-row justify-between items-center">
        <button type="submit" class="mt-5 w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  sm:w-auto px-5 py-2.5 text-center flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            حفظ البيانات
        </button>

        <button
            type="submit"
            name="send_report"
            id="sendBtn"
            class="mt-5 w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 
            focus:ring-4 focus:outline-none focus:ring-blue-300 
            font-medium rounded-lg text-sm sm:w-auto px-5 py-2.5 text-center
            disabled:bg-blue-500 disabled:text-white disabled:cursor-not-allowed flex gap-2"
            onclick="setTimeout(() => this.disabled = true, 1)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
            </svg>
            ارسال التقرير
        </button>
    </div>
</form>

<?php
$selectedMonth = isset($agentRecores['month']) ? str_pad($agentRecores['month'], 2, '0', STR_PAD_LEFT) : false;
$selectedYear = $agentRecores['year'] ?? '';
$selectedValue = $selectedMonth ? "$selectedMonth-$selectedYear" : '';
?>


<?php

include_once "Design/Modals/Salary/insert_bonus.php";
include_once "Design/Modals/Salary/insert_deduction.php";
include_once "Design/Modals/Salary/insert_advances.php";
include_once "Design/Modals/Salary/insert_absent_days.php";
include_once "Design/Modals/Salary/insert_overtime_days.php";
include_once "Design/Modals/Salary/insert_target_modal.php";

?>

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

        const formatted = total.toFixed(0);
        // update the div instead of input
        document.getElementById("totalDisplayBox").textContent = formatted;
    }

    // run when page load
    calculateTotal();

    /** add months to select Month */
    const select = document.getElementById('month');
    let selectedValue = "<?= $selectedValue ?>";

    // set the modal create_at input
    sendDateToModal(selectedValue)

    const now = new Date();

    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1; // 1-based
    let nextMonth = currentMonth + 1;
    let nextMonthYear = currentYear;

    if (nextMonth === 13) {
        nextMonth = 1;
        nextMonthYear += 1;
    }

    const pad = (num) => num < 10 ? '0' + num : num;

    if (!selectedValue) {
        // Fallback to current month if not provided from PHP
        selectedValue = `${pad(currentMonth)}-${currentYear}`;
        // set the modal create_at input
        sendDateToModal(`${currentMonth}-${currentYear}`)
    }

    // Loop from Jan of current year to the next month (inclusive)
    let year = currentYear;
    let month = 1;

    while (year < nextMonthYear || (year === nextMonthYear && month <= nextMonth)) {
        const value = `${pad(month)}-${year}`;
        const option = document.createElement('option');
        option.value = value;
        option.classList.add('font-bold');
        option.textContent = `${month} - ${year}`;
        if (selectedValue && value === selectedValue) option.selected = true;
        select.appendChild(option);

        // Increment month/year
        month++;
        if (month > 12) {
            month = 1;
            year++;
        }
    }


    // ajax to get salary Records
    const monthSelect = document.getElementById("month");
    const agentId = document.getElementById('agent-id').dataset.agentId;

    monthSelect.addEventListener("change", async function() {
        const selected = this.value; // e.g. "07-2025"
        const [month, year] = selected.split("-");
        // set the modal create_at input on change date
        sendDateToModal(`${month}-${year}`);

        try {
            const url = `functions/Customer-service/get_salary_records.php?id=${agentId}&month=${month}&year=${year}`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: agentId,
                    month,
                    year
                })
            });

            if (!response.ok) throw new Error("Network response was not ok");

            const data = await response.json();

            // Fill fields
            if (data) {
                document.getElementById("basic_salary").value = data.basic_salary ?? 0;
                document.getElementById("overtime_days").value = data.overtime_days ?? 0;
                document.getElementById("day_value").value = data.day_value ?? 0;
                document.getElementById("target").value = data.target ?? 0;
                document.getElementById("bonuses").value = data.bonuses ?? 0;
                document.getElementById("advances").value = data.advances ?? 0;
                document.getElementById("absent_days").value = data.absent_days ?? 0;
                document.getElementById("deduction_days").value = data.deduction_days ?? 0;

                calculateTotal(); // Trigger calculation
            } else {
                alert("No salary data found for the selected month.");
            }
        } catch (error) {
            console.error("Fetch error:", error);
            alert("Failed to load salary data.");
        }
    });


    // send salary Month modal
    function sendDateToModal(dateVal) {
        let elements = document.querySelectorAll('.createAtDate');
        elements.forEach(elm => {
            elm.value = dateVal
        })

        // to span
        let spanElm = document.querySelectorAll('.month-target');
        spanElm.forEach(elm => {
            elm.innerText = dateVal
        })

        console.log(dateVal);

    }
</script>