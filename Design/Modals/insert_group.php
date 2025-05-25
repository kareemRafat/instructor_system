    <?php
    $errors = $_SESSION['errors'] ?? [];
    ?>

    <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 ">
                        Create New Product
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center " data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="functions/Groups/insert_group.php" method="POST">
                    <input type="hidden" name="time" id="currentTime">
                    <script>
                        const now = new Date();
                        const time = now.toTimeString().split(' ')[0]; // Gets "HH:MM:SS"
                        document.getElementById('currentTime').value = time;
                    </script>

                    <div class="grid gap-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-600 block w-full p-2.5" placeholder="Group Name" required="">
                            <?php
                            if (isset($errors['name'])) {
                                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                    $errors['name'] .
                                    '</div>';
                            }
                            ?>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Date</label>
                            <div class="relative w-full mb-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input required name="date" id="datepicker-actions" datepicker datepicker-buttons datepicker-autoselect-today type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date">
                            </div>
                        </div>
                        <div class="col-span-1 mb-5">
                            <label for="grouptime" class="block mb-2 text-sm font-medium text-gray-900">Group time</label>
                            <select name="grouptime" id="grouptime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" required>
                                <option value="" selected="">Select Time</option>
                                <option value="10">10</option>
                                <option value="12.30">12.30</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="6.10">6 - Online</option>
                                <option value="8">8 - Online</option>
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

                        <div class="col-span-2 sm:col-span-1">
                            <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
                            <select name="branch" id="branchesSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="" selected="">Select branch</option>
                            </select>
                            <?php
                            if (isset($errors['branch'])) {
                                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                    $errors['branch'] .
                                    '</div>';
                            }
                            ?>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="instructor" class="block mb-2 text-sm font-medium text-gray-900">Instructor</label>
                            <select name="instructor" id="instructorSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="" selected="">Select instructor</option>
                            </select>
                            <?php
                            if (isset($errors['instructor'])) {
                                echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
                                    $errors['instructor'] .
                                    '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="me-1 -ms-1 w-5 h-5 sm:hidden md:inline-block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Add new Group
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const instructorSelect = document.getElementById('instructorSelect');
        const branchesSelect = document.getElementById('branchesSelect');
        const modalBtn = document.querySelector('[data-modal-target="crud-modal"]');

        modalBtn.addEventListener('click', function() {
            fetchBranches();
        });

        branchesSelect.addEventListener('change', function() {
            fetchInstructors(this.value);
        });


        async function fetchInstructors(value) {
            let instructors = await fetch(`functions/Instructors/get_instructors.php?branch_id=${value}`)
            let res = await instructors.json();
            if (res.data) {
                instructorSelect.innerHTML = '<option value="" selected="">Select instructor</option>';
                res.data.forEach(instructor => {
                    let option = document.createElement('option');
                    option.value = instructor.id;
                    option.textContent = capitalizeFirstLetter(instructor.username);
                    instructorSelect.appendChild(option);
                });
            }
        }

        async function fetchBranches() {
            let brnaches = await fetch(`functions/Branches/get_branches.php`)
            let res = await brnaches.json();
            if (res.data) {
                branchesSelect.innerHTML = `<option value="" selected="">Select branch</option>`;
                res.data.forEach(branch => {
                    let option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = capitalizeFirstLetter(branch.name);
                    branchesSelect.appendChild(option);
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
    </script>