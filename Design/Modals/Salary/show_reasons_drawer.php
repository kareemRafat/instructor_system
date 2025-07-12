<div id="reason-drawer"
    class="fixed top-0 left-0 z-50 h-screen overflow-y-auto transition-transform -translate-x-full bg-white w-full md:w-[500px] shadow-xl"
    tabindex="-1" aria-labelledby="drawer-label">

    <!-- Loading Overlay (shown by default) -->
    <section id="salary-preload" class="absolute inset-0 z-50 flex items-center justify-center bg-sky-500">
        <div class="text-white font-semibold tracking-wider text-4xl md:text-5xl flex flex-col items-center justify-center gap-5">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </section>


    <!-- Header with gradient background -->
    <div class="sticky top-0 z-10 bg-blue-500 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-lg bg-white/20 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                </div>
                <h2 id="drawer-label" class="text-xl font-bold text-white">
                    Salary
                </h2>
            </div>
            <button type="button" data-drawer-hide="reason-drawer" aria-controls="reason-drawer"
                class="text-white hover:bg-white/20 rounded-full p-2 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                </svg>
                <span class="sr-only">Close drawer</span>
            </button>
        </div>
    </div>

    <!-- Content Area -->

    <div class="p-6">
        <!-- Filter Chips -->
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                </svg>
                All
            </button>
            <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800 flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span id="actionBTN" class="font-semibold"></span>
            </button>

        </div>

        <!-- Reasons Timeline List -->
        <div class="space-y-4 relative">
            <!-- Timeline vertical line -->
            <div class="absolute left-5 top-0 h-full border-l-2 border-blue-100"></div>

            <!-- Example Bonus Item -->
            <div id="reason-list" class="space-y-3" dir="rtl">

            </div>

            <!-- Example Deduction Item -->
            <!-- <div class="relative pl-10 group">
                <div class="absolute left-0 top-3 w-3 h-3 bg-red-500 rounded-full ring-4 ring-red-100  group-hover:scale-125 transition-transform duration-200"></div>

                <div class="bg-white  border border-gray-200 p-4 shadow-xs hover:shadow-sm transition-shadow duration-200 hover:border-red-200 ">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Deduction
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">-$120.00</span>
                    </div>
                    <h3 class="text-gray-800 dark:text-gray-100 font-medium mb-1">Unauthorized Absence</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">
                        Instructor was absent for 3 days without prior notice.
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>July 1, 2025</span>
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            System
                        </span>
                    </div>
                </div>
            </div> -->

            <!-- Add more items dynamically... -->
        </div>
    </div>

    <!-- Footer -->
    <div class="sticky bottom-0 bg-white  border-gray-200 p-4">
        <div class="flex justify-end space-x-3">
            <button type="button" data-drawer-hide="reason-drawer"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                Close Drawer
                <svg class="w-4 h-4 ms-2 rtl:rotate-180" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </button>
        </div>
    </div>
</div>