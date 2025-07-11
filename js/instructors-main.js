import { capitalizeFirstLetter } from "./helpers.js";

const searchInput = document.getElementById("table-search");
const tbody = document.querySelector("tbody");
const addInstructor = document.getElementById("addInstructor");

document.addEventListener("DOMContentLoaded", function () {
  /** search functionality */
  searchInput.addEventListener("input", function () {
    const searchValue = this.value.trim();
    fetch(
      `functions/Instructors/search_instructors.php?search=${encodeURIComponent(
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
      const instructorId = button.dataset.instructorId;
      const isDisabling = button.textContent.trim() === "Disable";
      const confirmMessage = isDisabling
        ? "Are you sure you want to disable this instructor?"
        : "Are you sure you want to enable this instructor?";

      if (confirm(confirmMessage)) {
        fetch("functions/Instructors/toggle_instructor_status.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `instructor_id=${instructorId}`,
        })
          .then((response) => response.json())
          .then((data) => {            
            if (data.status === "success") {
              // Refresh the table data
              notyf.success("Instructor Updated successfully");

              // update the row
              updateInstructorStatusUI(button, isDisabling);
            } else {
              alert("Error updating instructor status");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Error updating instructor status");
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
      if (res.status === "success") {
        const branchesContainer = document.getElementById("branchesContainer");
        branchesContainer.innerHTML = ""; // Clear previous checkboxes

        res.data.forEach((branch , index) => {
          const label = document.createElement("label");

          // the last iteration will width full
          if (index === res.data.length - 1) {
            label.className =
                "col-span-2 md:col-span-1 flex items-center space-x-2 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100";
          }else {
            label.className =
              "col-span-1 flex items-center space-x-2 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100";
          }
          

          const checkbox = document.createElement("input");
          checkbox.type = "checkbox";
          checkbox.name = "branch_ids[]";
          checkbox.value = branch.id;
          checkbox.className =
            "text-blue-600 focus:ring-blue-500 border-gray-300 rounded border";

          const span = document.createElement("span");
          span.className = "text-gray-900 text-sm";
          span.textContent = capitalizeFirstLetter(branch.name);

          label.appendChild(checkbox);
          label.appendChild(span);

          branchesContainer.appendChild(label);
        });
      }
    })
    .catch((error) => console.error("Error fetching branches:", error));
};

// Function to update the table with search results
function setTable(res) {  
  tbody.innerHTML = "";
  
  if (res.data.length == 0) {
    tbody.innerHTML = `
        <tr>
          <td class="px-6 py-4 font-bold" colspan="4"> No Instructor Found </td>
        </tr>
    `;
  }

  if (res.status === "success") {
    res.data.forEach((instructor) => {
      const row = document.createElement("tr");
      row.className =
        "odd:bg-white even:bg-gray-50 bg-white border-b border-gray-200 hover:bg-gray-50";

      const statusText = instructor.is_active ? "Active" : "Disabled";
      const statusColor = instructor.is_active
        ? " bg-green-50  text-green-700 ring-green-600/20 "
        : " bg-red-50 text-red-700 ring-red-600/10 ";
      const actionText = instructor.is_active ? "Disable" : "Enable";
      const actionColor = instructor.is_active
        ? "text-red-500"
        : "text-green-500";
      const actionIcon = instructor.is_active
        ? '<i class="fa-solid fa-user-slash mr-1"></i>'
        : '<i class="fa-solid fa-user mr-1"></i>';
      row.innerHTML = `
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    ${capitalizeFirstLetter(instructor.username)}
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    ${capitalizeFirstLetter(instructor.email || `<span class="text-gray-400">Not Assigned</span>`)}
                </th>
                <td class="px-6 py-4">
                <div class="flex flex-row justify-start items-center gap-1">
                  <svg class=" w-5 h-5 text-indigo-600  md:inline " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd"></path>
                                        </svg>
                
                    ${
                      instructor.branch_names
                        ? capitalizeFirstLetter(instructor.branch_names)
                        : "Not Assigned"
                    }
                </td>
                </div>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset  ${statusColor}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <button class="toggle-status-btn cursor-pointer text-sm border border-gray-300 py-1 px-2 rounded-lg hover:underline ${actionColor}" data-instructor-id="${
        instructor.id
      }">
                        ${actionIcon}
                        ${actionText}
                    </button>
                </td>
            `;
      tbody.appendChild(row);
    });
  }
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
  const statusSpan = button.closest("tr").querySelector("td .isactive-span");
  statusSpan.textContent = statusText;
  statusSpan.className = `isactive-span inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ${statusColor}`;

  // Update the button text, icon, and styles
  button.innerHTML = `${actionIcon} ${actionText}`;
  button.className = `toggle-status-btn cursor-pointer text-sm font-medium border border-gray-300 py-1 px-2 rounded-lg ${actionColor} hover:underline`;
}
