const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");

document.addEventListener("DOMContentLoaded", () => {
  // Fetch branches
  fetch("functions/Branches/get_branches.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.data) {
        res.data.forEach((br) => {
          const option = document.createElement("option");
          option.value = br.id;
          option.textContent = br.name;
          branch.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching groups:", error));

    // fetch all lectures
    fetch("functions/Lectures/get_lectures.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.data) {
        res.data.forEach((lec) => {
          
          branch.appendChild(option);
        });
      }
    })

});

/** select branch */
branch.onchange = function(){
  // Fetch instructors based on selected branch
  console.log(this.value);
  fetch(`functions/Instructors/get_instructors.php?branch_id=${this.value}`)
    .then((response) => response.json())
    .then((res) => {  
      instructor.innerHTML = "<option value=''>Choose Instructor</option>";    
      if (res.data) {
        res.data.forEach((instructorData) => {
          const option = document.createElement("option");
          option.value = instructorData.id;
          option.textContent = instructorData.username;
          instructor.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching instructors:", error));
}

