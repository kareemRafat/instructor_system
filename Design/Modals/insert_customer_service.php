<?php
$errors = $_SESSION['errors'] ?? [];
?>

<div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Add New Agent
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="functions/Customer-service/insert_cs.php" method="POST">
                <div class="grid gap-4 mb-4">
                    <div class="col-span-2"> <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                        <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent username" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['username'] : '' ?>">
                        <?php if (isset($errors['username'])) {
                            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                $errors['username'] .
                                '</div>';
                        }
                        ?>
                    </div>
                    <div class="col-span-2">
                        <?php include_once "Design/Components/password-input.php"; ?>
                    </div>
                    <div class="col-span-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent email" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['email'] : '' ?>">
                        <?php if (isset($errors['email'])) {
                            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                $errors['email'] .
                                '</div>';
                        }
                        ?>
                    </div>
                    <div class="col-span-2">
                        <label for="salary" class="block mb-2 text-sm font-medium text-gray-900">Salary</label>
                        <input type="text" name="salary" id="salary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter agent salary" required value="<?= isset($_SESSION['old']) ? $_SESSION['old']['salary'] : '' ?>">
                        <?php if (isset($errors['salary'])) {
                            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                $errors['salary'] .
                                '</div>';
                        }
                        ?>
                    </div>
                    <div class="col-span-1">
                        <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
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
                    <?php if (hasRole('admin', 'owner')): ?>
                        <div class="col-span-1">
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
                    <svg class="me-1 -ms-1 w-5 h-5 hidden md:inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add Agent
                </button>
            </form>
        </div>
    </div>
</div>