const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const lectureForm = document.getElementById("lectureForm");
const groupTimeSelect = document.getElementById('group-time');

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
    fetchBranchLectures(this.value);

    // reset time
    groupTimeSelect.value = '';
  
};

/** select Lectures by Group time */
groupTimeSelect.onchange = function() {
  let url = '';

  if (this.value == "") {
    fetchBranchLectures(branch.value);
    return ;
  }

  if(branch.value ) {
    url = `functions/Lectures/get_lectures.php?branch_id=${branch.value}&time=${this.value}`
  } else {
    url = `functions/Lectures/get_lectures.php?time=${this.value}`
  }

  fetch(url)
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

/** select instructor */
instructor.onchange = function () {
  if (this.value == "") {
    fetchBranchLectures(branch.value);
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

    // reset time
    groupTimeSelect.value = '';
};

/** set card */
function setCard(lec) {
  return `
  <li class="relative mb-6 md:mb-0 md:w-[calc(33.333%-1rem)] flex-shrink-0">
    <div class="flex items-center">
    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
      <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
      </svg>
    </div>
    <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
    </div>
    <div class="mt-3 sm:pe-0 pb-7 border-indigo-500 border-b-2">
      <div class="flex items-center gap-2">
        <i class="fas fa-circle-check text-zinc-500"></i>
        <h3 class="text-xl mb-1 font-semibold text-amber-500 dark:text-white">${capitalizeFirstLetter(
          lec.group_name
        )}</h3>
        <span class="ml-2 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-yellow-600/20 ring-inset ">${lec.group_time == 2 || lec.group_time == 5 ? lec.group_time + ' - Friday' : lec.group_time}</span>
      </div>
      
      <div class="block mt-1 mb-4 text-md font-normal leading-none text-gray-500 dark:text-gray-500"><i class="fab fa-teamspeak mr-1"></i> Instructor :  ${
        capitalizeFirstLetter(lec.instructor_name)
      }</div>

      <time class="block mb-4 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"><i class="fas fa-calendar-check mr-1"></i> Commented on ${
        lec.formatted_date
      }</time>

      <p class="w-full border border-blue-200 bg-blue-50 pl-3 p-2 rounded-md text-base border-gray-200 text-base font-normal text-gray-500 dark:text-gray-400">${
        lec.comment
      }</p>
    </div>
  </li>
  `;
}

/** fetch branch lectures */
function fetchBranchLectures(value){
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

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}
