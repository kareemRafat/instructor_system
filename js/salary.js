/** Drawer functionality */
// const bigDiv = document.querySelectorAll('#the-big-div [data-action]');
// bigDiv.forEach(div => {
//     div.addEventListener('click', function() {
//         const clickedAction = div.dataset.action ;

//         //? hide all the reasons first
//         //! show the reason of what you click data-action
//     })
// })

const drawerList = document.getElementById("reason-list");
const actionBTN = document.getElementById('actionBTN');
const bonuses = document.querySelector('[data-action="bonuses"]');

bonuses.addEventListener("click", async function () {
  const agentId = this.dataset.agentId;
  const createdAt = this.dataset.createdAt;
  const action = this.dataset.action;

  actionBTN.innerText = capitalizeFirstLetter(action) ;


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
      result.bonuses.forEach((bonus) => {
        drawerList.innerHTML += bonusCard(bonus);
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

    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-300 hover:-translate-y-0.5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full inline-flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                </svg>
                Bonus
            </span>
            <span class="text-lg font-semibold text-blue-600">${data.amount} جنيه</span>
        </div>
        
        <h3 class="text-gray-900 font-semibold text-base mb-2 line-clamp-2">
            ${data.reason}
        </h3>
        
        <hr class="border-gray-100 my-3">
        
        <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
            <span class="flex items-center gap-1 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                ${data.bonus_created_at.slice(0, 10)}
            </span>
        </div>
    </div>
</div>`;
}




// Helper function to capitalize first letter
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}