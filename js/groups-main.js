import { capitalizeFirstLetter, getQueryString , globalWait } from "./helpers.js";

const searchInput = document.getElementById("table-search");
const tbody = document.querySelector("tbody");
const page = getQueryString("page");
const branchVal = getQueryString("branch");
const pageList = document.getElementById("page-list");
const instructorSelect = document.getElementById("instructor-select");
// branchSelect const came from the modal in the same page

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

  // get groups total count when page load
  groupsTotalCount(getQueryString("branch"));

  // when page load get instructors based on branch
  fetchInstructors(branchVal);

  /** instructor select */
  instructorSelect.addEventListener("change", function () {
    const instructorId = this.value;
    // toggle pagination
    if (!instructorId) {
      pageList.classList.remove("hidden");
    } else {
      pageList.classList.add("hidden");
    }

    let url = "";

    if (instructorId) {
      url = `functions/Groups/get_groups.php?instructor_id=${encodeURIComponent(
        instructorId
      )}&branch_id=${branchVal}`;
    } else {
      url = `functions/Groups/get_groups.php?branch_id=${branchVal}`;
    }

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        setTable(data);
      })
      .catch((error) => console.error("Error:", error));
  });

  /** search functionality */
  searchInput.addEventListener("input", function () {
    const searchValue = this.value.trim();

    // reset instructor
    fetchInstructors(branchVal);

    let url = "";

    if (!searchValue) {
      pageList.classList.remove("hidden");
    } else {
      pageList.classList.add("hidden");
    }

    if (branchVal) {
      url = `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}&branch_id=${encodeURIComponent(branchVal)}${
        page ? `&page=${encodeURIComponent(page)}` : ""
      }`;
    } else {
      url = `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}${page ? `&page=${encodeURIComponent(page)}` : ""}`;
    }

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        setTable(data);
      })
      .catch((error) => console.error("Error:", error));
  });

  // Handle finish button clicks
  tbody.addEventListener("click", function (e) {
    if (e.target.closest(".finish-group-btn")) {
      const button = e.target.closest(".finish-group-btn");
      const groupId = button.dataset.groupId;

      if (confirm("Are you sure you want to finish this group?")) {
        finishGroup(groupId, button);
      }
    }
  });
});

/** Finish a group */
function finishGroup(groupId, button) {
  const formData = new FormData();

  const now = new Date();
  const datetime = now.toLocaleString("sv-SE").replace("T", " "); // 'YYYY-MM-DD HH:mm:ss'

  formData.append("group_id", groupId);
  formData.append("finist_date", datetime);

  fetch("functions/Groups/finish_group.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Remove the row from the table
        const row = button.closest("tr");
        row.remove();
        notyf.success("Group Finished Successfully");
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => {
      alert("An error occurred while finishing the group");
    });
}

/** setTable */
function setTable(res, branch = null) {
  tbody.innerHTML = ""; // Clear current table content

  if (res.data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-6 py-4 font-bold bg-white" colspan="7"> No Group Found </td>
        </tr>
    `;
  }

  res.data.forEach((row) => {
    const tr = document.createElement("tr");
    tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";

    tr.innerHTML = `
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
          ${row.group_name.charAt(0).toUpperCase() + row.group_name.slice(1)}
      </th>
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
          ${
            row.group_time == 2 || row.group_time == 5
              ? `${row.group_time} - Friday`
              : row.group_time
          }
      </th>
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
      <span class="${dayBadgeColor(
        row.group_day
      )} text-sm font-medium me-2 px-2.5 py-1.5 rounded-md">${
      row.group_day
    }</span></th>
      <td class="px-6 py-4">
      <span class="w-2 h-2 ${
        branchIndicator(row.branch_name)["bgColor"]
      } inline-block mr-2"></span>
          ${
            row.instructor_name.charAt(0).toUpperCase() +
            row.instructor_name.slice(1)
          }
      </td>
      <td class="px-6 py-4 ${branchIndicator(row.branch_name)["textColor"]}">
          ${row.branch_name.charAt(0).toUpperCase() + row.branch_name.slice(1)}
      </td> 
      <td class="px-6 py-4">
          <span class="block">${row.month}</span>
          ${row.formatted_date ? row.formatted_date : "No date added"}
      </td>           
      <td class="px-6 py-4">
          <a href="?action=edit&group_id=${
            row.id
          }" class="cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-blue-600 hover:underline mr-2 inline-block mb-2"><i class="fa-solid fa-pen-to-square mr-2"></i>
            Edit
          </a>
          <button data-group-id="${
            row.id
          }" class="finish-group-btn cursor-pointer border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-600 hover:underline">
              <i class="fa-regular fa-circle-check mr-2"></i>Finish
          </button>
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
    saturday: "bg-orange-100 text-orange-600",
    sunday: "bg-blue-100 text-blue-700",
    monday: "bg-pink-100 text-pink-700",
    default: "bg-orange-100 text-orange-700",
  };

  return colors[dayName] || colors["default"];
}

/** get gropus total count */
async function groupsTotalCount(branch , instructor = null) {
  await globalWait(1000);

  const url = `functions/Groups/get_groups_count.php?branch_id=${encodeURIComponent(
    branch
  )}`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      document.querySelector(".total-inst-count").innerText = data;
    })
    .catch((error) => console.error("Error:", error));
}
