const searchInput = document.getElementById("table-search");
const tbody = document.querySelector("tbody");
const addInstructor = document.getElementById("addInstructor");

document.addEventListener("DOMContentLoaded", function () {
  /** search functionality */
  searchInput.addEventListener("input", function () {
    const searchValue = this.value.trim();
    fetch(
      `functions/Customer-service/search_cs.php?search=${encodeURIComponent(
        searchValue
      )}`
    )
      .then((response) => response.json())
      .then((data) => {
        setTable(data);
      })
      .catch((error) => console.error("Error:", error));
  });

  // Handle toggle status button clicks
  tbody.addEventListener("click", function (e) {
    if (e.target.closest(".toggle-status-btn")) {
      const button = e.target.closest(".toggle-status-btn");
      const agentId = button.dataset.agentId;

      const isDisabling = button.textContent.trim() === "Disable";
      const confirmMessage = isDisabling
        ? "Are you sure you want to disable this CS Agent?"
        : "Are you sure you want to enable this CS Agent?";

      if (confirm(confirmMessage)) {
        fetch("functions/Customer-service/toggle_cs_status.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `cs_id=${agentId}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              // Refresh the table data
              notyf.success("Customer service agent Update successfully");

              // update the row
              updateInstructorStatusUI(button, isDisabling);
            } else {
              alert("Error updating Customer service status");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Error updating Customer service status");
          });
      }
    }

    // Handle delete button clicks
    if (e.target.closest(".delete-cs-btn")) {
      const button = e.target.closest(".delete-cs-btn");
      const agentId = button.dataset.agentId;

      if (
        confirm(
          "Are you sure you want to delete this CS Agent? This action cannot be undone."
        )
      ) {
        fetch("functions/Customer-service/delete_cs.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `cs_id=${agentId}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              notyf.success("Customer service agent deleted successfully");
              // Remove the row from the table
              button.closest("tr").remove();
            } else {
              notyf.error(
                data.message || "Error deleting Customer service agent"
              );
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            notyf.error("Error deleting Customer service agent");
          });
      }
    }
  });
});

// get branches when open the modal
addInstructor.onclick = function () {
  fetch("functions/Branches/get_branches.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        branchesSelect.innerHTML = `<option value="" selected>Choose a Branch</option>`; // Clear previous cards
        res.data.forEach((branch) => {
          let option = document.createElement("option");
          option.value = branch.id;
          option.textContent = capitalizeFirstLetter(branch.name);
          branchesSelect.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
};

// Function to update the table with search results
function setTable(res) {
  tbody.innerHTML = "";

  if (res.data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-6 py-4 font-bold" colspan="4"> No Customer Service Agent Found </td>
        </tr>
    `;
  }

  if (res.status === "success") {
    // the logged in user ROLE
    const authCSAdmin = res.logged_instructor_role;
    res.data.forEach((instructor) => {
      const row = document.createElement("tr");
      const roleDisabled =
        instructor.role === authCSAdmin || instructor.role === "owner"
          ? "disabled"
          : "";
      const csAdminIcon =
        instructor.role == "cs-admin"
          ? ` <i class="fa-solid fa-user-shield ml-3 text-green-700"></i>`
          : "";

      row.className =
        "odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50";

      const statusText = instructor.is_active ? "Active" : "Disabled";
      const statusColor = instructor.is_active
        ? " bg-green-50  text-green-700 ring-green-600/20 "
        : " bg-red-50 text-red-700 ring-red-600/10 ";
      const actionText = instructor.is_active ? "Disable" : "Enable";
      const actionColor = instructor.is_active
        ? "text-red-600"
        : "text-green-600";
      const actionIcon = instructor.is_active
        ? '<i class="fa-solid fa-user-slash mr-1 hidden md:inline-block"></i>'
        : '<i class="fa-solid fa-user mr-1 hidden md:inline-block"></i>';
      row.innerHTML = `
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    ${capitalizeFirstLetter(instructor.username)}
                    ${csAdminIcon}
                </th>
                <td class="w-40 px-6 py-4">
                  <div class="w-fit text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded text-sm py-1 px-2 text-center">
                    <a class="flex" id="add-salary" href="?action=add&id=${instructor.id}">
                      <i class="fa-solid fa-money-check-dollar mr-2 text-sm"></i>
                      <span class="hidden md:inline-block mr-1">Edit </span>
                      salary
                    </a>
                  </div>
                </td>
                <td class="px-6 py-4">
                <div class="flex flex-row justify-start items-center">
                <svg class=" w-5 h-5 mr-1.5  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                            </svg>
                    ${
                      instructor.branch_names
                        ? capitalizeFirstLetter(instructor.branch_names)
                        : "Not Assigned"
                    }
                    </div>
                </td>
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    ${instructor.email || `<span class="text-gray-400">Not Assigned</span>`}
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset  ${statusColor}">
                        ${statusText}
                    </span>
                </td>

                ${generateActionCell(instructor, authCSAdmin)}
                
            `;
      tbody.appendChild(row);
    });
  }
}


function generateActionCell(instructor, authCSAdmin) {
  const isOwnerOrAdmin = instructor.role === authCSAdmin || instructor.role === 'owner';
  const roleDisabled = isOwnerOrAdmin ? 'disabled' : '';

  const statusText = instructor.is_active ? 'Disable' : 'Enable';
  const statusColor = instructor.is_active ? 'text-red-600' : 'text-green-600';
  const statusIcon = instructor.is_active
    ? '<i class="fa-solid fa-user-slash hidden md:inline-block mr-1 text-sm"></i>'
    : '<i class="fa-solid fa-user hidden md:inline-block mr-1 text-sm"></i>';

  const editBtnClass = instructor.role === 'owner'
    ? 'pointer-events-none text-gray-500 cursor-not-allowed'
    : 'text-blue-600';

  return `
    <td class="px-6 py-4 flex flex-col md:flex-row gap-1 w-fit">
      <a href="?action=edit&instructor_id=${instructor.id}"
         class="cursor-pointer border border-gray-300 py-0.5 px-2 rounded-lg font-medium ${editBtnClass} hover:underline text-center w-full md:w-fit flex items-center">
        <i class="fa-solid fa-pen-to-square mr-1 hidden md:inline-block text-sm"></i>
        Edit
      </a>

      <button ${roleDisabled}
        class="w-full md:w-fit toggle-status-btn cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg ${statusColor} hover:underline
               disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed disabled:hover:no-underline"
        data-agent-id="${instructor.id}">
        ${statusIcon}
        ${statusText}
      </button>

      <button ${roleDisabled}
        class="delete-cs-btn w-full md:w-fit cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg text-red-600 hover:underline 
               disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed disabled:hover:no-underline"
        data-agent-id="${instructor.id}">
        <i class="fa-solid fa-trash mr-1 hidden md:inline-block text-sm"></i>
        Delete
      </button>
    </td>
  `;
}

// Helper function to capitalize first letter
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateInstructorStatusUI(button, isDisabling) {
  const statusText = isDisabling ? "Disabled" : "Active";
  const statusColor = isDisabling
    ? "bg-red-50 text-red-700 ring-red-600/10"
    : "bg-green-50 text-green-700 ring-green-600/20";
  const actionText = isDisabling ? "Enable" : "Disable";
  const actionColor = isDisabling ? "text-green-500" : "text-red-500";
  const actionIcon = isDisabling
    ? '<i class="fa-solid fa-user mr-1"></i>'
    : '<i class="fa-solid fa-user-slash mr-1"></i>';

  // Update the status text and styles
  const statusSpan = button.closest("tr").querySelector("td span");
  statusSpan.textContent = statusText;
  statusSpan.className = `inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ${statusColor}`;

  // Update the button text, icon, and styles
  button.innerHTML = `${actionIcon} ${actionText}`;
  button.className = `toggle-status-btn cursor-pointer text-sm font-medium border border-gray-300 py-1 px-2 rounded-lg ${actionColor} hover:underline`;
}