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
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<div class="p-4 md:p-5 flex justify-end">
    <a href="instructors.php" class="text-white inline-flex items-center bg-orange-400 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fas fa-backward me-2"></i>
        Back
    </a>
</div>


<div>
    <h1 class="mb-10 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl">Edit <span class="text-blue-600"><?= $instructor[0]['username'] ?></span>'s info </h1>
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
        <div class="col-span-2"> <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter password if you want to Change it ...">
            <?php if (isset($errors['password'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['password'] .
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