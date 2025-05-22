import {getMetaContent , capitalizeFirstLetter , wait } from "./helpers.js";

const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const groupTimeSelect = document.getElementById("group-time");
const timeOptions = document.getElementById("time-options");
const tracks = document.getElementById("tracks");
const skeleton = document.getElementById("skeleton");
const arrowWarning = document.getElementById("arrow-warning");

document.addEventListener("DOMContentLoaded", async () => {
  const branchMeta = getMetaContent("branch");
  const roleMeta = getMetaContent("role");

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

  if (roleMeta == 'cs') {
    await wait(1000).then(() => { skeleton.classList.add("hidden") });
    // fetch lectures base on logged user branch
    fetchBranchLectures(branchMeta);
    // fetch tracks when select a branch
    fetchTracks();
    // show time options
    showTimeOptions();
    // fetch instructors whitin the selected branch
    fetchInstructors(branchMeta);
  } else {
    // if admin and cs-admin
    skeleton.classList.add("hidden");
    arrowWarning.classList.remove("hidden");
  }
});

/** select branch */
branch.onchange = async function () {
  try {
    showLoadingSkeleton();
    
    if (!branch.value) {
      resetAllWithNoBranch();
      await fetchBranchLectures(this.value);
      await wait(1000);
      skeleton.classList.add("hidden");
      return;
    }

    // Run these operations in parallel for better performance
    await Promise.all([
      fetchTracks(),
      fetchInstructors(this.value),
      fetchBranchLectures(this.value)
    ]);

    // show time options after successful fetches
    showTimeOptions();

    // reset time
    groupTimeSelect.value = "";

    await wait(1000);
    skeleton.classList.add("hidden");
  } catch (error) {
    console.error("Error in branch change handler:", error);
    lecturesCards.innerHTML = "<p>An error occurred. Please try again.</p>";
    skeleton.classList.add("hidden");
  }
};

/** get Tracks */
async function fetchTracks() {
  try {
    const response = await fetch(`functions/Tracks/get_tracks.php`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const tracksData = await response.json();
    
    tracks.innerHTML = "<option value=''>Select Track</option>";
    if (tracksData.data) {
      tracksData.data.forEach((trackResData) => {
        const option = document.createElement("option");
        option.value = trackResData.id;
        option.textContent = capitalizeFirstLetter(trackResData.name);
        tracks.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error fetching tracks:", error);
    tracks.innerHTML = "<option value=''>Error loading tracks</option>";
  }
}

/** select lectures by Track and Group */
tracks.onchange = async function () {
  showLoadingSkeleton();

  // reset instructor and time when select a track
  document.querySelector("#instructor option:first-child").selected = "true";
  document.querySelector("#group-time option:first-child").selected = "true";

  // reset groups when select no track
  if (this.value == "") {
    fetchBranchLectures(branch.value);
    await wait(1000).then(() => { skeleton.classList.add("hidden") });
    return;
  }

  // select track only when there a branch
  if (branch.value) {
    fetchBranchAndTrackLec(branch.value, this.value);
  }

  await wait(1000).then(() => { skeleton.classList.add("hidden") });
};

function fetchBranchAndTrackLec(branchId, trackId) {
  fetch(
    `functions/Lectures/get_lectures.php?branch_id=${branchId}&track_id=${trackId}`
  )
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

/** select Lectures by Group time */
groupTimeSelect.onchange = async function () {

  showLoadingSkeleton();

  if (this.value == "") {
    fetchBranchLectures(branch.value);
    await wait(1000).then(() => { skeleton.classList.add("hidden") });
    return;
  }

  let url = "";

  if (branch.value) {
    url = `functions/Lectures/get_lectures.php?branch_id=${branch.value}&time=${this.value}`;
  } else {
    url = `functions/Lectures/get_lectures.php?time=${this.value}`;
  }

  try {
    let lectures = await fetch(url);
    let res = await lectures.json();
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
  } catch (error) {
    console.error("An error occurred:", error);
  }
  await wait(1000).then(() => { skeleton.classList.add("hidden") });
};

/** select instructor */
instructor.onchange = async function () {

  showLoadingSkeleton();

  if (this.value == "") {
    fetchBranchLectures(branch.value);
    await wait(1000).then(() => { skeleton.classList.add("hidden") });
    return;
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
  groupTimeSelect.value = "";
  await wait(1000).then(() => { skeleton.classList.add("hidden") });
};

/** set card */
function setCard(lec) {
  return `
  <li class="relative mb-6 flex-shrink-0">   
    <div class="flex items-center">
    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white sm:ring-8 shrink-0">
      <svg class="w-2.5 h-2.5 text-blue-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
      </svg>
    </div>
    <div class="hidden sm:flex w-full bg-gray-200 h-0.5"></div>
    </div>
    <div class="mt-3 sm:pe-0 pb-7 border-indigo-500 border-b-2">
      <div class="flex items-center gap-2">
        <i class="fas fa-circle-check text-zinc-500"></i>
        <h3 class="text-2xl mb-1 font-semibold tracking-wide text-amber-500">${capitalizeFirstLetter(
          lec.group_name
        )}</h3>
        <span class="ml-2 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-yellow-600/20 ring-inset ">${
          lec.group_time == 2 || lec.group_time == 5
            ? lec.group_time + " - Friday"
            : lec.group_time == 8
            ? "Online"
            : lec.group_time
        }</span>
      </div>
      
      <div class="block mt-1 mb-4 text-md font-semibold tracking-wide leading-none text-gray-500"><i class="fab fa-teamspeak mr-1"></i> Instructor :  ${capitalizeFirstLetter(
        lec.instructor_name
      )}</div>

      <time class="block mb-4 text-sm font-normal leading-none text-gray-400"><i class="fas fa-calendar-check mr-1"></i> Commented on ${
        lec.latest_comment_date
      }</time>

 
      <p class="relative w-full border border-blue-200 bg-blue-50 pl-3 p-2 py-3 rounded-md text-base font-semibold text-gray-500 h-[76px] flex">
        <span class="absolute inline-flex items-center justify-center text-sm font-bold text-white bg-blue-500 border-2 border-white rounded-lg -top-4 end-5 px-4 py-1 tracking-wider ">
          ${capitalizeFirstLetter(lec.track_name)}
        </span>
        <span>
         ${lec.comment}
         </span>
        </p>
    </div>
  </li>
  `;
}

/** fetch branch lectures */
async function fetchBranchLectures(value) {
  try {
    const response = await fetch(`functions/Lectures/get_lectures.php?branch_id=${value}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const res = await response.json();
    
    if (res.status === "success") {
      if (res.data && res.data.length > 0) {
        lecturesCards.innerHTML = ""; // Clear previous cards
        res.data.forEach((lec) => {
          let card = setCard(lec);
          lecturesCards.innerHTML += card;
        });
      } else {
        lecturesCards.innerHTML = `<p><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>`;
      }
    } else {
      throw new Error(res.message || 'Failed to fetch lectures');
    }
  } catch (error) {
    console.error("Error fetching lectures:", error);
    lecturesCards.innerHTML = "<p>Failed to load lectures. Please try again.</p>";
  }
}

/** Fetch instructors based on selected branch */
async function fetchInstructors(branchId) {
  try {
    const response = await fetch(`functions/Instructors/get_instructors.php?branch_id=${branchId}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const res = await response.json();
    
    instructor.innerHTML = "<option value=''>Choose Instructor</option>";
    if (res.data) {
      res.data.forEach((instructorData) => {
        const option = document.createElement("option");
        option.value = instructorData.id;
        option.textContent = instructorData.username;
        instructor.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error fetching instructors:", error);
    instructor.innerHTML = "<option value=''>Error loading instructors</option>";
  }
}

/** show time options */
function showTimeOptions() {
  document.querySelector("#group-time option:first-child").innerHTML =
    "Choose a Time";
  timeOptions.classList.remove("hidden");
}

/** reset other selects when nulling the branches */
function resetAllWithNoBranch() {
  // reset tracks , time and instructor when no branch selected
  tracks.innerHTML = "<option value=''>Select Branch First</option>";
  instructor.innerHTML = "<option value=''>Select Branch First</option>";
  document.querySelector("#group-time option:first-child").innerHTML =
    "Select Branch First";
  document.querySelector("#group-time option:first-child").selected = "true";

  timeOptions.classList.add("hidden");
}

/** show Loading Skeletopn */
function showLoadingSkeleton() {
  // show skeleton and hide lectures cards and arrow warning
  skeleton.classList.remove("hidden");
  arrowWarning.classList.add("hidden");
  lecturesCards.classList.add("hidden");
}




