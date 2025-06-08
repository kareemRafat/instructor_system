import { capitalizeFirstLetter } from "./helpers.js";

const monthSelect = document.getElementById("month-select");

// <option value="">May - 2025</option>

document.addEventListener("DOMContentLoaded", async function () {
  await getMonths();
});

/** get months */
async function getMonths() {
  try {
    const response = await fetch("functions/Bonus/get_months.php");
    const month = await response.json();

    monthSelect.innerHTML = "<option value=''>Select a Month</option>";
    if (month.data) {
      month.data.forEach((resData) => {
        const option = document.createElement("option");
        option.value = `${resData.month} - ${resData.year}`;
        option.textContent = `${resData.month} - ${resData.year}`;
        monthSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error fetching dates:", error);
    monthSelect.innerHTML = "<option value=''>Error loading dates</option>";
  }
}
