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
      console.log(agentId);
      
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
      
      if (confirm("Are you sure you want to delete this CS Agent? This action cannot be undone.")) {
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
              notyf.error(data.message || "Error deleting Customer service agent");
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
    const authCSAdmin = res.logged_instructor_role ;
    res.data.forEach((instructor) => {
      const row = document.createElement("tr");      
      const roleDisabled = instructor.instructor_role === authCSAdmin ? 'disabled' : '';
      const csAdminIcon = instructor.instructor_role == 'cs-admin' ? ` <i class="fa-solid fa-user-shield ml-3 text-green-700"></i>` : '';

      row.className =
        "odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600";

      const statusText = instructor.is_active ? "Active" : "Disabled";
      const statusColor = instructor.is_active
        ? " bg-green-50  text-green-700 ring-green-600/20 "
        : " bg-red-50 text-red-700 ring-red-600/10 ";
      const actionText = instructor.is_active ? "Disable" : "Enable";
      const actionColor = instructor.is_active
        ? "text-red-600"
        : "text-green-600";
      const actionIcon = instructor.is_active
        ? '<i class="fa-solid fa-user-slash mr-1"></i>'
        : '<i class="fa-solid fa-user mr-1"></i>';
      row.innerHTML = `
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ${capitalizeFirstLetter(instructor.username)}
                    ${csAdminIcon}
                </th>
                <td class="px-6 py-4">
                    ${
                      instructor.branch_name
                        ? capitalizeFirstLetter(instructor.branch_name)
                        : "Not Assigned"
                    }
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset  ${statusColor}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <button ${roleDisabled} class="toggle-status-btn cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg hover:underline disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none disabled:cursor-not-allowed disabled:hover:no-underline mr-2 ${actionColor}" data-agent-id="${
        instructor.id
      }">
                        ${actionIcon}
                        ${actionText}
                    </button>
                    <button class="delete-cs-btn text-sm border border-gray-300 py-1 px-2 rounded-lg font-medium text-red-500 hover:underline" data-agent-id="${
        instructor.id
      }">
                        <i class="fa-solid fa-trash mr-1"></i> Delete
                    </button>
                </td>
            `;
      tbody.appendChild(row);
    });
  }
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
