const agentId = document.getElementById("agent-id").dataset.agentId;
const createdAt = document.getElementById("created-at").dataset.createdAt;

const salaryPreload = document.getElementById("salary-preload");
const drawerList = document.getElementById("reason-list");
const actionBTN = document.getElementById("actionBTN");
const bonuses = document.querySelector('[data-action="bonuses"]');
const deductions = document.querySelector('[data-action="deduction_days"]');

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

function bonusCard(data) {
  return `<div class="relative pl-10 group">
    <!-- Animated Dot with pulse effect -->
    <div class="absolute left-0 top-4 w-3 h-3 bg-red-500 rounded-full ring-4 ring-red-100 group-hover:scale-125 transition-all duration-300 animate-pulse"></div>

    <div class="bg-white border border-gray-200 px-5 py-3 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-300 hover:-translate-y-0.5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full inline-flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                </svg>
                Bonus
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
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full inline-flex items-center">
                            Deduction
                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
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

// Helper function to capitalize first letter
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
