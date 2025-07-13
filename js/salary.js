const agentId = document.getElementById("agent-id").dataset.agentId;
const createdAt = document.getElementById("created-at").dataset.createdAt;

const salaryPreload = document.getElementById("salary-preload");
const drawerList = document.getElementById("reason-list");
const actionBTN = document.getElementById("actionBTN");
const bonuses = document.querySelector('[data-action="bonuses"]');
const deductions = document.querySelector('[data-action="deduction_days"]');
const advances = document.querySelector('[data-action="advances"]');
const absentDays = document.querySelector('[data-action="absent_days"]');
const overtime = document.querySelector('[data-action="overtime"]');

/** bonus fetch functionality */
bonuses.addEventListener("click", async function () {
  const action = this.dataset.action;

  actionBTN.innerText = capitalizeFirstLetter(action);

  salaryPreload.classList.remove("hidden");
  drawerList.innerHTML = "";

  try {
    const response = await fetch("functions/Salary/fetch/get_bonuses.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `agent_id=${encodeURIComponent(
        agentId
      )}&created_at=${encodeURIComponent(createdAt)}`,
    });

    const result = await response.json();

    if (result.status === "success") {
      salaryPreload.classList.add("hidden");
      result.bonuses.forEach((bonus) => {
        drawerList.innerHTML += bonusCard(bonus);
      });
    }
  } catch (error) {
    alert("Request failed.");
    console.error(error);
  }
});

/** deductions functionality */
deductions.addEventListener("click", async function () {
  const action = this.dataset.action;
  actionBTN.innerText = capitalizeFirstLetter(action);
  salaryPreload.classList.remove("hidden");
  drawerList.innerHTML = "";

  try {
    const response = await fetch(
      "functions/Salary/fetch/get_deduction_days.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `agent_id=${encodeURIComponent(
          agentId
        )}&created_at=${encodeURIComponent(createdAt)}`,
      }
    );

    const result = await response.json();

    if (result.status === "success") {
      salaryPreload.classList.add("hidden");
      result.deductionDays.forEach((dedDay) => {
        drawerList.innerHTML += deductionCard(dedDay);
      });
    }
  } catch (error) {
    alert("Request failed.");
    console.error(error);
  }
});

/** advances functionality */
advances.addEventListener("click", async function () {
  const action = this.dataset.action;
  actionBTN.innerText = capitalizeFirstLetter(action);
  salaryPreload.classList.remove("hidden");
  drawerList.innerHTML = "";

  try {
    const response = await fetch("functions/Salary/fetch/get_advances.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `agent_id=${encodeURIComponent(
        agentId
      )}&created_at=${encodeURIComponent(createdAt)}`,
    });

    const result = await response.json();

    if (result.status === "success") {
      salaryPreload.classList.add("hidden");
      result.advances.forEach((adv) => {
        drawerList.innerHTML += advancesCard(adv);
      });
    }
  } catch (error) {
    alert("Request failed.");
    console.error(error);
  }
});

/** absent days functionality */
absentDays.addEventListener("click", async function () {
  const action = this.dataset.action;
  actionBTN.innerText = capitalizeFirstLetter(action);
  salaryPreload.classList.remove("hidden");
  drawerList.innerHTML = "";

  try {
    const response = await fetch(
      "functions/Salary/fetch/get_absent_days.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `agent_id=${encodeURIComponent(
          agentId
        )}&created_at=${encodeURIComponent(createdAt)}`,
      }
    );

    const result = await response.json();

    if (result.status === "success") {
      salaryPreload.classList.add("hidden");
      result.absentDays.forEach((absentDay) => {
        drawerList.innerHTML += absentCard(absentDay);
      });
    }
  } catch (error) {
    alert("Request failed.");
    console.error(error);
  }
});

/** overtime functionality */
overtime.addEventListener("click", async function () {
  const action = this.dataset.action;
  actionBTN.innerText = capitalizeFirstLetter(action);
  salaryPreload.classList.remove("hidden");
  drawerList.innerHTML = "";

  try {
    const response = await fetch(
      "functions/Salary/fetch/get_overtime.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `agent_id=${encodeURIComponent(
          agentId
        )}&created_at=${encodeURIComponent(createdAt)}`,
      }
    );

    const result = await response.json();

    if (result.status === "success") {
      salaryPreload.classList.add("hidden");
      result.overtime.forEach((ot) => {
        drawerList.innerHTML += overtimeCard(ot);
      });
    }
  } catch (error) {
    alert("Request failed.");
    console.error(error);
  }
});

function bonusCard(data) {
  return `<div class="relative pl-10 group">
    <!-- Animated Dot with pulse effect -->
    <div class="absolute left-0 top-4 w-3 h-3 bg-green-500 rounded-full ring-4 ring-green-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>

    <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-green-300 hover:-translate-y-0.5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full inline-flex items-center">
                <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                </svg>
                المكافآت
            </span>
            <span class="text-base font-semibold text-slate-600">${
              data.amount
            } جنيه</span>
        </div>
        
        <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
            ${data.reason}
        </h3>
        
        <hr class="border-gray-100 my-3">
        
        <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
            <span class="flex items-center gap-1 text-gray-800 font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                ${data.bonus_created_at.slice(0, 10)}
            </span>
        </div>
    </div>
</div>`;
}

function deductionCard(data) {
  return `<div class="relative pl-10 group">
                <!-- Animated Dot with pulse effect -->
                <div class="absolute left-0 top-4 w-3 h-3 bg-red-500 rounded-full ring-4 ring-red-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>

                <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-red-300 hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full inline-flex items-center">
                            <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            الخصومات
                        </span>
                        <span class="text-base font-semibold text-slate-600">${
                          data.days
                        } يوم</span>
                    </div>
                    
                    <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
                        ${data.reason}
                    </h3>
                    
                    <hr class="border-gray-100 my-3">
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1 text-gray-800 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            ${data.deductions_created_at.slice(0, 10)}
                        </span>
                    </div>
                </div>
            </div>`;
}

function advancesCard(data) {
  return `<div class="relative pl-10 group">
                <!-- Animated Dot with pulse effect -->
                <div class="absolute left-0 top-4 w-3 h-3 bg-orange-500 rounded-full ring-4 ring-orange-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>
                <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-orange-300 hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-orange-600 bg-red-50  px-2 py-0.5 rounded-full inline-flex items-center">
                            <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                             السلف
                        </span>
                        <span class="text-base font-semibold text-slate-600">${
                          data.amount
                        } جنيه</span>
                    </div>
                    
                    <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
                        ${data.reason}
                    </h3>
                    
                    <hr class="border-gray-100 my-3">
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1 text-gray-800 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            ${data.advances_created_at.slice(0, 10)}
                        </span>
                    </div>
                </div>
            </div>`;
}

function absentCard(data) {
  return `<div class="relative pl-10 group">
                <!-- Animated Dot with pulse effect -->
                <div class="absolute left-0 top-4 w-3 h-3 bg-red-500 rounded-full ring-4 ring-red-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>

                <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-red-300 hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-red-600 bg-red-50 p-2  py-0.5 rounded-full inline-flex items-center">
                            <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            الـغـيـاب
                        </span>
                        <span class="text-base font-semibold text-slate-600">${
                          data.days
                        } يوم</span>
                    </div>
                    
                    <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
                        ${data.reason}
                    </h3>
                    
                    <hr class="border-gray-100 my-3">
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1 text-gray-800 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            يوم الغياب : 
                            ${data.absent_created_at.slice(0, 10)}
                        </span>
                    </div>
                </div>
            </div>`;
}

function overtimeCard(data) {
  return `<div class="relative pl-10 group">
                <!-- Animated Dot with pulse effect -->
                <div class="absolute left-0 top-4 w-3 h-3 bg-blue-500 rounded-full ring-4 ring-blue-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>

                <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-300 hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 p-2  py-0.5 rounded-full inline-flex items-center">
                            <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            أوفر تايم + مكافأت
                        </span>
                        <span class="text-base font-semibold text-slate-600">${
                          data.days
                        } يوم</span>
                    </div>
                    
                    <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
                        ${data.reason}
                    </h3>
                    
                    <hr class="border-gray-100 my-3">
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1 text-gray-800 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            ${data.overtime_created_at.slice(0, 10)}
                        </span>
                    </div>
                </div>
            </div>`;
}

// Helper function to capitalize first letter
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
