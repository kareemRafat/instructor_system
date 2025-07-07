<?php

$instId = $_GET['instructor_id'];
$instructor = getInstructorById($instId, $pdo);
$errors = $_SESSION['errors'] ?? [];


function getInstructorById($instId, $pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            * 
                        FROM `instructors` i
                        JOIN branch_instructor bi ON i.id = bi.instructor_id
                        WHERE id = :id");
    $stmt->execute([':id' => $instId]);
    $instructor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($instructor)) {
        include "not_found.php";
        exit();
    }

    return $instructor;
}

function getBranches($pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            id, name 
                        FROM branches b ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<div class="p-3 md:p-3 flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
    <div>
        <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Edit <span class="text-blue-600"><?= $instructor[0]['username'] ?></span>'s info </h3>
    </div>
    <a href="instructors.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
        <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
        </svg>
        <span class="w-full">Back</span>
    </a>
</div>




<form class="p-4 md:p-5" action="functions/Instructors/update_instructor.php" method="POST">
    <input type="hidden" name="id" value="<?= $instructor[0]['id'] ?>">
    <div class="grid gap-4 mb-4">
        <div class="col-span-2"> <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
            <input type="text" name="username" id="username" value="<?= $instructor[0]['username'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter instructor username" required>
            <?php if (isset($errors['username'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['username'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-span-2">
            <?php include "Design/Components/password-input.php"; ?>
        </div>
        <div class="col-span-2"> <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="text" name="email" id="email" value="<?= $instructor[0]['email'] ?? '' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter instructor Email" required>
            <?php if (isset($errors['email'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['email'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900">Branches</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2" id="branchesContainer">
                <!-- getBranches($pdo) -->
                <?php
                $instBranch = array_map(fn($instbr) => $instbr['branch_id'], $instructor);
                $branches = getBranches($pdo);
                $count = count($branches);
                $isOdd = $count % 2 !== 0;
                foreach ($branches as $i => $branch):
                    // if it is last iteration
                    $isLast = $i === $count - 1;
                    // full width if is odd
                    $fullWidth = $isOdd && $isLast;
                ?>
                    <div class="<?= $fullWidth ? 'col-span-2' : 'col-span-1' ?> md:col-span-1">
                        <label
                            class="flex items-center space-x-2 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input
                                type="checkbox"
                                name="branch_ids[]"
                                <?= in_array($branch['id'], $instBranch) ? 'checked' : '' ?>
                                value="<?= $branch['id'] ?>"
                                class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded border">
                            <span class="text-gray-900 text-sm"><?= $branch['name'] ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (isset($errors['branch'])): ?>
                <div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <?= $errors['branch'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fa-solid fa-pen-to-square mr-2"></i>
        update Instructor
    </button>
</form>




<script>
    /** helper functions */
    function capitalizeFirstLetter(value) {
        if (typeof value !== "string" || value.length === 0) {
            return value;
        }
        return value.charAt(0).toUpperCase() + value.slice(1);
    }
</script>