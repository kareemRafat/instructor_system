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
    <a href="groups.php" class="text-white inline-flex items-center bg-amber-400 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fas fa-backward me-2"></i>
        Back
    </a>
</div>

<form class="p-4 md:p-5" action="functions/Groups/update_group.php" method="POST">
    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
    <input type="hidden" name="time" id="currentTime">
    <script>
        const nowDate = new Date();
        const nowTime = nowDate.toTimeString().split(' ')[0]; // Gets "HH:MM:SS"
        document.getElementById('currentTime').value = nowTime;
    </script>

    <div class="grid gap-4 grid-cols-2">
        <div class="col-span-1">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
            <input type="hidden" name="old_name" value="<?= $group['name'] ?>">
            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Group Name" required="" value="<?= $group['name'] ?>">
            <?php if (isset($errors['name'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['name'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900">Date m-d-y</label>
            <div class="relative w-full mb-2">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input name="date" datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date" value="<?= date('m/d/Y', strtotime($group['start_date'])); ?>">
            </div>
        </div>
        <div class="col-span-1">
            <label for="grouptime" class="block mb-2 text-sm font-medium text-gray-900">Group time</label>
            <select name="grouptime" id="grouptime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 font-semibold" required>
                <option value="" selected="">Select Time</option>
                <option value="10">10</option>
                <option value="12.30">12.30</option>
                <option value="3">3</option>
                <option value="6">6</option>
                <option value="8">Online</option>
                <option value="2">2 [ Friday ]</option>
                <option value="5">5 [ Friday ]</option>
            </select>
            <?php
            if (isset($errors['grouptime'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['grouptime'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-span-1 mb-5">
            <label for="groupDay" class="block mb-2 text-sm font-medium text-gray-900">Group Day</label>
            <select name="groupDay" id="groupDay" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" required>
                <option value="" selected="">Select Day</option>
                <option value="saturday">Saturday</option>
                <option value="sunday">Sunday</option>
                <option value="monday">Monday</option>
            </select>
            <?php
            if (isset($errors['groupDay'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['groupDay'] .
                    '</div>';
            }
            ?>
        </div>
    </div>
    <?php
    if (isset($errors['date'])) {
        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
            $errors['date'] .
            '</div>';
    }
    ?>
    <div class="grid gap-4 mb-4 grid-cols-2">

        <div class="col-span-2 sm:col-span-1"> <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
            <select name="branch" id="branchesSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                <option value="" selected="">Select branch</option>
            </select>
            <?php if (isset($errors['branch'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['branch'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-span-2 sm:col-span-1"> <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900">Instructor</label>
            <select name="instructor" id="instructorSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                <option value="" selected="">Select instructor</option>
            </select>
            <?php if (isset($errors['instructor'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['instructor'] .
                    '</div>';
            }
            ?>
        </div>
    </div>
    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        <i class="fa-solid fa-pen-to-square mr-2"></i>
        Update Group
    </button>
</form>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        // get branches when page loaded
        fetch("functions/Branches/get_branches.php")
            .then((response) => response.json())
            .then((res) => {
                if (res.status == "success") {
                    branchesSelect.innerHTML = `<option value="" selected>Select a Branch</option>`;
                    res.data.forEach((branch) => {
                        let option = document.createElement("option");
                        option.value = branch.id;
                        if (branch.id == <?= $group['branch_id'] ?>) {
                            option.setAttribute('selected', 'true');
                        }
                        option.textContent = capitalizeFirstLetter(branch.name);
                        branchesSelect.appendChild(option);
                    });
                }
            })
            .catch((error) => console.error("Error fetching lectures:", error));
    })

    branchesSelect.addEventListener('change', function() {
        fetchInstructors(this.value);
    });

    fetchInstructors(<?= $group['branch_id'] ?>);


    async function fetchInstructors(value) {
        let instructors = await fetch(`functions/Instructors/get_instructors.php?branch_id=${value}`)
        let res = await instructors.json();
        if (res.data) {
            instructorSelect.innerHTML = '<option value="" selected="">Select instructor</option>';
            res.data.forEach(instructor => {
                let option = document.createElement('option');
                option.value = instructor.id;
                if (instructor.id == <?= $group['instructor_id'] ?>) {
                    option.setAttribute('selected', 'true');
                }
                option.textContent = capitalizeFirstLetter(instructor.username);
                instructorSelect.appendChild(option);
            });
        }
    }

    /** helper functions */
    function capitalizeFirstLetter(value) {
        if (typeof value !== "string" || value.length === 0) {
            return value;
        }
        return value.charAt(0).toUpperCase() + value.slice(1);
    }

    // select the same date as the group date
    const selectOption = document.querySelectorAll('#grouptime option');
    selectOption.forEach(option => {
        if (option.value == <?= $group['time'] ?>) {
            option.setAttribute('selected', 'true');
        }
    });

    // select the same date as the group date
    const days = document.querySelectorAll('#groupDay option');
    days.forEach(option => {
        if (option.value === `<?= $group['day'] ?>`) {
            option.setAttribute('selected', 'true');
        }
    });
</script>