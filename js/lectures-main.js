const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const lectureForm = document.getElementById("lectureForm");
const groupTimeSelect = document.getElementById("group-time");
const timeOptions = document.getElementById("time-options");
const trackSelect = document.getElementById('tracks');

const cardImgs = {
  html : "https://img.icons8.com/?size=100&id=D2Hi2VkJSi33&format=png&color=000000" , 
  css : "https://img.icons8.com/?size=100&id=NPK9UueLJjaj&format=png&color=000000" , 
  javascript : "https://img.icons8.com/?size=100&id=RwtOBojoLS2N&format=png&color=000000" , 
  php : "https://img.icons8.com/?size=100&id=39856&format=png&color=037585" , 
  'Database MySQL' : "https://img.icons8.com/?size=120&id=9nLaR5KFGjN0&format=png&color=000000" , 
  project : "https://img.icons8.com/?size=120&id=111139&format=png&color=000000" , 
}

document.addEventListener("DOMContentLoaded", () => {
  const branchMeta = getMetaContent('branch');
  const roleMeta = getMetaContent('role');

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
  
  if (!branch.value) {
    resetAllWithNoBranch();
    fetchBranchLectures(this.value);
    return ;
  }
  
  // fetch tracks when select a branch
  fetchTracks();

  // show time options
  showTimeOptions();
 
  // fetch instructors whitin the selected branch
  fetchInstructors(this.value);

  // show lectures based on selected branch
  fetchBranchLectures(this.value);

  // reset time
  groupTimeSelect.value = "";
};

/** get Tracks */
async function fetchTracks(){
  let fetchTracks = await fetch(`functions/Tracks/get_tracks.php`);
  let tracksData = await fetchTracks.json();

  tracks.innerHTML = "<option value=''>Select Track</option>";
  if (tracksData.data) {
        tracksData.data.forEach((trackResData) => {
          const option = document.createElement("option");
          option.value = trackResData.id;
          option.textContent = capitalizeFirstLetter(trackResData.name);
          tracks.appendChild(option);
        });
      }
  
}

/** select lectures by Track and Group */
tracks.onchange = function(){
    
  // reset instructor and time when select a track
  document.querySelector("#instructor option:first-child").selected = "true";
  document.querySelector("#group-time option:first-child").selected = "true";
  
  // reset groups when select no track
  if (this.value == "") {
    fetchBranchLectures(branch.value);
    return;
  }

  // select track only when there a branch
  if (branch.value) {
    fetchBranchAndTrackLec(branch.value , this.value);
  }
}

function fetchBranchAndTrackLec(branchId , trackId){
  fetch(`functions/Lectures/get_lectures.php?branch_id=${branchId}&track_id=${trackId}`)
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
  
  if (this.value == "") {
    fetchBranchLectures(branch.value);
    return;
  }
  
  let url = "";

  if (branch.value) {
    url = `functions/Lectures/get_lectures.php?branch_id=${branch.value}&time=${this.value}`;
  } else {
    url = `functions/Lectures/get_lectures.php?time=${this.value}`;
  }

  try {
    let lectures = await fetch(url)
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
    console.error('An error occurred:', error);
  }
};

/** select instructor */
instructor.onchange = function () {
  if (this.value == "") {
    fetchBranchLectures(branch.value);
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
};

/** set card */
function setCard(lec) {
  const fullComment = lec.comment;
  const isLong = fullComment.length > 30;
  const shortComment = isLong ? fullComment.slice(0, 30) + '...' : fullComment;

  const commentId = `comment-${Math.random().toString(36).substring(2, 9)}`;

  return `
  <li class="relative mb-6 flex-shrink-0">
    <img class="absolute top-2 right-0 opacity-20" src="${cardImgs[lec.track_name]}" alt="" />
    
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
        <h3 class="text-2xl mb-1 font-semibold tracking-wide text-amber-500 dark:text-white">
          ${capitalizeFirstLetter(lec.group_name)}
        </h3>
        <span class="ml-2 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-yellow-600/20 ring-inset">
          ${
            lec.group_time == 2 || lec.group_time == 5
              ? lec.group_time + " - Friday"
              : lec.group_time == 8
              ? "Online"
              : lec.group_time
          }
        </span>
      </div>

      <div class="block mt-1 mb-4 text-md font-semibold tracking-wide leading-none text-gray-500 dark:text-gray-500">
        <i class="fab fa-teamspeak mr-1"></i> Instructor : ${capitalizeFirstLetter(lec.instructor_name)}
      </div>

      <time class="block mb-4 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
        <i class="fas fa-calendar-check mr-1"></i> Commented on ${lec.latest_comment_date}
      </time>

      <p class="relative w-full border border-blue-200 bg-blue-50 pl-3 p-2 py-3 rounded-md text-base font-semibold text-gray-500 dark:text-gray-400 h-auto">
        <span class="absolute inline-flex items-center justify-center text-sm font-bold text-white bg-indigo-500 border-2 border-white rounded-lg -top-4 end-5 px-4 py-1 dark:border-gray-900 tracking-wider">
          ${capitalizeFirstLetter(lec.track_name)}
        </span>
        <span id="${commentId}" class="comment-text">${shortComment}</span>
        ${
          isLong
            ? `<a href="#" data-full="${fullComment}" data-short="${shortComment}" data-target="${commentId}" class="read-more-toggle text-indigo-600 ml-2 text-sm font-normal">Read more</a>`
            : ''
        }
      </p>
    </div>
  </li>
  `;
}

/** fetch branch lectures */
function fetchBranchLectures(value) {
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
          lecturesCards.innerHTML = `<p><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>`;
        }
      }
    })
    .catch((error) => console.error("Error fetching lectures:", error));
}

/** Fetch instructors based on selected branch */
function fetchInstructors(branchId){
  fetch(`functions/Instructors/get_instructors.php?branch_id=${branchId}`)
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

/** show time options */
function showTimeOptions() {
  document.querySelector("#group-time option:first-child").innerHTML = "Choose a Time";
  timeOptions.classList.remove("hidden");
}

 /** reset other selects when nulling the branches */
function resetAllWithNoBranch() {
  // reset tracks , time and instructor when no branch selected
  tracks.innerHTML = "<option value=''>Select Branch First</option>";
  instructor.innerHTML = "<option value=''>Select Branch First</option>";
  document.querySelector("#group-time option:first-child").innerHTML = "Select Branch First";
  document.querySelector("#group-time option:first-child").selected = "true";

  timeOptions.classList.add("hidden");
}

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}

/** get roles from meta */
function getMetaContent(value) {
  return document.querySelector(`meta[name="${value}"]`)?.content || null;
}


document.addEventListener('click', function (e) {
  if (e.target.classList.contains('read-more-toggle')) {
    e.preventDefault();

    const toggle = e.target;
    const targetId = toggle.dataset.target;
    const full = toggle.dataset.full;
    const short = toggle.dataset.short;
    const commentSpan = document.getElementById(targetId);

    if (toggle.textContent === 'Read more') {
      commentSpan.textContent = full;
      toggle.textContent = 'Read less';
    } else {
      commentSpan.textContent = short;
      toggle.textContent = 'Read more';
    }
  }
});
