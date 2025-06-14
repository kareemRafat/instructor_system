<?php

$pageName = basename($_SERVER['PHP_SELF']);

function hasRole(...$roles)
{
    return in_array(ROLE, $roles);
}
?>

<!-- navbar -->
<nav class="bg-white border-gray-200">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 gap-4">
        <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="flex justify-between self-center text-xl font-semibold whitespace-nowrap">
                <span class="w-8 h-8 mt-0.5 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M2.25 6a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V6Zm3.97.97a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 0 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Zm4.28 4.28a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                    </svg>
                </span>
                Instructors System
            </span>
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
                class="font-medium flex md:items-center flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white">
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm md:bg-transparent md:text-blue-700 md:p-0"
                        aria-current="page"></a>
                </li>
                <!-- team drop down -->
                <li>
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 px-3 <?= $pageName == 'instructors.php' || $pageName == 'customer-service.php' ? 'text-blue-600' : 'text-gray-900' ?> rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto <?= ROLE !== 'admin' && ROLE !== 'cs-admin' && ROLE !== 'owner' ? 'hidden' : '' ?>">Team <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                        <ul class="py-2 text-base font-semibold text-gray-700" aria-labelledby="dropdownLargeButton">
                            <?php
                            if (hasRole('owner', 'admin')) {
                            ?>
                                <li>
                                    <a href="instructors.php"
                                        class=" <?= $pageName == 'instructors.php' ? 'text-blue-600' : '' ?> block px-4 py-2 hover:bg-gray-100">
                                        <i class="fa-solid fa-person-chalkboard text-blue-700"></i>
                                        Instructors
                                    </a>
                                </li>
                            <?php } ?>
                            <?php
                            if (hasRole('admin', 'cs-admin', 'owner')) {
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
                <?php if (hasRole('admin', 'owner')) { ?>
                    <li>
                        <a href="bonus.php"
                            class=" <?= $pageName == 'bonus.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-medal"></i>
                            Bonus
                        </a>
                    </li>
                <?php } ?>
                <?php
                if (hasRole('admin', 'cs-admin', 'cs', 'owner')) {
                ?>
                    <li>
                        <a href="lectures.php"
                            class=" <?= $pageName == 'lectures.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-code"></i>
                            Lectures
                        </a>
                    </li>
                    <li>
                        <a href="tables.php"
                            class=" <?= $pageName == 'tables.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-star"></i>
                            Tables
                        </a>
                    </li>
                <?php } ?>

                <?php
                if (hasRole('admin', 'cs', 'cs-admin', 'owner')) {
                ?>
                    <li>
                        <a href="groups.php"
                            class=" <?= $pageName == 'groups.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-user-group text-sm"></i>
                            Groups
                        </a>
                    </li>
                <?php } ?>
                <?php
                if (hasRole('admin', 'instructor', 'owner')) {
                ?>
                    <li>
                        <a href="instructor-groups.php"
                            class=" <?= $pageName == 'instructor-groups.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-chalkboard-user"></i>
                            My Groups
                        </a>
                    </li>
                    <li>
                        <a href="index.php"
                            class=" <?= $pageName == 'index.php' ? 'text-blue-600' : '' ?> block py-2 px-3 rounded-sm md:text-center hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">
                            <i class="text-blue-700 fa-solid fa-square-check"></i>
                            Add Lecture</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="functions/Auth/logout.php"
                        class="block py-2 px-3 text-red-700 rounded-sm md:text-center hover:bg-gray-200 md:hover:bg-transparent md:border-0 md:hover:text-orange-500 md:p-0">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>