<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';
?>
<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <?php 

        include_once("Design/Partials/Instructors/view.php");

    ?>
</div>

<?php include_once 'Design/Modals/insert_instructor.php'; ?>

<!-- Add this before closing body tag -->
<script type="module" src="dist/instructors-main.js"></script>

<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>