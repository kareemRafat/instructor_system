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

    // fetch lectures based on selected branch
    fetchBranch(this.value);
  
};

/** select instructor */
instructor.onchange = function () {
  
  if (this.value == "") {
    fetchBranch(branch.value);
    return ;
  }

  fetch(`functions/Lectures/get_lectures.php?instructor_id=${this.value}`)
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        if (res.data.length > 0) {
          lecturesCards.innerHTML = ""; // Clear previous cards
          res.data.forEach((lec) => {
            let card = setCard(lec);
            lecturesCards.innerHTML += card;
          });
        } else {
          lecturesCards.innerHTML = "<p>No lectures found</p>";
        }
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
};

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}

/** set card */
function setCard(lec) {
  return `
  <li class="relative mb-6 sm:mb-0 sm:w-1/3">
    <div class="flex items-center">
    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
      <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
      </svg>
    </div>
    <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
    </div>
    <div class="mt-3 sm:pe-8 pb-7 border-indigo-500 border-b-2">
      <h3 class="text-xl font-semibold text-amber-500 dark:text-white">${capitalizeFirstLetter(
        lec.group_name
      )}</h3>
      <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">commented on ${
        lec.formatted_date
      }</time>
      <p class="text-base font-normal text-gray-500 dark:text-gray-400">${
        lec.comment
      }</p>
    </div>
  </li>
  `;
}

/** fetch branch lectures */
function fetchBranch(value){
  // get all lectures based on selected branch
  fetch(`functions/Lectures/get_lectures.php?branch_id=${value}`)
    .then((response) => response.json())
    .then((res) => {
      if (res.status == "success") {
        if (res.data.length > 0) {
          lecturesCards.innerHTML = ""; // Clear previous cards
          res.data.forEach((lec) => {
            let card = setCard(lec);
            lecturesCards.innerHTML += card;
          });
        } else {
          lecturesCards.innerHTML = "<p>No lectures found</p>";
        }
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
}