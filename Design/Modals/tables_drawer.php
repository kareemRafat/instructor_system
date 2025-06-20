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
                  <p class="font-medium text-gray-800 text-lg" id="drawerGroup2">Neon Online</p>
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
                     <p class="font-medium text-gray-800 text-lg" id="drawerTrack">javascript</p>
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
                  <p class="font-medium text-gray-800" id="drawerTime">Online 8</p>
               </div>
            </div>

            <!-- Day -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-50 mr-3 group-hover:bg-slate-100 transition-colors">
                  <i class="fas fa-calendar-day text-slate-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Day</p>
                  <p class="font-medium text-gray-800" id="drawerDay">Sunday</p>
               </div>
            </div>

            <!-- Instructor -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 mr-3 group-hover:bg-purple-100 transition-colors">
                  <i class="fas fa-user-tie text-purple-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Instructor</p>
                  <p class="font-medium text-gray-800" id="drawerInstructor">Esraa</p>
               </div>

            </div>

            <!-- Branch -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 mr-3 group-hover:bg-green-100 transition-colors">
                  <i class="fas fa-location-dot text-green-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Branch</p>
                  <p class="font-medium text-gray-800" id="drawerBranch">Mansoura</p>
               </div>
            </div>

            <!-- Start Date -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 mr-3 group-hover:bg-blue-100 transition-colors">
                  <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Start Date</p>
                  <p class="font-medium text-rose-700" id="drawerStartMonth">25-06-2025</p>
                  <p class="font-medium text-gray-800" id="drawerStartDate">25-06-2025</p>
               </div>
            </div>

            <!-- End Date -->
            <div class="flex items-center p-4 hover:bg-gray-50 transition-all duration-200 group">
               <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-cyan-50 mr-3 group-hover:bg-cyan-100 transition-colors">
                  <i class="fas fa-calendar-check text-cyan-600 text-sm"></i>
               </div>
               <div class="flex-1">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Expected End Date</p>
                  <p class="font-medium text-purple-700" id="drawerEndMonth">09-12-2025</p>
                  <p class="font-medium text-gray-800" id="drawerEndDate">09-12-2025</p>
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
<?php 
   $_SESSION['page'] = 'tables.php';
?>
