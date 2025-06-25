 <div class=" min-h-screen max-w-8xl mx-auto md:px-6 py-6 pb-20">
     <!-- Track Header -->
     <div class=" flex flex-col-reverse md:flex-row justify-between md:items-center gap-3">
         <div>
             <h3 class="text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl"><span class="text-blue-600"><?= ucwords('Hover') ?></span> Group </h3>
         </div>
         <a href="groups.php" class="inline-flex items-center justify-center self-end p-2 text-base font-medium text-gray-500 rounded-lg bg-gray-100 hover:text-gray-900 hover:bg-gray-200">
             <svg class="w-4 h-4 me-2 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
             </svg>
             <span class="w-full">Back</span>
         </a>
     </div>
     <p class="text-sm text-gray-500 mb-4">Instructor: Ahmed Hossam</p>

     <!-- track Name -->
     <div class="bg-gray-50 py-4 rounded-md  mb-6">
         <h2 class="text-xl font-semibold text-gray-800 mb-2">
             <i class="fa-solid fa-bolt"></i>
             <span>Javascript</span>
         </h2>

         <!-- Comments Row -->
         <div class="grid gird-cols-1 lg:grid-cols-2">

             <!-- single comment -->
             <div class="flex items-start md:items-center justify-between flex-col gap-4 md:flex-row bg-white py-4 md:p-4 mb-2 rounded shadow-sm border border-slate-400 hover:bg-gray-50 relative">
                 <div class="flex md:items-center gap-3 ml-3 md:ml-0">
                     <div class="flex gap-4 items-center">
                         <i class="hidden md:inline-block fa-solid fa-comment text-slate-600"></i>
                         <p class="font-medium text-sm">"Good understanding of Eloquent relationships"</p>
                     </div>
                 </div>
                 <!-- data md -->
                 <div class="hidden md:flex items-center gap-3">
                     <div class="flex items-center gap-4 self-end text-slate-600">
                         <i class="text-sm fa-solid fa-calendar-week"></i>
                         <span class="text-base font-semibold">2025-06-23</span> |
                         <span class="text-base font-semibold">
                             3 days ago
                         </span>
                     </div>
                 </div>

                 <!-- date sm -->
                 <div class="w-fit flex md:hidden items-end justify-between flex-col gap-4 md:flex-row bg-white px-4 py-1 mb-2 rounded shadow-sm hover:bg-gray-50 border border-slate-400 absolute -bottom-8 right-2">
                     <div class="flex items-center gap-3">
                         <div class="flex items-center gap-2 self-end text-slate-600">
                             <i class="text-xs fa-solid fa-calendar-week"></i>
                             <span class="text-sm font-semibold">2025-06-23</span> |
                             <span class="text-sm font-semibold">3 days ago</span>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- end single comment -->
         </div>
     </div>
 </div>