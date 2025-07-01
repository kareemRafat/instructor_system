<?php
// get the previous page query string and redirect with the same query string in previous page when click back
$groupPage = isset($_SESSION['params']['groups']) ? "groups.php?" . $_SESSION['params']['groups'] : "groups.php";
$tablesPage = isset($_SESSION['params']['tables']) ? "tables.php?" . $_SESSION['params']['tables'] : "tables.php";
?>
<a href="<?= isset($_SESSION['page']) ? $tablesPage : $groupPage; ?>" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
    <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
    </svg>
    <span class="w-full">Back</span>
</a>