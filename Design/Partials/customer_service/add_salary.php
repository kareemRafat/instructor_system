<?php


if (!hasRole('owner', 'admin')) {
    include_once "not_found.php";
    exit();
}

if (!isset($_GET['month']) || !isset($_GET['year'])) {
    include_once "not_found.php";
    exit();
}

define('CREATED_AT', "{$_GET['month']}-{$_GET['year']}");

$agentId = $_GET['id'];
$agentRecords = getAgentSalaryRecords($agentId, $_GET['month'], $_GET['year'], $pdo);
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
<div id="created-at" data-created-at="<?= CREATED_AT ?>"></div>

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

<div class="p-3 flex flex-col md:flex-row gap-1 px-6">
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

<div class="min-h-screen p-2" id="the-big-div">
    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-lg shadow-md p-4 mb-3">
            <div
                class=" bg-sky-600 text-white rounded-md p-3 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold"><?= ucwords($agent['username']) ?></h2>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 text-sm">شهر المحاسبة</p>
                        <p class="text-lg font-semibold"><?= $_GET['month'] ?> - <?= $_GET['year'] ?></p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="month" class="block mb-2 text-sm font-medium text-gray-900 ">شهر المحاسبة</label>
                <select name="created_at" id="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold">
                    <option value="" selected>Choose a Month</option>
                </select>
                <?php if (isset($errors['created_at'])) {
                    echo '<div class="p-2 my-2 text-sm font-semibold text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                        $errors['created_at'] .
                        '</div>';
                }
                ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3  gap-3 mb-4" dir="rtl">
                <!-- المرتب الأساسي (Order 1) -->
                <div class="order-1 p-3 rounded-md border border-green-200">
                    <div class="flex items-center mb-1">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center ml-2">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">المرتب الأساسي</h3>
                    </div>
                    <p class="text-xl font-bold text-green-700"><?= $agentRecords['agent_salary'] ?></p>
                    <p class="text-sm text-green-600">جنيه مصري</p>
                </div>

                <!-- قيمة اليوم (Order 2) -->
                <div class="order-2 p-3 rounded-md border border-purple-200">
                    <div class="flex items-center mb-1">
                        <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center ml-2">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-8a7 7 0 1114 0 7 7 0 01-14 0zm7-3a1 1 0 012 0v.01c3.012.232 5 1.755 5 3.99 0 1.704-1.573 3.047-3.5 3.495V15a1 1 0 11-2 0v-.505C9.077 14.053 7.5 12.71 7.5 11c0-2.235 1.988-3.758 5-3.99V7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">قيمة اليوم</h3>
                    </div>
                    <p class="text-xl font-bold text-purple-700"><?= $agentRecords['day_value'] ?></p>
                    <p class="text-sm text-purple-600">جنيه مصري</p>
                </div>

                <!-- أوفر تايم + مكافأت (Order 3) -->
                <div
                    data-action="overtime"
                    <?php if ($agentRecords['overtime_days']): ?>
                    data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"
                    <?php endif; ?>
                    class="order-3 p-3 rounded-md border border-blue-200 cursor-pointer">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center ml-2">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">أوفر تايم + مكافأت</h3>
                        </div>
                        <?php if ($agentRecords['overtime_days']): ?>
                            <i class="fa-solid fa-circle-exclamation text-rose-700 text-base ml-1"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-xl font-bold text-blue-700"><?= $agentRecords['overtime_days'] ?? 0 ?></p>
                    <p class="text-sm text-blue-600">أيام</p>
                </div>

                <!-- التارجت (Order 4) -->
                <div class="order-4 p-3 rounded-md border border-orange-200">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center ml-2">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">التارجت</h3>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-orange-700 target-points"><?= $agentRecords['target'] ?? 0 ?></p>
                    <p class="text-sm text-orange-600">نقطة</p>
                </div>

                <!-- المكافآت (Order 5) -->
                <div
                    data-action="bonuses"
                    <?php if ($agentRecords['bonuses']): ?>
                    data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"
                    <?php endif; ?>
                    class="order-5 p-3 rounded-md border border-teal-500 cursor-pointer">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center ml-2">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">المكافآت</h3>
                        </div>
                        <?php if ($agentRecords['bonuses']): ?>
                            <i class="fa-solid fa-circle-exclamation text-rose-700 text-base ml-1"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-xl font-bold text-teal-700 bonuses-display"><?= $agentRecords['bonuses'] ?? 0 ?></p>
                    <p class="text-sm text-teal-600">جنيه مصري</p>
                </div>

                <!-- السلف (Order 6) -->
                <div
                    data-action="advances"
                    <?php if ($agentRecords['advances']): ?>
                    data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"
                    <?php endif; ?>
                    class="order-6 p-3 rounded-md border border-orange-400 cursor-pointer">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center ml-2">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">السلف</h3>
                        </div>
                        <?php if ($agentRecords['advances']): ?>
                            <i class="fa-solid fa-circle-exclamation text-rose-700 text-base ml-1"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-xl font-bold text-orange-700 advances-display"><?= $agentRecords['advances'] ?? 0 ?></p>
                    <p class="text-sm text-orange-600">جنيه مصري</p>
                </div>
            </div>

            <div class="bg-red-50 rounded-md p-3 mb-4 border border-red-200"
                dir="rtl">
                <h3 class="text-base font-bold text-red-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>الخصومات والغياب
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="bg-white p-2 rounded cursor-pointer"
                        data-action="absent_days"
                        <?php if ($agentRecords['absent_days']): ?>
                        data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"
                        <?php endif; ?>>
                        <div class="flex justify-between items-center">
                            <h4 class="font-semibold text-gray-700 mb-1 text-xs">الغياب</h4>
                            <?php if ($agentRecords['absent_days']): ?>
                                <i class="fa-solid fa-circle-exclamation text-rose-700 text-base ml-1"></i>
                            <?php endif; ?>
                        </div>
                        <p class="text-lg font-bold text-red-600">
                            <span class="absent-days-display"><?= $agentRecords['absent_days'] ?? 0 ?></span>
                            <span class="text-sm font-normal">أيام</span>
                        </p>
                    </div>
                    <div class="bg-white p-2 rounded cursor-pointer"
                        data-action="deduction_days"
                        <?php if ($agentRecords['deduction_days']): ?>
                        data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"
                        <?php endif; ?>>
                        <div class="flex justify-between items-center">
                            <h4 class="font-semibold text-gray-700 mb-1 text-xs">خصم</h4>
                            <?php if ($agentRecords['deduction_days']): ?>
                                <i class="fa-solid fa-circle-exclamation text-rose-700 text-base ml-1"></i>
                            <?php endif; ?>
                        </div>
                        <p class="text-lg font-bold text-red-600">
                            <span class="deduction-days-display"><?= $agentRecords['deduction_days'] ?? 0 ?></span>
                            <span class="text-sm font-normal">أيام</span>
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-sky-600 text-white rounded-lg p-4 text-center">
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
                    <?= ($agentRecords['calculated_total'] == '0.00' ? $agentRecords['agent_salary'] : $agentRecords['calculated_total']) ?? '0.00'  ?>
                </p>
                <p class="text-blue-200 text-sm">جنيه مصري</p>
            </div>
            <div class="flex flex-col md:flex-row justify-end gap-2 mt-4">
                <form method="post" action="functions/Salary/send_salary_report.php">
                    <input type="hidden" name="agent_id" value="<?= $agentId ?>">
                    <input type="hidden" name="cs_name" value="<?= $agentRecords['cs_name'] ?>">
                    <input type="hidden" name="email" value="<?= $agent['email'] ?>">
                    <input type="hidden" name="basic_salary" value="<?= $agentRecords['agent_salary'] ?>">
                    <input type="hidden" name="overtime_days" value="<?= $agentRecords['overtime_days'] ?>">
                    <input type="hidden" name="target" value="<?= $agentRecords['target'] ?>">
                    <input type="hidden" name="bonuses" value="<?= $agentRecords['bonuses'] ?>">
                    <input type="hidden" name="advances" value="<?= $agentRecords['advances'] ?>">
                    <input type="hidden" name="absent_days" value="<?= $agentRecords['absent_days'] ?>">
                    <input type="hidden" name="deduction_days" value="<?= $agentRecords['deduction_days'] ?>">
                    <input type="hidden" name="created_at" value="<?= CREATED_AT ?>">
                    <input type="hidden" name="total" value="<?= $agentRecords['calculated_total'] ?>">
                    <button
                        onclick="setTimeout(() => this.disabled = true, 1)"
                        class="flex items-center justify-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-md transition-colors shadow-sm md:disabled:bg-sky-500 disabled:text-white disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                        </svg>إرسال التقرير
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

include_once "Design/Modals/Salary/insert_bonus.php";
include_once "Design/Modals/Salary/insert_deduction.php";
include_once "Design/Modals/Salary/insert_advances.php";
include_once "Design/Modals/Salary/insert_absent_days.php";
include_once "Design/Modals/Salary/insert_overtime_days.php";
include_once "Design/Modals/Salary/insert_target_modal.php";
include_once "Design/Modals/Salary/show_reasons_drawer.php";

?>

<script>
    // Auto-calculate salary formula
    const fields = document.querySelectorAll(".calc-field");
    const totalField = document.getElementById("total");
    const dayValue = document.getElementById("day_value");

    const pad = num => num < 10 ? '0' + num : num;
    const select = document.getElementById("month");

    // Get query string month/year or fallback to current month
    const urlParams = new URLSearchParams(window.location.search);
    let queryMonth = parseInt(urlParams.get('month'), 10);
    let queryYear = parseInt(urlParams.get('year'), 10);

    const now = new Date();
    const currentMonth = now.getMonth() + 1;
    const currentYear = now.getFullYear();

    if (isNaN(queryMonth) || isNaN(queryYear)) {
        queryMonth = currentMonth;
        queryYear = currentYear;
    }

    const selectedValue = `${pad(queryMonth)}-${queryYear}`;
    sendDateToModal(selectedValue);

    // Generate options from Jan of current year to next month
    let year = currentYear;
    let month = 1;
    let nextMonth = currentMonth + 1;
    let nextYear = currentYear;
    if (nextMonth === 13) {
        nextMonth = 1;
        nextYear++;
    }

    while (year < nextYear || (year === nextYear && month <= nextMonth)) {
        const padded = pad(month);
        const value = `${padded}-${year}`;
        const option = document.createElement("option");
        option.value = value;
        option.textContent = `${month} - ${year}`;
        option.classList.add("font-bold");
        if (value === selectedValue) option.selected = true;
        select.appendChild(option);

        month++;
        if (month > 12) {
            month = 1;
            year++;
        }
    }

    // When a new month is selected, reload page with query params
    select.addEventListener("change", function() {
        const [m, y] = this.value.split("-");
        setQueryString(m, y);
    });

    // Update modal inputs/spans with selected date
    function sendDateToModal(dateVal) {
        document.querySelectorAll('.createAtDate').forEach(el => el.value = dateVal);
        document.querySelectorAll('.month-target').forEach(el => el.innerText = dateVal);
    }

    // Set URL query string and reload
    function setQueryString(month, year) {
        const url = new URL(window.location.href);
        url.searchParams.set('month', parseInt(month, 10));
        url.searchParams.set('year', parseInt(year, 10));
        window.location.href = url.toString(); // reload with new query string
    }




    /** END Drawer functionality */
</script>
<script src="dist/salary.js"></script>

<?php
// salary records Query
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
            COALESCE(sa.advances, 0) AS advances,
            COALESCE(sad.absent_days, 0) AS absent_days,
            COALESCE(sd.deduction_days, 0) AS deduction_days,
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
            (SELECT 
                 agent_id, 
                 SUM(amount) AS bonuses
             FROM salary_bonuses 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sb ON i.id = sb.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(amount) AS advances
             FROM salary_advances 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sa ON i.id = sa.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS absent_days
             FROM salary_absent_days 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sad ON i.id = sad.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS deduction_days
             FROM salary_deductions 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) sd ON i.id = sd.agent_id
        LEFT JOIN 
            (SELECT 
                 agent_id, 
                 SUM(days) AS overtime_days
             FROM salary_overtime_days 
             WHERE created_at >= :startDate 
             AND created_at < :endDate
             GROUP BY agent_id) so ON i.id = so.agent_id
        LEFT JOIN (
                SELECT 
                    SUM(target) AS target
                FROM salary_target
                WHERE MONTH(created_at) = :targetMonth AND YEAR(created_at) = :targetYear
            ) st ON 1=1
        WHERE 
            i.id = :agentId
    ");
    $stmt->execute([
        ':agentId' => $agentId,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
        ':targetMonth' => $month,
        ':targetYear' => $year
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<span data-drawer-target="reason-drawer" data-drawer-show="reason-drawer" aria-controls="reason-drawer"></span>