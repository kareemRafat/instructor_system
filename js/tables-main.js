import { capitalizeFirstLetter } from "./helpers.js";

const branchSelect = document.getElementById("branchSelect");
const branchForm = document.getElementById("branch-form");
const urlParams = new URLSearchParams(window.location.search);

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
          if (urlParams.get('branch') == branch.id){
            option.selected = true; // Select the branch if it matches the URL parameter
          }
          branchSelect.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
});

branchSelect.onchange = function(){
    branchForm.submit();
    if (!this.value) {
        window.location.href = 'tables.php?branch=1';
    }
}
