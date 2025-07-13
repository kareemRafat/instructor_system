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