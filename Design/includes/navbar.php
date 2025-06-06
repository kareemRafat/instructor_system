<?php

$pageName = basename($_SERVER['PHP_SELF']);

?>

<!-- navbar -->
<nav class="bg-white border-gray-200">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="https://Createivo.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl font-semibold whitespace-nowrap">Createivo</span>
        </a>
        <button data-collapse-toggle="navbar-default" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white">
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm md:bg-transparent md:text-blue-700 md:p-0"
                        aria-current="page"></a>
                </li>
                <!-- team drop down -->
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 px-3 <?= $pageName == 'instructors.php' || $pageName == 'customer-service.php' ? 'text-blue-600' : 'text-gray-900' ?> rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto <?= ROLE !== 'admin' && ROLE !== 'cs-admin' ? 'hidden' : '' ?>">Team <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                        <ul class="py-2 text-base font-semibold text-gray-700" aria-labelledby="dropdownLargeButton">
                            <?php
                            if (ROLE === 'admin') {
                            ?>
                                <li>
                                    <a href="instructors.php"
                                        class=" <?= $pageName == 'instructors.php' ? 'text-blue-600' : '' ?> block px-4 py-2 hover:bg-gray-100">Instructors</a>
                                </li>
                            <?php } ?>
                            <?php
                            if (ROLE == 'admin' or ROLE == 'cs-admin') {
                            ?>
                                <li>
                                    <a href="customer-service.php"
                                        class=" <?= $pageName == 'customer-service.php' ? 'text-blue-600' : '' ?> block px-4 py-2 hover:bg-gray-100">Customer
                                        service</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>

                <?php
                if (ROLE === 'admin' or ROLE === 'cs-admin' or ROLE === 'cs') {
                ?>
                    <li>
                        <a href="lectures.php"
                            class=" <?= $pageName == 'lectures.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Lectures</a>
                    </li>
                    <li>
                        <a href="tables.php"
                            class=" <?= $pageName == 'tables.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Tables</a>
                    </li>
                <?php } ?>

                <?php
                if (ROLE == 'admin' or ROLE == 'cs' or ROLE == 'cs-admin') {
                ?>
                    <li>
                        <a href="groups.php"
                            class=" <?= $pageName == 'groups.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Groups</a>
                    </li>
                <?php } ?>
                <?php
                if (ROLE === 'admin' or ROLE === 'instructor') {
                ?>
                    <li>
                        <a href="instructor-groups.php"
                            class=" <?= $pageName == 'instructor-groups.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">My Groups</a>
                    </li>
                    <li>
                        <a href="index.php"
                            class=" <?= $pageName == 'index.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Add
                            Lecture</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="functions/Auth/logout.php"
                        class="block py-2 px-3 text-red-700 rounded-sm hover:bg-gray-200 md:hover:bg-transparent md:border-0 md:hover:text-orange-500 md:p-0">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>