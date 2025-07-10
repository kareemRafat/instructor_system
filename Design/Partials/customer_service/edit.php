<?php

$instId = $_GET['instructor_id'];
$instructor = getInstructorById($instId, $pdo);
$branches = getBranches($pdo);
$errors = $_SESSION['errors'] ?? [];

function getInstructorById($instId, $pdo)
{
    $stmt = $pdo->prepare("SELECT 
                            * 
                        FROM `instructors` i
                        JOIN branch_instructor ON i.id = branch_instructor.instructor_id
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
    <a href="customer-service.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
        <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
        </svg>
        <span class="w-full">Back</span>
    </a>
</div>


<form class="p-4 md:p-5" action="functions/Customer-service/update_cs.php" method="POST">
    <input type="hidden" name="id" value="<?= $instId ?> ">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent username" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['username'] : $instructor[0]['username'] ?>">
            <?php if (isset($errors['username'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['username'] .
                    '</div>';
            }
            ?>
        </div>
        <div>
            <?php include_once "Design/Components/password-input.php"; ?>
        </div>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent email" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['email'] : $instructor[0]['email'] ?>">
            <?php if (isset($errors['email'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['email'] .
                    '</div>';
            }
            ?>
        </div>
        <div>
            <label for="salary" class="block mb-2 text-sm font-medium text-gray-900">Salary</label>
            <input type="number" name="salary" id="salary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent salary" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['salary'] : $instructor[0]['salary'] ?>">
            <?php if (isset($errors['salary'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['salary'] .
                    '</div>';
            }
            ?>
        </div>
        <div>
            <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
            <select name="branch" id="branchesSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                <option value="" selected="">Select branch</option>
                <?php foreach ($branches as $branch): ?>
                    <option
                        value="<?= $branch['id'] ?>"
                        <?= $branch['id'] === $instructor[0]['branch_id'] ? 'selected' : '' ?>>
                        <?= $branch['name'] ?>
                    </option>
                <?php endforeach; ?>

            </select>
            <?php if (isset($errors['branch'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['branch'] .
                    '</div>';
            }
            ?>
        </div>
        <?php if (hasRole('admin', 'owner')): ?>
            <div>
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                <select name="role" id="rolesSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option value="" selected="">Select Role</option>
                    <option value="owner">Owner</option>
                    <option value="cs-admin">Admin</option>
                    <option value="cs">Agent</option>
                </select>
                <?php if (isset($errors['role'])) {
                    echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                        $errors['role'] .
                        '</div>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fa-solid fa-pen-to-square mr-2"></i>
        update Agent
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


    // mark the agent role as selected
    document.querySelectorAll('#rolesSelect option').forEach(op => {
        if (op.value == `<?= $instructor[0]['role'] ?>`) {
            op.selected = true
        }
    })
</script>