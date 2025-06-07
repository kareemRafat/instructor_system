<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>
<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Instructors</h1>
    <?php 

        if(!isset($_GET['action'])) {
            include_once("Design/Partials/Instructors/view.php");
        } elseif($_GET['action'] == 'edit') {
            include_once("Design/Partials/Instructors/edit.php");
        } else {
             include_once("Design/Partials/Instructors/not_found.php");
        }

    ?>
</div>

<?php 
if(!isset($_GET['action'])):

    include_once 'Design/Modals/insert_instructor.php'; 

?>

<!-- Add this before closing body tag -->
<script type="module" src="dist/instructors-main.js"></script>

<?php endif ; ?>

<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>