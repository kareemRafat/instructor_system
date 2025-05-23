import { getMetaContent, capitalizeFirstLetter, wait } from "./helpers.js";

const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const groupTimeSelect = document.getElementById("group-time");
const tracks = document.getElementById("tracks");
const skeleton = document.getElementById("skeleton");
const arrowWarning = document.getElementById("arrow-warning");

document.addEventListener("DOMContentLoaded", async () => {
const branchMeta = getMetaContent("branch");
const roleMeta = getMetaContent("role");

try {
// Fetch branches
const branchResponse = await fetch("functions/Branches/get_branches.php");
const branchData = await branchResponse.json();

if (branchData.data) {
branch.innerHTML = '<option value="" selected>Choose a branch</option>';
branchData.data.forEach((br) => {
const option = document.createElement("option");
option.value = br.id;
option.textContent = capitalizeFirstLetter(br.name);
branch.appendChild(option);
});
}

if (roleMeta == "cs") {
await wait(1000);
skeleton.classList.add("hidden");
// fetch lectures base on logged user branch
await fetchBranchLectures(branchMeta);
// fetch tracks when select a branch
await fetchTracks();
// show time options
showTimeOptions();
// fetch instructors whitin the selected branch
await fetchInstructors(branchMeta);
} else {
// if admin and cs-admin
skeleton.classList.add("hidden");
arrowWarning.classList.remove("hidden");
}
} catch (error) {
console.error("Error during initialization:", error);
skeleton.classList.add("hidden");
lecturesCards.innerHTML =
      "<p>Failed to load initial data. Please refresh the page.</p>";
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
fetchBranchLectures(this.value),
fetchTracks(),
fetchInstructors(this.value),
]);

// show time options after successful fetches
showTimeOptions();

// reset time
groupTimeSelect.value = "";

await wait(1000);
skeleton.classList.add("hidden");
} catch (error) {
console.error("Error in branch change handler:", error);
lecturesCards.classList.remove("hidden");
lecturesCards.innerHTML = "<p>An error occurred. Please try again.</p>";
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
try {
showLoadingSkeleton();

// reset instructor and time when select a track
document.querySelector("#instructor option:first-child").selected = "true";
document.querySelector("#group-time option:first-child").selected = "true";

// reset groups when select no track
if (this.value == "") {
await fetchBranchLectures(branch.value);
await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
return;
}

// select track only when there a branch
if (branch.value) {
await fetchBranchAndTrackLec(branch.value, this.value);
}

await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
} catch (error) {
console.error("Error in track change:", error);
lecturesCards.innerHTML =
      "<p>Failed to load lectures. Please try again.</p>";
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
}
};

async function fetchBranchAndTrackLec(branchId, trackId) {
try {
const response = await fetch(
`functions/Lectures/get_lectures.php?branch_id=${branchId}&track_id=${trackId}`
);
if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}
const res = await response.json();

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
} else {
throw new Error(res.message || "Failed to fetch lectures");
}
} catch (error) {
console.error("Error fetching lectures:", error);
lecturesCards.innerHTML =
      "<p>Failed to load lectures. Please try again.</p>";
}
}

/** select Lectures by Group time */
groupTimeSelect.onchange = async function () {
try {
showLoadingSkeleton();

if (this.value == "") {
await fetchBranchLectures(branch.value);
await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
return;
}

let url = "";

if (branch.value) {
url = `functions/Lectures/get_lectures.php?branch_id=${branch.value}&time=${this.value}`;
} else {
url = `functions/Lectures/get_lectures.php?time=${this.value}`;
}

const response = await fetch(url);
if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}
const res = await response.json();

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
} else {
throw new Error(res.message || "Failed to fetch lectures");
}
} catch (error) {
console.error("Error in time change:", error);
lecturesCards.innerHTML =
      "<p>Failed to load lectures. Please try again.</p>";
} finally {
await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
}
};

/** select instructor */
instructor.onchange = async function () {
try {
showLoadingSkeleton();

if (this.value == "") {
await fetchBranchLectures(branch.value);
await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
return;
}

const response = await fetch(
`functions/Lectures/get_lectures.php?instructor_id=${this.value}`
);
if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}
const res = await response.json();

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
} else {
throw new Error(res.message || "Failed to fetch lectures");
}

// reset time
groupTimeSelect.value = "";
} catch (error) {
console.error("Error in instructor change:", error);
lecturesCards.innerHTML =
      "<p>Failed to load lectures. Please try again.</p>";
} finally {
await wait(1000);
skeleton.classList.add("hidden");
lecturesCards.classList.remove("hidden");
}
};

/** set card */
function setCard(lec) {
let indicatorColor = "";
let trackName = lec.track_name.toLowerCase();
if (trackName == "php" || trackName.toLowerCase().includes('database') || trackName.includes('project')) {
indicatorColor = `bg-red-500`;
} else if(trackName == "html" || trackName.includes('css')) {
indicatorColor = `bg-green-500`;
} else if(trackName.includes('javascript')){
indicatorColor = `bg-cyan-500`;
}
return `
<li class="">
    <div class="relative">
        <div
            class="bg-white rounded-2xl shadow-sm transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 px-6 py-2 traking-wider">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                              <path fill-rule="evenodd" d="M2.25 6a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V6Zm3.97.97a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 0 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Zm4.28 4.28a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg tracking-wider font-bold text-white">${capitalizeFirstLetter(lec.group_name)}</h2>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-white font-semibold">${
          lec.group_time == 2 || lec.group_time == 5
            ? lec.group_time + " - Friday"
            : lec.group_time == 8
            ? "Online"
            : lec.group_time
        }</div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex flex-wrap gap-3 mb-4">
                    <div class="flex items-center gap-2 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200">
                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg><span class="text-amber-700 font-medium text-sm">${lec.group_start_date}</span>
                    </div>
                    <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg><span class="text-blue-700 font-medium text-sm">${
          lec.group_time == 2 || lec.group_time == 5
            ? "Friday"
            : lec.group_time == 8
            ? "Online"
            : lec.group_time
        }</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-r bg-cyan-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">${lec.instructor_name[0].toUpperCase()}</span>
                    </div>
                    <div>
                        <div class="text-gray-600 text-xs capitalize tracking-wide">
                            Instructor
                        </div>
                        <div class="font-semibold text-gray-800">${capitalizeFirstLetter( lec.instructor_name)}</div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                clip-rule="evenodd"></path>
                        </svg><span class="text-gray-500 text-sm">Last comment • ${lec.latest_comment_date}</span>
                    </div>
                </div>
                <div class="relative bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-100 h-[93px]">
                    <div class="absolute -top-3 right-4">
                        <span
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg relative">${capitalizeFirstLetter(lec.track_name)}
                            <div
                                class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white">
                            </div>
                        </span>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-700 font-medium" dir="rtl" >${lec.comment}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
`;
}

/** fetch branch lectures */
async function fetchBranchLectures(value) {
try {
const response = await fetch(
`functions/Lectures/get_lectures.php?branch_id=${value}`
);
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
throw new Error(res.message || "Failed to fetch lectures");
}
} catch (error) {
console.error("Error fetching lectures:", error);
lecturesCards.innerHTML =
      "<p>Failed to load lectures. Please try again.</p>";
}
}

/** Fetch instructors based on selected branch */
async function fetchInstructors(branchId) {
try {
const response = await fetch(
`functions/Instructors/get_instructors.php?branch_id=${branchId}`
);
if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}
const res = await response.json();

instructor.innerHTML = "<option value=''>Choose Instructor</option>";
if (res.data) {
res.data.forEach((instructorData) => {
const option = document.createElement("option");
option.value = instructorData.id;
option.textContent = capitalizeFirstLetter(instructorData.username);
instructor.appendChild(option);
});
}
} catch (error) {
console.error("Error fetching instructors:", error);
instructor.innerHTML =
      "<option value=''>Error loading instructors</option>";
}
}

/** show time options */
function showTimeOptions() {
const time = [10, 12.30, 3, 6, 8, 2, 5];
groupTimeSelect.innerHTML = "<option value=''>Select Group Time</option>";
time.forEach((time) => {
const option = document.createElement("option");
option.value = time;
option.textContent = time;
option.classList.add("font-semibold");
if (time == 8) {
option.textContent = "Online";
} else if (time == 2 || time == 5) {
option.textContent = `${time} [ Friday ]`;
}
groupTimeSelect.appendChild(option);
});
}

/** reset other selects when nulling the branches */
function resetAllWithNoBranch() {
// reset tracks , time and instructor when no branch selected
tracks.innerHTML = "<option value=''>Select Branch First</option>";
instructor.innerHTML = "<option value=''>Select Branch First</option>";
document.querySelector("#group-time option:first-child").innerHTML =
"Select Branch First";
document.querySelector("#group-time option:first-child").selected = "true";
let allOptions = document.querySelectorAll("#group-time option:not(:first-child)");
allOptions.forEach((option) => option.remove());
}

/** show Loading Skeleton */
function showLoadingSkeleton() {
// show skeleton and hide lectures cards and arrow warning
skeleton.classList.remove("hidden");
arrowWarning.classList.add("hidden");
lecturesCards.classList.add("hidden");
lecturesCards.innerHTML = ""; // Clear previous content
}
