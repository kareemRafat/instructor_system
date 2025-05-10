const searchInput = document.getElementById("table-search");
const tbody = document.querySelector("tbody");

document.addEventListener("DOMContentLoaded", function () {

  // get branches when page loaded
  fetch("functions/Branches/get_branches.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        branchSelect.innerHTML = `<option value="" selected>Choose a Branch</option>`; // Clear previous cards
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

    fetch(
      `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}`
    )
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        
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

  /** show branch lectures functionality */
  branchSelect.onchange = function(){
    fetchLecturesByGroup(this.value , this.selectedOptions[0].text)
  }
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
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => {
      alert("An error occurred while finishing the group");
    });
}

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}

/** fetch branch lectures for table */
function fetchLecturesByGroup(value , branch){
  // get all lectures based on selected branch
  fetch(`functions/Groups/get_groups.php?branch_id=${value}`)
    .then((response) => response.json())
    .then((data) => {           
      setTable(data , branch)
    })
    .catch((error) => console.error("Error fetching lectures:", error));
}

/** setTable */
function setTable(data , branch = null) {
  tbody.innerHTML = ""; // Clear current table content

  console.log(data.data);
  
  if (data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-6 py-4 font-bold" colspan="4"> No Group Found </td>
        </tr>
    `;
  }

  data.data.forEach((row) => {
    console.log(row);
    
    const tr = document.createElement("tr");
    tr.className =
      "bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600";

    tr.innerHTML = `
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
          ${
            row.group_name.charAt(0).toUpperCase() +
            row.group_name.slice(1)
          }
      </th>
      <td class="px-6 py-4">
          ${
            row.instructor_name.charAt(0).toUpperCase() +
            row.instructor_name.slice(1)
          }
      </td>
      <td class="px-6 py-4">
          ${
            row.branch_name.charAt(0).toUpperCase() +
            row.branch_name.slice(1)
          }
      </td> 
      <td class="px-6 py-4">
          ${row.formatted_date ? row.formatted_date : "No date added"}
      </td>           
      <td class="px-6 py-4">
          <button data-group-id="${
            row.id
          }" class="finish-group-btn font-medium text-red-600 dark:text-red-500 hover:underline">
              <i class="fas fa-ban mr-2"></i>Finish
          </button>
      </td>
  `;

    tbody.appendChild(tr);
  });
}
