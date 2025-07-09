<!-- Main modal -->
<div id="add-absent-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm ">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <span class="text-blue-600 font-semibold mr-3 text-lg"><?= ucwords($agent[0]['username']) ?></span>
                <h3 class="w-full text-lg font-semibold text-gray-900 ">
                    <span>-</span>
                    إضافة أيام غياب
                    <span> لشهر </span>
                    <span class="text-red-700 font-semibold month-target"> 7-2025 </span>

                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="add-absent-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-4">
                <form method="post" class="space-y-2" action="functions/Salary/insert_absent_days.php">
                    <input type="hidden" name="created_at" class="createAtDate" value="">
                    <input type="hidden" name="id" id="agent-id" value="<?= $agentId ?>">
                    <div>
                        <label for="absent-days" class="block mb-2 text-sm font-medium text-gray-900 ">عدد ايام الغياب </label>
                        <input type="absent-days" name="absent_days" id="absent-days" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " placeholder="Amount" required />
                    </div>
                    <div>
                        <label for="absent-reason" class="block mb-2 text-sm font-medium text-gray-900 ">سبب الغياب </label>
                        <input type="text" name="absent-reason" id="absent-reason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " required />
                    </div>
                    <div>
                        <label for="absent-days" class="block mb-2 text-sm font-medium text-gray-900 ">يوم الغياب </label>
                        <input
                            type="date"
                            name="absent_created_at"
                            id="absent_created_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 my-4"
                            required />
                    </div>

                    <button type="submit" class="w-full text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">إضافة</button>
                </form>
            </div>
        </div>
    </div>
</div>