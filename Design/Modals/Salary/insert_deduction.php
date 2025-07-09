<!-- Main modal -->
<div id="add-deduction-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm ">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 ">
                    <span class="text-blue-600 font-semibold"><?= ucwords($agent[0]['username']) ?></span>
                     إضافة خصومات بالأيام
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="add-deduction-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-4">
                <form method="post" class="space-y-2" action="functions/Salary/insert_deduction.php">
                    <input type="hidden" name="created_at" id="createAtDate2" value="">
                    <input type="hidden" name="id" id="agent-id" value="<?= $agentId ?>">
                    <div>
                        <label for="deduction" class="block mb-2 text-sm font-medium text-gray-900 ">أيام الخصم </label>
                        <input type="deduction" name="deduction" id="deduction" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " placeholder="Amount" required />
                    </div>
                    <div>
                        <label for="deduction-reason" class="block mb-2 text-sm font-medium text-gray-900 ">سبب الخصم </label>
                        <input type="text" name="deduction-reason" id="deduction-reason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " required />
                    </div>
                    <div>
                        <input
                            type="hidden"
                            name="deduction_created_at"
                            id="deduction_created_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            required />
                    </div>
                    <script>
                        // add date now to upove input
                        document.addEventListener("DOMContentLoaded", function() {
                            const input = document.getElementById("deduction_created_at");

                            const now = new Date();

                            const yyyy = now.getFullYear();
                            const mm = String(now.getMonth() + 1).padStart(2, '0');
                            const dd = String(now.getDate()).padStart(2, '0');
                            const hh = String(now.getHours()).padStart(2, '0');
                            const mi = String(now.getMinutes()).padStart(2, '0');
                            const ss = String(now.getSeconds()).padStart(2, '0');

                            const mysqlDatetime = `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`;

                            input.value = mysqlDatetime;
                        });
                    </script>
                    <button type="submit" class="w-full text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-rose-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">إضافة</button>
                </form>
            </div>
        </div>
    </div>
</div>