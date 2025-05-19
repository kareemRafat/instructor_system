<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';

$errors = $_SESSION['errors'] ?? [];

?>

<div class="max-w-7xl mx-auto pt-6 px-6">
    <h1 class="text-3xl font-bold mb-6 text-center tracking-wide text-blue-500">
        <?= ucwords(USERNAME) ?>
    </h1>
    <!-- card -->
    <div class="flex flex-col items-center justify-center my-8 text">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Groups</h2>
            <form action="functions/Lectures/insert_lecture.php" method="POST">
                <input type="hidden" name="date" id="currentDate">
                <script>
                    const now = new Date();
                    const formatted = now.toLocaleString('sv-SE').replace('T', ' '); // 'YYYY-MM-DD HH:mm:ss'
                    document.getElementById('currentDate').value = formatted;
                </script>
                <div class="mb-4">
                    <label for="group" class="block text-sm font-medium text-gray-600">Group</label>
                    <select id="group" name="group" class="form-select block w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">Select Group</option>
                    </select>
                    <?php
                    if (isset($errors['group'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['group'] .
                            '</div>';
                    }
                    ?>
                </div>
                <div class="mb-7">
                    <label for="track" class="block text-sm font-medium text-gray-600">Track</label>
                    <select id="track" name="track" class="form-select block w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-400 focus:border-blue-400">
                        <option value="1">HTML</option>
                        <option value="2">CSS</option>
                        <option value="3">JavaScript</option>
                        <option value="4">PHP</option>
                        <option value="5">MySQL</option>
                        <option value="6">Project</option>
                    </select>
                    <?php
                    if (isset($errors['track'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['group'] .
                            '</div>';
                    }
                    ?>
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <input name="comment" type="text" id="floating_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?= $_SESSION['old']['comment'] ?? '' ?>" />
                        <label for="floating_outlined" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Comment</label>
                    </div>
                    <?php
                    if (isset($errors['comment'])) {
                        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert"> ' .
                            $errors['comment'] .
                            '</div>';
                    }
                    ?>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Submit</button>
            </form>
        </div>
        <div class="w-full max-w-md mt-3 text-xl text-blue-600 text-right">
            <i class="fa-solid fa-fire mr-1"></i>
            <a class="hover:underline" href="https://tinyurl.com/createivo-track" target="_blank">Track</a>
        </div>
    </div>
</div>

<?php
unset($_SESSION['errors']);
?>

<script src="js/main.js"></script>

<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>

</html>