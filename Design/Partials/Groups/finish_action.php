<?php

$groupId = $_GET['group_id'];
$group = getGroupById($groupId, $pdo);
$errors = $_SESSION['errors'] ?? [];

function getGroupById($groupId, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM `groups` WHERE id = :id");
    $stmt->execute([':id' => $groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$group) {
        include "not_found.php";
        exit();
    }

    return $group ;
}
?>
<div class="p-3 md:p-3 flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
    <div>
        <h1 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl">Finish <span class="text-blue-600"><?= $group['name'] ?></span> Group </h1>
    </div>
    <a href="<?= isset($_SESSION['page']) ? $_SESSION['page'] : 'groups.php'; ?>" class="inline-flex items-center self-end justify-center p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
        <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
        </svg>
        <span class="w-full">Back</span>
    </a>
</div>

<form class="p-4 md:p-5" action="functions/Groups/finish_group.php" method="POST">
    <input type="hidden" name="group_name" value="<?= $group['name'] ?>">
    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">



    <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
        <!-- date picker -->
        <div class="my-1">
            <label class="block mb-2 text-sm font-medium text-gray-900">End Date</label>
            <div class="relative w-full mb-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input required autocomplete="off" name="finish_date" id="datepicker-actions" datepicker datepicker-buttons datepicker-autoselect-today type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date">
            </div>
        </div>

        <div class="my-1">
            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900">Total Students:</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-2.5 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.4472 4.10557c-.2815-.14076-.6129-.14076-.8944 0L2.76981 8.49706l9.21949 4.39024L21 8.38195l-8.5528-4.27638Z" />
                        <path d="M5 17.2222v-5.448l6.5701 3.1286c.278.1325.6016.1293.8771-.0084L19 11.618v5.6042c0 .2857-.1229.5583-.3364.7481l-.0025.0022-.0041.0036-.0103.009-.0119.0101-.0181.0152c-.024.02-.0562.0462-.0965.0776-.0807.0627-.1942.1465-.3405.2441-.2926.195-.7171.4455-1.2736.6928C15.7905 19.5208 14.1527 20 12 20c-2.15265 0-3.79045-.4792-4.90614-.9751-.5565-.2473-.98098-.4978-1.27356-.6928-.14631-.0976-.2598-.1814-.34049-.2441-.04036-.0314-.07254-.0576-.09656-.0776-.01201-.01-.02198-.0185-.02991-.0253l-.01038-.009-.00404-.0036-.00174-.0015-.0008-.0007s-.00004 0 .00978-.0112l-.00009-.0012-.01043.0117C5.12215 17.7799 5 17.5079 5 17.2222Zm-3-6.8765 2 .9523V17c0 .5523-.44772 1-1 1s-1-.4477-1-1v-6.6543Z" />
                    </svg>
                </div>
                <input type="number" name="total_students" id="number-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " placeholder="25" required />
                <?php if (isset($errors['total_students'])) {
                    echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                        $errors['total_students'] .
                        '</div>';
                }
                ?>
            </div>
        </div>
        <div class="my-1">
            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900">unpaid Students</label>
            <input type="number" name="unpaid_students" id="number-input" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="5" required />
            <?php if (isset($errors['unpaid_students'])) {
                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                    $errors['unpaid_students'] .
                    '</div>';
            }
            ?>
        </div>
        <div class="my-1">
            <button
                type="submit"
                onclick="return confirm('Are you sure you want to finish this group?');"
                class="sm:mt-5 md:mt-0 md:max-w-fit text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                <i class="fa-solid fa-pen-to-square mr-2"></i>
                Finish Group
            </button>
        </div>
    </div>
</form>
