document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("table-search");
  const tbody = document.querySelector("tbody");


  searchInput.addEventListener("input", function () {
    const searchValue = this.value.trim();

    fetch(
      `functions/Groups/search_groups.php?search=${encodeURIComponent(
        searchValue
      )}`
    )
      .then((response) => response.json())
      .then((data) => {
        tbody.innerHTML = ""; // Clear current table content

        if(data.length == 0) {
          tbody.innerHTML = `
             <tr>
                <td class="px-6 py-4 font-bold" colspan="4"> No Group Found </td>
             </tr>
          `;
        }

        data.forEach((row) => {
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
                    row.instructor_name
                    .charAt(0)
                    .toUpperCase() +
                    row.instructor_name.slice(1)
                }
            </td>
            <td class="px-6 py-4">
                ${
                    row.branch_name.charAt(0).toUpperCase() +
                    row.branch_name.slice(1)
                }
            </td>            <td class="px-6 py-4">
                <button data-group-id="${row.id}" class="finish-group-btn font-medium text-red-600 dark:text-red-500 hover:underline">
                    <i class="fas fa-ban mr-2"></i>Finish
                </button>
            </td>
        `;

          tbody.appendChild(tr);
        });
      })
      .catch((error) => console.error("Error:", error));
  });

  
  // Handle finish button clicks
  tbody.addEventListener('click', function(e) {
    if (e.target.closest('.finish-group-btn')) {
      const button = e.target.closest('.finish-group-btn');
      const groupId = button.dataset.groupId;
      
      if (confirm('Are you sure you want to finish this group?')) {
        finishGroup(groupId, button);
      }
    }
  });

});

/** Finish a group */
function finishGroup(groupId, button) {
  const formData = new FormData();
  formData.append('group_id', groupId);

  fetch('functions/Groups/finish_group.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      // Remove the row from the table
      const row = button.closest('tr');
      row.remove();
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(error => {
    alert('An error occurred while finishing the group');
  });
}
