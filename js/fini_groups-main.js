import {
  capitalizeFirstLetter,
  getQueryString,
  globalWait,
} from "./helpers.js";

const tbody = document.querySelector("tbody");
const page = getQueryString("page");
const branchVal = getQueryString("branch");
const pageList = document.getElementById("page-list");
const instructorSelect = document.getElementById("instructor-select");
const branchSelect = document.getElementById("branchSelect");

document.addEventListener("DOMContentLoaded", function () {
  // get branches when page loaded
  fetch("functions/Branches/get_branches.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        branchSelect.innerHTML = `<option value="" selected>Select a Branch</option>`; // Clear previous cards
        res.data.forEach((branch) => {
          let option = document.createElement("option");
          option.value = branch.id;
          option.textContent = capitalizeFirstLetter(branch.name);
          if (option.value == branchVal) {
            option.selected = true;
          }
          branchSelect.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));

  // when page load get instructors based on branch
  fetchInstructors(branchVal);

  /** instructor select */
  instructorSelect.addEventListener("change", function (e) {
    const instructorId = this.value;
    const instructorName = this.selectedOptions[0]?.text;

    let url = "";

    if (instructorId) {
      url = `functions/Groups/get_finished_groups.php?instructor_id=${encodeURIComponent(
        instructorId
      )}&branch_id=${branchVal}`;
    } else {
      url = `functions/Groups/get_finished_groups.php?branch_id=${branchVal}`;
    }

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        setTable(data);
      })
      .catch((error) => console.error("Error:", error));
  });

  // Event listener for select dropdown
  branchSelect.addEventListener("change", (e) => {
    const selectedBranch = e.target.value;

    // Remove 'page' from query string when changing branch
    const url = new URL(window.location);
    url.searchParams.delete("page");

    if (selectedBranch) {
      url.searchParams.set("branch", selectedBranch);
    } else {
      url.searchParams.delete("branch");
    }

    window.location = url;
  });

});

/** setTable */
function setTable(res, branch = null) {
  tbody.innerHTML = ""; // Clear current table content

  if (res.data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-6 py-3.5 font-bold bg-white" colspan="9"> No Group Found </td>
        </tr>
    `;
  }

  res.data.forEach((row) => {
    
    let hasBonus = null ;
    if(row.has_bonus) {
      hasBonus = `<i class="fa-solid fa-square-check text-green-600 mr-2"></i> <span class="text-green-600">Has Bonus`;
    }else {
      hasBonus = `<i class="fa-solid fa-square-xmark text-red-600 mr-2"></i> No Bonus Granted`
    }

    const tr = document.createElement("tr");
    tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";

    tr.innerHTML = `
      <th scope="row" class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">
          ${row.group_name.charAt(0).toUpperCase() + row.group_name.slice(1)}
      </th>
      <th scope="row" class="px-4 py-3.5 font-medium text-pink-900 whitespace-nowrap">
      <i class="fa-solid fa-clock mr-1.5"></i>
          ${
            row.group_time == 2 || row.group_time == 5
              ? `${row.group_time} - Friday`
              : row.group_time
          }
      </th>
      <th scope="row" class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">
      <span class="${dayBadgeColor(
        row.group_day
      )} text-sm font-medium me-2 px-2.5 py-1.5 rounded-md">${
      row.group_day
    }</span></th>
      <td class="px-4 py-3.5 text-sky-600 capitalize">
        ${capitalizeFirstLetter(row.track)}
      </td>
      <td class="px-4 py-3.5">
      <span class="w-2 h-2 ${
        branchIndicator(row.branch_name)["bgColor"]
      } inline-block mr-2"></span>
          ${
            row.instructor_name.charAt(0).toUpperCase() +
            row.instructor_name.slice(1)
          }
      </td>
      <td class="px-4 py-3.5 ${branchIndicator(row.branch_name)["textColor"]}">
        <div class="flex flex-row justify-start items-center">
          <svg class=" w-5 h-5 mr-1.5  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
          </svg>
          ${row.branch_name.charAt(0).toUpperCase() + row.branch_name.slice(1)}
        </div>
      </td> 
      <td class="px-4 py-3.5">
          <span class="block text-rose-700">${row.month}</span>
          ${row.formatted_date ? row.formatted_date : "No date added"}
      </td>     
      <td class="px-4 py-3.5">
          <span class="text-purple-700">${row.group_end_month}</span>
          <br>
          ${row.group_end_date}
      </td>      
      <td class="px-4 py-3.5">
          ${hasBonus}
      </td>
  `;

    tbody.appendChild(tr);
  });
}

/** Fetch instructors based on selected branch */
async function fetchInstructors(branchId) {
  try {
    const response = await fetch(
      `functions/Instructors/get_instructors.php?branch_id=${branchId}`
    );
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const res = await response.json();

    instructorSelect.innerHTML = !branchId
      ? "<option value=''>Select Branch First</option>"
      : "<option value=''>Choose Instructor</option>";

    if (res.data) {
      res.data.forEach((instructorData) => {
        const option = document.createElement("option");
        option.value = instructorData.id;
        option.textContent = capitalizeFirstLetter(instructorData.username);
        instructorSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error fetching instructors:", error);
    instructorSelect.innerHTML =
      "<option value=''>Error loading instructors</option>";
  }
}

/** branch indicator color */
function branchIndicator(branchName) {
  branchName = branchName.toLowerCase();

  const bgColors = {
    tanta: "bg-teal-600",
    mansoura: "bg-blue-600",
    zagazig: "bg-purple-500",
    default: "bg-orange-600",
  };

  const textColors = {
    tanta: "text-teal-600",
    mansoura: "text-blue-700",
    zagazig: "text-purple-700",
    default: "text-orange-700",
  };

  const bgClass = bgColors[branchName] || bgColors["default"];
  const textClass = textColors[branchName] || textColors["default"];

  return {
    bgColor: bgClass,
    textColor: textClass,
  };
}

/** day badge color */
function dayBadgeColor(dayName) {
  dayName = dayName.toLowerCase();

  const colors = {
    saturday: "bg-orange-100 text-orange-600 border border-orange-300",
    sunday: "bg-blue-100 text-blue-700 border border-blue-300",
    monday: "bg-pink-100 text-pink-700 border border-pink-300",
    default: "bg-zinc-100 text-zinc-700 border border-zinc-300",
  };

  return colors[dayName] || colors["default"];
}
