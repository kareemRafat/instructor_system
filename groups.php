<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';

?>

<div class="min-h-screen max-w-8xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Groups</h1>
   
    <!-- include partials -->
    <?php
     if(!isset($_GET['action'])) {
        include_once 'Design/Partials/Groups/view.php';
     } elseif($_GET['action'] == 'finished'){
        include_once 'Design/Partials/Groups/finished_view.php';
     }  elseif($_GET['action'] == 'edit') {
        include_once 'Design/Partials/Groups/edit.php';
     } elseif($_GET['action'] == 'finish_group') {
        include_once 'Design/Partials/Groups/finish_action.php';
     } else {
        include_once 'Design/Partials/Groups/not_found.php';
     }
    ?>
</div>



<?php
// Main modal -->

if(!isset($_GET['action'])):

    include_once 'Design/Modals/insert_group.php';

?>

<script type="module" src="dist/groups-main.js"></script>
<script type="module" src="dist/groups-pagination.js"></script>

<?php else : ?>

   <script type="module" src="js/fini_groups-main.js"></script>

<?php endif; ?>



<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>