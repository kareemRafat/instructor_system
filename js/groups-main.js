import {
  capitalizeFirstLetter,
  getQueryString,
  globalWait,
} from "./helpers.js";

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

  // finish group in case of training groups only
  finishTrainigGroups();

  // get groups total count when page load
  groupsTotalCount(getQueryString("branch"));

  // when page load get instructors based on branch
  fetchInstructors(branchVal);

  /** instructor select */
  instructorSelect.addEventListener("change", function (e) {
    const instructorId = this.value;
    const instructorName = this.selectedOptions[0]?.text;

    // get total groups to the instructor
    groupsTotalCount(branchVal, instructorId, instructorName);

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
    groupsTotalCount(branchVal);

    let url = "";

    // pagination
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
});

/** Finish Training groups */
function finishTrainigGroups() {
  const button = document.querySelectorAll(".finish-btn");

  button.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const groupId = btn.dataset.groupId;

      if (
        confirm(
          "Are you sure you want to mark this Training group as finished?"
        )
      ) {
        try {
          const response = await fetch(
            "functions/Groups/finish_training_group.php",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
              body: `id=${encodeURIComponent(groupId)}`,
            }
          );

          const result = await response.json();
          if (result.status == "success") {
            const row = btn.closest("tr");
            row.remove();
            notyf.success("Group Finished Successfully");
          }
        } catch (error) {
          alert("Request failed.");
          console.error(error);
        }
      }
    });
  });
}

/** setTable */
function setTable(res, branch = null) {
  tbody.innerHTML = ""; // Clear current table content

  if (res.data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-4 py-2 font-bold bg-white" colspan="9"> No Group Found </td>
        </tr>
    `;
  }

  res.data.forEach((row) => {
    const tr = document.createElement("tr");
    tr.className = "bg-white border-b border-gray-200 hover:bg-gray-50";

    // show real time group time (6.10 to online 6)
    const displayTime = (group_time) => {
      group_time = parseFloat(group_time);
      if (group_time === 2 || group_time === 5) {
        return `${group_time} - Friday`;
      } else if (group_time === 6.1 || group_time === 8) {
        return `Online ${Math.floor(group_time)}`;
      } else {
        return `${group_time}`;
      }
    };
    tr.innerHTML = `
      <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
          ${row.group_name.charAt(0).toUpperCase() + row.group_name.slice(1)}
      </th>
      <th scope="row" class="px-4 py-2 font-medium text-pink-900 whitespace-nowrap">
      <i class="fa-solid fa-clock mr-1.5"></i>
          ${displayTime(row.group_time)}
      </th>
      <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
      <span class="${dayBadgeColor(
        row.group_day
      )} text-sm font-medium me-2 px-2.5 py-1.5 rounded-md">${
      row.group_day
    }</span></th>
      <td class="px-4 py-2 text-sky-600 capitalize">
        ${capitalizeFirstLetter(row.track)}
      </td>
      <td class="px-4 py-2">
      <span class="w-2 h-2 ${
        branchIndicator(row.branch_name)["bgColor"]
      } inline-block mr-2"></span>
          ${
            row.instructor_name.charAt(0).toUpperCase() +
            row.instructor_name.slice(1)
          }
      </td>
      <td class="px-4 py-2 ${branchIndicator(row.branch_name)["textColor"]}">
        <div class="flex flex-row justify-start items-center">
          <svg class=" w-5 h-5 mr-1.5  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
          </svg>
          ${row.branch_name.charAt(0).toUpperCase() + row.branch_name.slice(1)}
        </div>
      </td> 
      <td class="px-4 py-2">
          <span class="block text-rose-700">${row.month}</span>
          ${row.formatted_date ? row.formatted_date : "No date added"}
      </td>     
      <td class="px-4 py-2">
          <span class="text-purple-700">${row.group_end_month}</span>
          <br>
          ${row.group_end_date}
      </td>      
      <td class="px-4 py-2 grid grid-cols-1 gap-1">
          <a href="?action=edit&group_id=${
            row.id
          }" class="cursor-pointer text-center border border-gray-300 py-1 px-2 rounded-lg font-medium text-blue-600 hover:underline"><i class="fa-solid fa-pen-to-square hidden md:inline-block mr-1.5"></i>
            Edit
          </a>
          <a href="?action=finish_group&group_id=${
            row.id
          }" class="cursor-pointer text-center border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-600 hover:underline">
              <i class="fa-regular fa-circle-check hidden md:inline-block mr-1.5"></i>Finish
          </a>
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

/** get gropus total count */
async function groupsTotalCount(
  branch,
  instructor = null,
  instructorName = null
) {
  const instructorTotal = document.querySelector(".total-inst-count");

  instructorTotal.innerHTML = `
    <div role="status" class="inline-block">
             <svg aria-hidden="true" class="inline w-4 h-4 text-gray-400 animate-spin fill-blue-800" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                 <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
             </svg>
             <span class="sr-only">Loading...</span>
         </div>
  `;
  await globalWait(300);

  let url = `functions/Groups/get_groups_count.php`;

  if (instructor) {
    url += `?branch_id=${encodeURIComponent(
      branch
    )}&instructor_id=${encodeURIComponent(instructor)}`;
  } else if (branch) {
    url += `?branch_id=${encodeURIComponent(branch)}`;
  }

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      document.querySelector(".total-inst-count").innerText = data;
    })
    .catch((error) => console.error("Error:", error));
}
