import { capitalizeFirstLetter , getQueryString } from "./helpers.js";

const searchInput = document.getElementById("table-search");
const tbody = document.querySelector("tbody");
const page = getQueryString('page');
const pageList = document.getElementById('page-list');
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
          branchSelect.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));

  /** search functionality */
  searchInput.addEventListener("input", function () {
    const searchValue = this.value.trim();
    let url = "";

    if(!searchValue) { 
      pageList.classList.remove('hidden');
    } else {
      pageList.classList.add('hidden');
    }

    // check if the branch selected or not - add page number to the url
    if (branchSelect.value) {
      url = `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}&branch_id=${encodeURIComponent(branchSelect.value)}${page ? `&page=${encodeURIComponent(page)}` : ''}`;
    } else {
      url = `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}${page ? `&page=${encodeURIComponent(page)}` : ''}`;
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

/** xx fetch branch lectures for table */
function fetchGroups(value, branch) {
  // get all groups based on selected branch
  fetch(`functions/Groups/get_groups.php?branch_id=${value}`)
    .then((response) => response.json())
    .then((data) => {
      setTable(data, branch);
    })
    .catch((error) => console.error("Error fetching lectures:", error));
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
          ${row.group_day}
      </th>
      <td class="px-6 py-4">
          ${
            row.instructor_name.charAt(0).toUpperCase() +
            row.instructor_name.slice(1)
          }
      </td>
      <td class="px-6 py-4">
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
