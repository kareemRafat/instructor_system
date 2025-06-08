<?php

$groupId = $_GET['group_id'];
$group = getGroupById($groupId, $pdo);
$errors = $_SESSION['errors'] ?? [];

function getGroupById($groupId, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `groups` WHERE id = :id");
    $stmt->execute([':id' => $groupId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="p-4 md:p-5 flex justify-end">
    <a href="groups.php" class="text-white inline-flex items-center bg-orange-400 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fas fa-backward me-2"></i>
        Back
    </a>
</div>

<form class="p-4 md:p-5" action="functions/Groups/finish_group.php" method="POST">
    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
    <input type="hidden" name="finist_date" id="currentDate">
    <script>
        const now = new Date();
        const date = now.toLocaleString("sv-SE").replace("T", " "); // 'YYYY-MM-DD HH:mm:ss'
        document.getElementById('currentDate').value = date;
    </script>

    <div>
        <h1 class="mb-10 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl">Finsih <span class="text-blue-600"><?= $group['name'] ?></span> Group </h1>
    </div>


    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
        <div>
            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900">Total Students</label>
            <input type="number" name="total_students" id="number-input" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="30" required />
            <?php if (isset($errors['total_students'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['total_students'] .
                    '</div>';
            }
            ?>
        </div>
        <div>
            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900">Total paid Students</label>
            <input type="number" name="unpaid_students" id="number-input" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="5" required />
            <?php if (isset($errors['unpaid_students'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['unpaid_students'] .
                    '</div>';
            }
            ?>
        </div>
    </div>

    <button
        type="submit"
        onclick="return confirm('Are you sure you want to finish this group?');"
        class="mt-4 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fa-solid fa-pen-to-square mr-2"></i>
        Finish Group
    </button>
</form>