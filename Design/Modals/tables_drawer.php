<!-- drawer component -->
<div id="drawer-left-example" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-full md:w-[700px]" tabindex="-1" aria-labelledby="drawer-left-label">
   <h5 id="drawer-left-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">

   </h5>
   <button type="button" data-drawer-hide="drawer-left-example" aria-controls="drawer-left-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center">
      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
      </svg>
      <span class="sr-only">Close menu</span>
   </button>
   <div class="drawer-body my-6 w-full">
      <div class=" mx-auto bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
         <!-- Header -->
         <div class="p-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 flex items-center text-lg">
               <i class="fas fa-calendar-alt text-indigo-500 mr-2 text-lg"></i>
               <p id="drawerGroup" class="mr-2 text-indigo-800 text-lg"> </p>
               <p>Details</p>
            </h3>
         </div>

         <!-- Details List -->
         <div class="divide-y divide-gray-100">
            <!-- Group -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 mr-3 group-hover:bg-indigo-100 transition-colors">
                  <i class="fas fa-layer-group text-indigo-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Group</p>
                  <p class="font-medium text-gray-800 text-lg" id="drawerGroup2">
                     <i class="fa-solid fa-spinner fa-spin"></i>
                  </p>
               </div>
            </div>

            <!-- Track -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-teal-50 mr-3 group-hover:bg-teal-100 transition-colors">
                  <i class="fa-solid fa-bolt text-teal-600 text-sm"></i>
               </div>
               <div class="flex-1 flex justify-between items-center">
                  <div>
                     <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Track</p>
                     <p class="font-medium text-gray-800 text-lg" id="drawerTrack">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                     </p>
                  </div>
                  <div id="langIcon">
                     <!-- injected with js -->
                  </div>
               </div>
            </div>

            <!-- Time -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-50 mr-3 group-hover:bg-amber-100 transition-colors">
                  <i class="fas fa-clock text-amber-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Time</p>
                  <p class="font-medium text-gray-800" id="drawerTime">
                     <i class="fa-solid fa-spinner fa-spin"></i>
                  </p>
               </div>
            </div>

            <!-- Day -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-50 mr-3 group-hover:bg-slate-100 transition-colors">
                  <i class="fas fa-calendar-day text-slate-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Day</p>
                  <p class="font-medium text-gray-800" id="drawerDay">
                     <i class="fa-solid fa-spinner fa-spin"></i>
                  </p>
               </div>
            </div>

            <!-- Instructor -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 mr-3 group-hover:bg-purple-100 transition-colors">
                  <i class="fas fa-user-tie text-purple-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Instructor</p>
                  <p class="font-medium text-gray-800" id="drawerInstructor">
                     <i class="fa-solid fa-spinner fa-spin"></i>
                  </p>
               </div>

            </div>

            <!-- Branch -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 mr-3 group-hover:bg-green-100 transition-colors">
                  <i class="fas fa-location-dot text-green-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Branch</p>
                  <p class="font-medium text-gray-800" id="drawerBranch">
                     <i class="fa-solid fa-spinner fa-spin"></i>
                  </p>
               </div>
            </div>

            <!-- Start Date -->
            <div class="flex justify-between hover:bg-gray-50 transition-all duration-200 ">
               <div class="flex items-center p-4 group">
                  <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 mr-3 group-hover:bg-blue-100 transition-colors">
                     <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                     <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Start Date</p>
                     <p class="font-medium text-rose-700" id="drawerStartMonth">

                     </p>
                     <p class="font-medium text-gray-800" id="drawerStartDate">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                     </p>
                  </div>
               </div>
               <!-- now -->
               <div class="hidden md:flex items-center text-right p-4 hover:bg-gray-50 transition-all duration-200 group">
                  <div id="today" class="font-medium text-gray-800">
                     <!-- inject by js -->
                  </div>
                  <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-cyan-50 ml-6 mr-3 group-hover:bg-cyan-100 transition-colors">
                     <i class="fa-solid fa-calendar text-blue-600 text-sm"></i>
                  </div>
               </div>
            </div>

            <!-- End Date -->
            <div class="flex justify-between hover:bg-gray-50 transition-all duration-200">
               <div class="flex items-center p-4 group">
                  <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-cyan-50 mr-3 group-hover:bg-cyan-100 transition-colors">
                     <i class="fas fa-calendar-check text-cyan-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                     <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Expected End Date</p>
                     <p class="font-medium text-purple-700" id="drawerEndMonth">

                     </p>
                     <p class="font-medium text-gray-800" id="drawerEndDate">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                     </p>
                  </div>
               </div>
               <!-- time left medium -->
               <div class="hidden md:flex items-center text-right p-4 hover:bg-gray-50 transition-all duration-200 group">
                  <div id="time-left" class="font-medium text-gray-800">
                     <!-- inject by js -->
                  </div>
                  <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-rose-50 ml-6 mr-3 group-hover:bg-rose-100 transition-colors">
                     <i class="fa-solid fa-calendar-week text-rose-600 text-sm"></i>
                  </div>
               </div>
            </div>

            <!-- time left small -->
            <div class="md:hidden flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-rose-50 mr-3 group-hover:bg-rose-100 transition-colors">
                  <i class="fa-solid fa-calendar-week text-rose-600 text-sm"></i>
               </div>
               <div id="time-left2" class="font-medium text-gray-800">
                  <!-- inject by js -->
               </div>
            </div>
         </div>

         <!-- Footer -->
         <div class="p-3 bg-gray-50 border-t border-gray-100 flex gap-3">
            <a id="edit-btn" href="" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
               <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <div class="self-center">
               <a id="finish-btn" href="" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                  <i class="fas fa-edit mr-1"></i> Finish
               </a>


               <a id="finish-training-btn" data-group-id="" href="" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                  <i class="fas fa-edit mr-1"></i> Finish
               </a>

            </div>
         </div>
      </div>
   </div>
</div>

<button id="floatClose" class="hidden md:hidden fixed bottom-1/2 right-4 translate-y-1/2 z-50" data-drawer-hide="drawer-left-example" aria-controls="drawer-left-example">
   <div class="relative">
      <!-- Main button -->
      <div class="w-12 h-12 rounded-full bg-blue-600 shadow-lg shadow-blue-500/50 flex items-center justify-center text-white transform transition-all hover:scale-110 hover:bg-blue-700 active:scale-95">
         <i class="fa-solid fa-circle-chevron-left text-4xl"></i>
      </div>

      <!-- Optional pulse effect -->
      <div class="absolute inset-0 rounded-full bg-blue-600 opacity-0 group-hover:opacity-40 group-hover:animate-ping"></div>
   </div>
</button>
<?php
$_SESSION['page'] = 'tables.php';
?>


<script>
   const closeBtn = document.querySelector("#floatClose");

   closeBtn.addEventListener('click', function(e) {
      this.classList.add('hidden');
   })
</script>