<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>

<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Customer Service</h1>
    <?php 

        if(!isset($_GET['action'])) {
            include_once("Design/Partials/customer_service/view.php");
        } elseif($_GET['action'] == 'edit') {
            include_once("Design/Partials/customer_service/edit.php");
        } else {
             include_once("Design/Partials/customer_service/not_found.php");
        }

    ?>
</div>

<?php 
if(!isset($_GET['action'])):

  include_once 'Design/Modals/insert_customer_service.php';

?>

<!-- Add this before closing body tag -->
<script src="dist/cs-main.js"></script>

<?php endif ; ?>


<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>


<?php

// branch indicator color
function branchIndicator($branch_name)
{
    $branch_name = strtolower($branch_name);
    $bgColors = [
        'tanta' => 'bg-teal-600',
        'mansoura' => 'bg-blue-600',
        'zagazig' => 'bg-purple-500',
        'default' => 'bg-orange-600'
    ];

    $textColors = [
        'tanta' => 'text-teal-600',
        'mansoura' => 'text-blue-700',
        'zagazig' => 'text-purple-700',
        'default' => 'text-orange-700'
    ];

    $bgClass = $bgColors[$branch_name] ?? $bgColors['default'];
    $textClass = $textColors[$branch_name] ?? $textColors['default'];

    return [
        'bgColor' => $bgClass,
        'textColor' => $textClass
    ];
}


?>