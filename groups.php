    <?php 
        include_once 'Design/includes/header.php';
        include_once 'Design/includes/navbar.php';
    ?>
    


    <div class="max-w-7xl mx-auto pt-6 px-6">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Groups</h1>
        <?php
        // Fetch all groups from the databaserequire_once "Database/connect.php";
        $query = "SELECT 
                        groups.name AS group_name,
                        instructors.username AS instructor_name,
                        branches.name AS branch_name 
                FROM groups 
                JOIN instructors ON groups.instructor_id = instructors.id 
                JOIN branches ON groups.branch_id = branches.id
                WHERE groups.is_active = 1
                ORDER BY groups.start_date DESC";

        include_once "Database/connect.php";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ?>

        <!-- Modal toggle -->
        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Add Group Modal
        </button>
        <br>
        <!-- table -->
        <div class="relative overflow-x-auto shadow-sm sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Group
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Instructor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Branch
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Finish</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $row) :
                    ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?= ucwords($row['group_name']) ?>
                            </th>
                            <td class="px-6 py-4">
                                <?= ucwords($row['instructor_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= ucwords($row['branch_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <button class="font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fas fa-ban mr-2"></i>Finish
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php 
        // Main modal -->
        include_once 'Design/Modals/insert_group.php';
    ?>
</body>

</html>