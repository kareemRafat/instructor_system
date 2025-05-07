const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const lectureForm = document.getElementById("lectureForm");

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
});

/** select branch */
branch.onchange = function () {
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
};

/** form select */
lectureForm.onsubmit = function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  const url = this.action;
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        if (res.data.length > 0) {
          lecturesCards.innerHTML = ""; // Clear previous cards
          res.data.forEach((lec) => {
            let card = `
            <div class="bg-white shadow-md rounded-lg p-5">
                <h2 class=" text-center text-xl font-semibold text-blue-700 mb-1">${lec.group_name}</h2>
                <p class="text-gray-700 my-3 text-right">${lec.comment}</p>
                <p class="text-gray-500 text-sm mt-1 text-right">التاريخ: ${lec.formatted_date}</p>
            </div>
          `;
            lecturesCards.innerHTML += card;
          });
        } else {
          lecturesCards.innerHTML = "<p>No lectures found</p>";
        }
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
};
