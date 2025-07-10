<?php


if (!hasRole('owner', 'admin')) {
    include_once "not_found.php";
    exit();
}

if (!isset($_GET['month']) || !isset($_GET['year'])) {
    include_once "not_found.php";
    exit();
}

// Validate query string month and year
// if ($_GET['month'] != date('n') || $_GET['year'] != date('Y')) {
//     include_once "not_found.php";
//     exit();
// }


$agentId = $_GET['id'];
$agentRecores = getAgentSalaryRecords($agentId, $_GET['month'], $_GET['year'], $pdo);
$agent = getAgentById($agentId, $pdo);

function getAgentById($agentId, $pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            * 
                        FROM `instructors` i
                        JOIN branch_instructor ON i.id = branch_instructor.instructor_id
                        WHERE id = :id");
    $stmt->execute([':id' => $agentId]);
    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($instructor) || $instructor['role'] == 'owner') {
        include "not_found.php";
        exit();
    }

    return $instructor;
}

$errors = $_SESSION['errors'] ?? [];

?>
<div id="agent-id" data-agent-id="<?= $agentId ?>"></div>
<div class="p-3 md:p-3 flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
    <div>
        <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Edit <span class="text-blue-600"><?= ucwords($agent['username']) ?></span>'s Salary </h3>
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
            إضافة خصم بالأيام
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
            إضافة غياب بالأيام
        </a>
    </div>
    <div class="w-full text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-overtime" data-modal-target="add-overtime-modal" data-modal-toggle="add-overtime-modal">
            <i class="fa-solid fa-check-to-slot mr-3 text-base"></i>
            إضافة أوفر تايم بالأيام
        </a>
    </div>

    <div class="w-full text-white bg-fuchsia-700 hover:bg-fuchsia focus:ring-4 focus:outline-none focus:ring-fuchsia font-medium rounded text-sm py-1 px-2 text-center">
        <a class="flex items-center cursor-pointer" id="add-target" data-modal-target="add-target-modal" data-modal-toggle="add-target-modal">
            <i class="fa-solid fa-users-viewfinder mr-3 text-base"></i>
            إضافة تارجت
        </a>
    </div>

</div>

<div class="min-h-screen p-2">
    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-lg shadow-md p-4 mb-3">
            <div
                class=" bg-blue-700 text-white rounded-md p-3 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold"><?= ucwords($agent['username']) ?></h2>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 text-sm">شهر المحاسبة</p>
                        <p class="text-lg font-semibold"><?= $agentRecores['month'] ?> - <?= $agentRecores['year'] ?></p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
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
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">

                <div dir="rtl"
                    class="p-3 rounded-md border border-blue-200">
                    <div class="flex items-center mb-1">
                        <div
                            class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">
                            أوفر تايم + مكافأت
                        </h3>
                    </div>
                    <p class="text-xl font-bold text-blue-700"><?= $agentRecores['overtime_days'] ?? 0 ?></p>
                    <p class="text-sm text-blue-600">أيام</p>
                </div>
                <div dir="rtl"
                    class=" p-3 rounded-md border border-purple-200">
                    <div class="flex items-center mb-1">
                        <div
                            class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-8a7 7 0 1114 0 7 7 0 01-14 0zm7-3a1 1 0 012 0v.01c3.012.232 5 1.755 5 3.99 0 1.704-1.573 3.047-3.5 3.495V15a1 1 0 11-2 0v-.505C9.077 14.053 7.5 12.71 7.5 11c0-2.235 1.988-3.758 5-3.99V7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">قيمة اليوم</h3>
                    </div>
                    <p class="text-xl font-bold text-purple-700"><?= $agentRecores['day_value'] ?></p>
                    <p class="text-sm text-purple-600">جنيه مصري</p>
                </div>
                <div dir="rtl"
                    class="p-3 rounded-md border border-green-200">
                    <div class="flex items-center mb-1">
                        <div dir="rtl"
                            class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">المرتب الأساسي</h3>
                    </div>
                    <p class="text-xl font-bold text-green-700"><?= $agentRecores['agent_salary'] ?></p>
                    <p class="text-sm text-green-600">جنيه مصري</p>
                </div>
                <div dir="rtl"
                    class="bg-gradient-to-br from-orange-50 to-orange-100 p-3 rounded-md border border-orange-200">
                    <div class="flex items-center mb-1">
                        <div
                            class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">التارجت</h3>
                    </div>
                    <p class="text-xl font-bold text-orange-700 target-points"><?= $agentRecores['target'] ?? 0 ?></p>
                    <p class="text-sm text-orange-600">نقطة</p>
                </div>
                <div dir="rtl"
                    class="p-3 rounded-md border border-teal-500">
                    <div class="flex items-center mb-1">
                        <div
                            class="w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">المكافآت</h3>
                    </div>
                    <p class="text-xl font-bold text-teal-700 bonuses-display"><?= $agentRecores['bonuses'] ?? 0 ?></p>
                    <p class="text-sm text-teal-600">جنيه مصري</p>
                </div>
                <div dir="rtl"
                    class="p-3 rounded-md border border-orange-400">
                    <div class="flex items-center mb-1">
                        <div
                            class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center ml-2">
                            <svg
                                class="w-3 h-3 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">السلف</h3>
                    </div>
                    <p class="text-xl font-bold text-orange-700 advances-display"><?= $agentRecores['advances'] ?? 0 ?></p>
                    <p class="text-sm text-orange-600">جنيه مصري</p>
                </div>
            </div>
            <div class="bg-red-50 rounded-md p-3 mb-4 border border-red-200" dir="rtl">
                <h3 class="text-base font-bold text-red-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>الخصومات والغياب
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-white p-2 rounded">
                        <h4 class="font-semibold text-gray-700 mb-1 text-xs">الغياب</h4>
                        <p class="text-lg font-bold text-red-600">
                            <span class="absent-days-display"><?= $agentRecores['absent_days'] ?? 0 ?></span>
                            <span class="text-sm font-normal">أيام</span>
                        </p>
                    </div>
                    <div class="bg-white p-2 rounded">
                        <h4 class="font-semibold text-gray-700 mb-1 text-xs">خصم</h4>
                        <p class="text-lg font-bold text-red-600">
                            <span class="deduction-days-display"><?= $agentRecores['deduction_days'] ?? 0 ?></span>
                            <span class="text-sm font-normal">أيام</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-blue-700 text-white rounded-lg p-4 text-center">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-lg font-bold">إجمالي الراتب</h3>
                </div>
                <p class="text-3xl font-extrabold mb-1 total-salary-value">
                    <?= ($agentRecores['calculated_total'] == '0.00' ? $agentRecores['agent_salary'] : $agentRecores['calculated_total']) ?? '0.00'  ?>
                </p>
                <p class="text-blue-200 text-sm">جنيه مصري</p>
            </div>
            <div class="flex flex-col md:flex-row justify-center gap-2 mt-4">
                <button
                    class="flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>حفظ البيانات</button><button
                    class="flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                    </svg>إرسال التقرير
                </button>
            </div>
        </div>
    </div>
</div>

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
    // Salary formula auto calculate
    const fields = document.querySelectorAll(".calc-field");
    const totalField = document.getElementById("total");
    const dayValue = document.getElementById("day_value");

    fields.forEach(field => {
        field.addEventListener("input", calculateTotal);
    });

    function getVal(id) {
        return parseFloat(document.getElementById(id).value) || 0;
    }

    function calculateTotal() {
        const basic = getVal("basic_salary");
        const overtimeDays = getVal("overtime_days");
        const dayVal = parseFloat(dayValue.value) || 0;

        const total = basic + (overtimeDays * dayVal);
        totalField.value = total.toFixed(2);
    }

    // Utility to pad single digit months with leading zero
    const pad = (num) => num < 10 ? '0' + num : num;

    // Prepare month <select>
    const select = document.getElementById("month");

    // Extract query string month and year (if exist)
    const urlParams = new URLSearchParams(window.location.search);
    const queryMonth = parseInt(urlParams.get('month'), 10);
    const queryYear = parseInt(urlParams.get('year'), 10);

    // Determine current and next month
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1; // JavaScript months are 0-based
    let nextMonth = currentMonth + 1;
    let nextMonthYear = currentYear;

    if (nextMonth === 13) {
        nextMonth = 1;
        nextMonthYear++;
    }

    // Set selectedValue (from query OR fallback from PHP)
    let selectedValue = null;
    if (!isNaN(queryMonth) && !isNaN(queryYear)) {
        selectedValue = `${pad(queryMonth)}-${queryYear}`;
    } else {
        // fallback to PHP variable (if present)
        selectedValue = "<?= $selectedValue ?>";
        if (!selectedValue || selectedValue === "<?= $selectedValue ?>") {
            selectedValue = `${pad(currentMonth)}-${currentYear}`;
        }
    }

    // Set modal date input
    sendDateToModal(selectedValue);

    // Loop from Jan of current year to next month (inclusive)
    let year = currentYear;
    let month = 1;

    while (year < nextMonthYear || (year === nextMonthYear && month <= nextMonth)) {
        const paddedMonth = pad(month);
        const value = `${paddedMonth}-${year}`;
        const option = document.createElement("option");
        option.value = value;
        option.classList.add("font-bold");
        option.textContent = `${month} - ${year}`;

        if (value === selectedValue) {
            option.selected = true;
        }

        select.appendChild(option);

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
        setQueryString(month, year)
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

    }

    // set query string 
    // Function to set query string parameters for month and year
    function setQueryString(currentMonth, currentYear) {
        // Ensure month and year are integers to avoid leading zeros
        const month = parseInt(currentMonth, 10);
        const year = parseInt(currentYear, 10);

        // Get current URL and query parameters
        const url = new URL(window.location.href);
        const params = url.searchParams;

        // Always update month and year when called (remove the hasMonth/hasYear check)
        params.set('month', month);
        params.set('year', year);

        // Update URL without reloading
        // history.pushState({}, '', url.toString());

        window.location.href = url.toString();
        // Return the updated parameters for further use
        return {
            month,
            year
        };
    }
</script>

<?php
function getAgentSalaryRecords($agentId, $month, $year, $pdo)
{
    $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
    $endDate = date('Y-m-d', strtotime("$startDate +1 month"));

    $stmt = $pdo->prepare("
        SELECT 
            i.username AS cs_name,
            i.role,
            i.salary AS agent_salary,
            COALESCE(so.overtime_days, 0) AS overtime_days,
            CEIL(COALESCE(i.salary / 30, 0)) AS day_value,
            COALESCE(st.target, 0) AS target,
            COALESCE(sb.bonuses, 0) AS bonuses,
            COALESCE(sb.bonus_reasons, '') AS bonus_reasons,
            COALESCE(sb.bonus_created_at_dates, '') AS bonus_created_at_dates,
            COALESCE(sa.advances, 0) AS advances,
            COALESCE(sa.advance_reasons, '') AS advance_reasons,
            COALESCE(sa.advances_created_at_dates, '') AS advances_created_at_dates,
            COALESCE(sad.absent_days, 0) AS absent_days,
            COALESCE(sad.absent_reasons, '') AS absent_reasons,
            COALESCE(sad.absent_created_at_dates, '') AS absent_created_at_dates,
            COALESCE(sd.deduction_days, 0) AS deduction_days,
            COALESCE(sd.deduction_reasons, '') AS deduction_reasons,
            COALESCE(sd.deductions_created_at_dates, '') AS deductions_created_at_dates,
            COALESCE(so.overtime_reasons, '') AS overtime_reasons,
            COALESCE(so.overtime_created_at_dates, '') AS overtime_created_at_dates,
            COALESCE(sr.total, 0) AS total,
            sr.created_at AS created_at,
            MONTH(sr.created_at) AS month,
            YEAR(sr.created_at) AS year,
            (
                COALESCE(i.salary, 0) +
                (COALESCE(so.overtime_days, 0) * CEIL(COALESCE(i.salary / 30, 0))) +
                COALESCE(st.target, 0) +
                COALESCE(sb.bonuses, 0) -
                COALESCE(sa.advances, 0) -
                (COALESCE(sad.absent_days, 0) * CEIL(COALESCE(i.salary / 30, 0))) -
                (COALESCE(sd.deduction_days, 0) * CEIL(COALESCE(i.salary / 30, 0)))
            ) AS calculated_total
        FROM 
            instructors i
        LEFT JOIN 
            (SELECT instructor_id, basic_salary, target, total, created_at
             FROM salary_records 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             LIMIT 1) sr ON i.id = sr.instructor_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(amount) AS bonuses,
                 GROUP_CONCAT(reason SEPARATOR ', ') AS bonus_reasons,
                 GROUP_CONCAT(bonus_created_at SEPARATOR ', ') AS bonus_created_at_dates
             FROM salary_bonuses 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sb ON i.id = sb.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(amount) AS advances,
                 GROUP_CONCAT(reason SEPARATOR ', ') AS advance_reasons,
                 GROUP_CONCAT(advances_created_at SEPARATOR ', ') AS advances_created_at_dates
             FROM salary_advances 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sa ON i.id = sa.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS absent_days,
                 GROUP_CONCAT(reason SEPARATOR ', ') AS absent_reasons,
                 GROUP_CONCAT(absent_created_at SEPARATOR ', ') AS absent_created_at_dates
             FROM salary_absent_days 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sad ON i.id = sad.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS deduction_days,
                 GROUP_CONCAT(reason SEPARATOR ', ') AS deduction_reasons,
                 GROUP_CONCAT(deductions_created_at SEPARATOR ', ') AS deductions_created_at_dates
             FROM salary_deductions 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sd ON i.id = sd.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS overtime_days,
                 GROUP_CONCAT(reason SEPARATOR ', ') AS overtime_reasons,
                 GROUP_CONCAT(overtime_created_at SEPARATOR ', ') AS overtime_created_at_dates
             FROM salary_overtime_days 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) so ON i.id = so.agent_id
        LEFT JOIN 
            (
                SELECT 
                    sum(target) AS target, 
                    MONTH(created_at) AS target_month, 
                    YEAR(created_at) AS target_year
                FROM salary_target
                WHERE created_at >= :startDate AND created_at < :endDate
                LIMIT 1
            ) st ON MONTH(sr.created_at) = st.target_month AND YEAR(sr.created_at) = st.target_year
        WHERE 
            i.id = :agentId
    ");
    $stmt->execute([
        ':agentId' => $agentId,
        ':startDate' => $startDate,
        ':endDate' => $endDate
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>