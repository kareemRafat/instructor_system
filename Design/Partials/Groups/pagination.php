<?php

// get count for pagination
$countQuery = "SELECT COUNT(*) AS total FROM `groups` WHERE is_active = 1 AND (:branch IS NULL OR `groups`.branch_id = :branch)";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute([
    ':branch' => isset($_GET['branch']) ? $_GET['branch'] : null
]);
$totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

?>


<nav aria-label="Page navigation example" class="flex flex-col md:flex-row md:justify-between m-4">
    <div class=" mb-5 md:mb-0">
        Showing
        <?= $pageNum + 1 ?>
        to
        <?= min($pageNum + $groupPerPage, $totalCount) ?>
        of
        <?= $totalCount ?> entries
    </div>
    <ul id="page-list" class="inline-flex -space-x-px text-sm">
        <li>
            <a href="?<?= isset($_GET['branch']) ? 'branch=' . $_GET['branch'] . '&' : '' ?>page=<?= (isset($_GET['page']) && intval($_GET['page']) > 1) ? intval($_GET['page']) - 1 : 1 ?>"
                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700
        <?= (!isset($_GET['page']) || intval($_GET['page']) <= 1) ? 'pointer-events-none bg-red-100 opacity-50' : '' ?>">
                Previous
            </a>
        </li>
        <?php
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $totalPages = ceil($totalCount / $groupPerPage);
        $maxVisiblePages = 6; // Number of pages to show at once

        // Calculate start and end of page range
        $half = floor($maxVisiblePages / 2);
        $startPage = max(1, $currentPage - $half);
        $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

        // Adjust if we're at the end
        if ($endPage - $startPage < $maxVisiblePages - 1) {
            $startPage = max(1, $endPage - $maxVisiblePages + 1);
        }

        // Show first page and ellipsis if needed
        if ($startPage > 1) {
            echo '<li>
                <a data-page="1" href="?page=1" class="page-num flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">1</a>
            </li>';
            if ($startPage > 2) {
                echo '<li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300">...</li>';
            }
        }

        // Show page range
        for ($i = $startPage; $i <= $endPage; $i++):
        ?>
            <li>
                <a href="?page=<?= $i ?>"
                    data-page="<?= $i ?>"
                    class="page-num flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700
                    <?= $i == $currentPage ? 'bg-blue-50 text-blue-600 border-blue-300' : '' ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php
        // Show last page and ellipsis if needed
        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300">...</li>';
            }
            echo '<li>
                <a data-page="' . $totalPages . '" href="?page=' . $totalPages . '" class="page-num flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">' . $totalPages . '</a>
            </li>';
        }
        ?>
        <li>
            <a href="?<?= isset($_GET['branch']) ? 'branch=' . $_GET['branch'] . '&' : '' ?>page=<?= min($currentPage + 1, $totalPages) ?>"
                data-page="<?= $currentPage ?>"
                class="page-num-next flex items-center justify-center px-3 h-8 leading-tight text-gray-500 border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700
        <?= $currentPage >= $totalPages ? 'pointer-events-none bg-red-100 opacity-50' : '' ?>">
                Next
            </a>
        </li>
    </ul>
</nav>