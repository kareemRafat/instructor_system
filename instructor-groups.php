<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';

?>

<div class="min-h-screen max-w-7xl mx-auto p-6 pb-20">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">My Groups</h1>
   
    <!-- include partials -->
    <?php
        include_once 'Design/Partials/Instructor-groups/view.php';
    ?>
</div>

<script src="dist/instructor-groups.js"></script>
</body>

</html>