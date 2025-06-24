import { getMetaContent, capitalizeFirstLetter, wait } from "./helpers.js";

const branch = document.getElementById("branch");
const instructor = document.getElementById("instructor");
const lecturesCards = document.getElementById("lecturesCards");
const groupTimeSelect = document.getElementById("group-time");
const tracks = document.getElementById("tracks");
const skeleton = document.getElementById("skeleton");
const arrowWarning = document.getElementById("arrow-warning");
const groupSearch = document.getElementById("group-search");

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
        if (roleMeta == "cs") {
          option.selected = branchMeta == br.id;
        }
        option.textContent = capitalizeFirstLetter(br.name);
        branch.appendChild(option);
      });
    }

    // when logged user is cs auto run the functions
    if (roleMeta == "cs") {
      await wait(300);
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

  /** search group event */
  const debouncedSearch = debounce(async function () {
    await fetchLecturesWhenSearch(this.value);
    resetOtherWhenSearch();
  }, 500);
  groupSearch.addEventListener("input", debouncedSearch);
  /** end search event */

  /** fetch groups when search */
  async function fetchLecturesWhenSearch(searchValue) {
    let url = null;

    // if not branch and remove search value don`t retrn cards
    if(!searchValue && !branch.value){
        lecturesCards.innerHTML = `<p id="arrow-warning"><i class="fas fa-arrow-up-long mr-2"></i>Select Branch</p>`;
        return ;
    };

    if (!branch.value) {
      if (roleMeta == 'cs') {
        // if the user is cs search with its branch_id found in meta
        url = `functions/Lectures/get_lectures.php?branch_id=${branchMeta}&search=${searchValue}`;
      } else {
        url = `functions/Lectures/get_lectures.php?search=${searchValue}`;
      }
    } else {
      url = `functions/Lectures/get_lectures.php?branch_id=${branch.value}&search=${searchValue}`;
    }

    try {
      showLoadingSkeleton();

      const response = await fetch(url);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const res = await response.json();

      await wait(300);
      skeleton.classList.add("hidden");

      if (res.status == "success") {
        if (res.data.length > 0) {
          lecturesCards.innerHTML = ""; // Clear previous cards
          
          res.data.forEach((lec) => {
            let card = setCard(lec);
            lecturesCards.innerHTML += card;
          });
        } else {
          lecturesCards.innerHTML = `<p><i class="text-gray-400 fa-solid fa-ban"></i> No lectures found</p>`;
        }
      } else {
        throw new Error(res.message || "Failed to fetch lectures");
      }
    } catch (error) {
      lecturesCards.innerHTML =
        "<p>Failed to load lectures. Please try again.</p>";
    }
  }

  /** select branch */
  branch.onchange = async function () {
    try {
      showLoadingSkeleton();

      if (!branch.value) {
        resetAllWithNoBranch();
        groupSearch.value = ""; // reset search
        await fetchBranchLectures(this.value);
        await wait(300);
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

      // reset search
      groupSearch.value = "";

      await wait(300);
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
      document.querySelector("#instructor option:first-child").selected =
        "true";
      document.querySelector("#group-time option:first-child").selected =
        "true";

      // reset search
      groupSearch.value = "";

      // reset groups when select no track
      if (this.value == "") {
        await fetchBranchLectures(branch.value);
        await wait(300);
        skeleton.classList.add("hidden");
        lecturesCards.classList.remove("hidden");
      }

      // select track only when there a branch
      if (branch.value) {
        await fetchBranchAndTrackLec(branch.value, this.value);
      }

      await wait(300);
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
          lecturesCards.innerHTML = `<p><i class="text-gray-400 fa-solid fa-ban"></i> No lectures found</p>`;
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

      // reset search
      groupSearch.value = "";

      if (this.value == "") {
        await fetchBranchLectures(branch.value);
        await wait(300);
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
          lecturesCards.innerHTML = `<p><i class="text-gray-400 fa-solid fa-ban"></i> No lectures found</p>`;
        }
      } else {
        throw new Error(res.message || "Failed to fetch lectures");
      }
    } catch (error) {
      console.error("Error in time change:", error);
      lecturesCards.innerHTML =
        "<p>Failed to load lectures. Please try again.</p>";
    } finally {
      await wait(300);
      skeleton.classList.add("hidden");
      lecturesCards.classList.remove("hidden");
    }
  };

  /** select instructor */
  instructor.onchange = async function () {
    const selectedBranch = branch.value;

    try {
      showLoadingSkeleton();

      // reset search
      groupSearch.value = "";
      tracks.value = "";
      groupTimeSelect.value = "";

      if (this.value == "") {
        await fetchBranchLectures(branch.value);
        await wait(300);
        skeleton.classList.add("hidden");
        lecturesCards.classList.remove("hidden");
        return;
      }

      const response = await fetch(
        `functions/Lectures/get_lectures.php?instructor_id=${this.value}&branch_id=${selectedBranch}`
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
          lecturesCards.innerHTML = `<p><i class="text-gray-400 fa-solid fa-ban"></i> No lectures found</p>`;
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
      await wait(300);
      skeleton.classList.add("hidden");
      lecturesCards.classList.remove("hidden");
    }
  };

  /** set card */
  function setCard(lec) {
    let indicatorColor = "";
    let trackName = lec.track_name.toLowerCase();
    if (
      trackName == "php" ||
      trackName.toLowerCase().includes("database") ||
      trackName.includes("project")
    ) {
      indicatorColor = `bg-red-500`;
    } else if (trackName == "html" || trackName.includes("css")) {
      indicatorColor = `bg-green-500`;
    } else if (trackName.includes("javascript")) {
      indicatorColor = `bg-cyan-500`;
    }
    return `
<li class="">
    <div class="relative">
        <div
            class="bg-white rounded-lg shadow-sm transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="card-header py-2 traking-wider">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 ml-3">
                        <div class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                              <path fill-rule="evenodd" d="M2.25 6a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V6Zm3.97.97a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 0 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Zm4.28 4.28a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="group-name text-lg tracking-wider text-gray-100">${capitalizeFirstLetter(
                              lec.group_name
                            )}</h2>
                        </div>
                    </div>
                    <div class="text-center text-base font-bold pr-6 flex +flex-row md:gap-2 items-center">
                        <div class="group-time text-white text-lg mb-0">${
                          lec.group_time == 2 || lec.group_time == 5
                            ? lec.group_time + " - Friday"
                            : lec.group_time == 8 || lec.group_time == 6.1
                            ? "Online " + Math.floor(lec.group_time)
                            : lec.group_time
                        }</div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex flex-col md:flex-row items-start flex-wrap gap-3 mb-4">
                    <div class="flex items-center gap-2 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200 w-full md:w-fit">
                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg><span class="text-amber-700 font-medium text-sm">${
                          lec.group_start_date
                        }</span>
                    </div>
                    <div class="flex items-center gap-2 bg-teal-50 px-2 py-1 rounded-lg border border-teal-200 w-full md:w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-teal-500">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>

                        </svg><span class="text-teal-700 font-medium text-sm">End : ${
                          lec.group_end_date
                        }</span>
                    </div>
                    
                </div>
                <div class="flex items-center gap-3 mb-4 p-3 bg-gray-100 rounded-lg">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gradient-to-r bg-cyan-600 rounded-full flex items-center justify-center">
                          <span class="text-white font-bold text-lg">${lec.instructor_name[0].toUpperCase()}</span>
                      </div>
                      <div>
                          <div class="text-gray-600 text-xs capitalize tracking-wide">
                              Instructor
                          </div>
                          <div class="font-semibold text-gray-800">${capitalizeFirstLetter(
                            lec.instructor_name
                          )}</div>
                      </div>
                  </div>
                  <div class="w-10 ms-auto md:mr-2">
                    ${groupDay(lec.group_day)}
                  </div>
                </div>
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                clip-rule="evenodd"></path>
                        </svg><span class="text-gray-500 text-sm">Last comment • ${
                          lec.latest_comment_date
                        }</span>
                    </div>
                </div>
                <div class="relative comment-background rounded-xl p-4 border border-blue-100 h-[93px]">
                    <div class="absolute -top-3 right-4">
                        <span
                            class="track-name text-white px-4 py-1 rounded text-sm font-bold shadow-lg relative">${capitalizeFirstLetter(
                              lec.track_name
                            )}
                            <div
                                class="absolute -top-1 -right-1 w-3 h-3 ${indicatorColor} rounded-full border-2 border-white">
                            </div>
                        </span>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-700 font-medium" dir="ltr" >${
                          lec.comment
                        }</p>
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
    // friday  2, 5 canceled - 4 online4 canceledd
    const time = [10, 12.3, 3, 6, 6.1, 8];
    groupTimeSelect.innerHTML = "<option value=''>Select Group Time</option>";
    time.forEach((time) => {
      const option = document.createElement("option");
      option.value = time;
      option.textContent = time;
      option.classList.add("font-semibold");
      if (time == 8 || time == 6.1 || time == 4) {
        option.textContent = `Online ${time.toFixed(0)}`;
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
    let allOptions = document.querySelectorAll(
      "#group-time option:not(:first-child)"
    );
    allOptions.forEach((option) => option.remove());
  }

  function resetOtherWhenSearch() {
    tracks.value = "";
    groupTimeSelect.value = "";
    if (!branch.value) {
      document.querySelector("#instructor option:first-child").innerHTML =
        "Select Branch First";
      document.querySelector("#instructor option:first-child").selected =
        "true";
    } else {
      instructor.value = "";
    }
  }

  /** show Loading Skeleton */
  function showLoadingSkeleton() {
    // show skeleton and hide lectures cards and arrow warning
    skeleton.classList.remove("hidden");
    arrowWarning.classList.add("hidden");
    lecturesCards.classList.add("hidden");
    lecturesCards.innerHTML = ""; // Clear previous content
  }

  /** group day svg */
  function groupDay(group_day) {
    if (group_day == "saturday") {
      return `<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 116.61"><defs><style>.cls-1{fill:gray;}.cls-2{fill:#fff;}.cls-2,.cls-3,.cls-4{fill-rule:evenodd;}.cls-3{fill:#ef4136;}.cls-4{fill:#c72b20;}.cls-5{fill:#1a1a1a;}</style></defs><title>week-day-saturday</title><path class="cls-1" d="M111.36,116.61H11.52A11.57,11.57,0,0,1,0,105.09V40H122.88v65.07a11.56,11.56,0,0,1-11.52,11.52Z"/><path class="cls-2" d="M12.92,112.92H110.3a9.09,9.09,0,0,0,9.06-9.06V44.94H3.86v58.92a9.09,9.09,0,0,0,9.06,9.06Z"/><path class="cls-3" d="M11.52,6.67h99.84a11.57,11.57,0,0,1,11.52,11.52V44.94H0V18.19A11.56,11.56,0,0,1,11.52,6.67Zm24.79,9.75A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Zm49.79,0a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M86.1,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.06,11.06,0,0,1,7.74-3.16Zm0,1.79a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M36.31,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.08,11.08,0,0,1,7.74-3.16Zm0,1.79A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-5" d="M80.54,4.56C80.54,2,83,0,86.1,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M30.75,4.56C30.75,2,33.24,0,36.31,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M25.24,92.15l1.22-7.24a29.88,29.88,0,0,0,7.26,1,42.22,42.22,0,0,0,5.2-.26V83l-4-.35c-3.56-.32-6-.74-7.34-2.13s-2-3.44-2-6.16q0-5.61,2.44-7.72t8.26-2.1a46.49,46.49,0,0,1,10.53,1.09l-1.1,7c-2.72-.44-4.9-1.1-6.53-1.1a32.78,32.78,0,0,0-4.17.22v2.59l3.16.31q5.74.57,7.93,2.74a8.08,8.08,0,0,1,2.2,6,12.8,12.8,0,0,1-.75,4.67A8,8,0,0,1,45.83,91a6.66,6.66,0,0,1-2.92,1.51,16.71,16.71,0,0,1-3.31.64c-1,.07-2.21.11-3.79.11a46.29,46.29,0,0,1-10.57-1.14Zm33.59.48H49.58l7.1-27.41H70.23l7.1,27.41H68.08l-1-4.34H59.84l-1,4.34Zm4.38-19-1.79,7.68h4L63.7,73.64Zm34.43-1.4H91.28V92.63H82.51V72.24H76.15v-7H97.64v7Z"/></svg>`;
    } else if (group_day == "sunday") {
      return `<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 116.61"><defs><style>.cls-1{fill:gray;}.cls-2{fill:#fff;}.cls-2,.cls-3,.cls-4{fill-rule:evenodd;}.cls-3{fill:#ef4136;}.cls-4{fill:#c72b20;}.cls-5{fill:#1a1a1a;}</style></defs><title>week-day-sunday</title><path class="cls-1" d="M111.36,116.61H11.52A11.57,11.57,0,0,1,0,105.09V40H122.88v65.07a11.56,11.56,0,0,1-11.52,11.52Z"/><path class="cls-2" d="M12.75,112.92h97.38a9.1,9.1,0,0,0,9.06-9.06V44.94H3.69v58.92a9.08,9.08,0,0,0,9.06,9.06Z"/><path class="cls-3" d="M11.52,6.67h99.84a11.57,11.57,0,0,1,11.52,11.52V44.94H0V18.19A11.56,11.56,0,0,1,11.52,6.67Zm24.79,9.75A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Zm49.79,0a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M86.1,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.06,11.06,0,0,1,7.74-3.16Zm0,1.79a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M36.31,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.08,11.08,0,0,1,7.74-3.16Zm0,1.79A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-5" d="M80.54,4.56C80.54,2,83,0,86.1,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M30.75,4.56C30.75,2,33.24,0,36.31,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M22.1,92.15l1.23-7.24a29.88,29.88,0,0,0,7.26,1,41.93,41.93,0,0,0,5.19-.26V83.47l-3.94-.35c-3.57-.33-6-1.18-7.35-2.57s-2-3.44-2-6.16q0-5.61,2.43-7.72t8.27-2.1a46.39,46.39,0,0,1,10.52,1.09l-1.09,7A43.08,43.08,0,0,0,36.09,72a32.72,32.72,0,0,0-4.16.22v2.15l3.15.31q5.74.57,7.94,2.74a8.12,8.12,0,0,1,2.19,6,12.82,12.82,0,0,1-.74,4.67A8,8,0,0,1,42.69,91a6.61,6.61,0,0,1-2.91,1.51,16.71,16.71,0,0,1-3.31.64c-.95.07-2.22.11-3.8.11A46.35,46.35,0,0,1,22.1,92.15ZM57.27,65.22V85.79h3.12a4.47,4.47,0,0,0,2.28-.41c.41-.28.61-.92.61-1.91V65.22h8.77v15.4a30.2,30.2,0,0,1-.48,6,8.69,8.69,0,0,1-1.8,3.86,6.81,6.81,0,0,1-3.59,2.2,23.43,23.43,0,0,1-5.92.61,23.1,23.1,0,0,1-5.9-.61,6.82,6.82,0,0,1-3.58-2.2A8.78,8.78,0,0,1,49,86.62a29.36,29.36,0,0,1-.49-6V65.22ZM92.53,92.63,85.82,82.9a4.59,4.59,0,0,1-.44-2.11h-.17V92.63H76.44V65.22h8.24L91.39,75a4.41,4.41,0,0,1,.44,2.1H92V65.22h8.77V92.63Z"/></svg>`;
    } else if (group_day == "monday") {
      return `<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 116.61"><defs><style>.cls-1{fill:gray;}.cls-2{fill:#fff;}.cls-2,.cls-3,.cls-4{fill-rule:evenodd;}.cls-3{fill:#ef4136;}.cls-4{fill:#c72b20;}.cls-5{fill:#1a1a1a;}</style></defs><title>week-day-monday</title><path class="cls-1" d="M111.36,116.61H11.52A11.57,11.57,0,0,1,0,105.09V40H122.88v65.07a11.56,11.56,0,0,1-11.52,11.52Z"/><path class="cls-2" d="M12.75,112.92h97.38a9.1,9.1,0,0,0,9.06-9.06V44.94H3.69v58.92a9.08,9.08,0,0,0,9.06,9.06Z"/><path class="cls-3" d="M11.52,6.67h99.84a11.57,11.57,0,0,1,11.52,11.52V44.94H0V18.19A11.56,11.56,0,0,1,11.52,6.67Zm24.79,9.75A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Zm49.79,0a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M86.1,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.06,11.06,0,0,1,7.74-3.16Zm0,1.79a9.31,9.31,0,1,1-9.31,9.31,9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-4" d="M36.31,14.63a11.11,11.11,0,1,1-7.85,3.26l.11-.1a11.08,11.08,0,0,1,7.74-3.16Zm0,1.79A9.31,9.31,0,1,1,27,25.73a9.31,9.31,0,0,1,9.31-9.31Z"/><path class="cls-5" d="M80.54,4.56C80.54,2,83,0,86.1,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M30.75,4.56C30.75,2,33.24,0,36.31,0s5.56,2,5.56,4.56V25.77c0,2.51-2.48,4.56-5.56,4.56s-5.56-2-5.56-4.56V4.56Z"/><path class="cls-5" d="M26,92.63H16.8l1.66-27.41H29.91l3.42,14h.31l3.42-14H48.5l1.67,27.41H41l-.52-13.29h-.31L36.84,92.63H30.13L26.75,79.34h-.26L26,92.63ZM52.47,79q0-7.5,2.81-10.94t10.13-3.44q7.32,0,10.13,3.44T78.35,79a28.46,28.46,0,0,1-.59,6.27,11.57,11.57,0,0,1-2,4.43,8.25,8.25,0,0,1-4,2.76,19.34,19.34,0,0,1-6.31.88,19.38,19.38,0,0,1-6.31-.88,8.18,8.18,0,0,1-4-2.76,11.44,11.44,0,0,1-2-4.43A28.46,28.46,0,0,1,52.47,79Zm9.43-4.56v11.4h3.64a6.19,6.19,0,0,0,2.61-.41c.54-.28.81-.92.81-1.91V72.06H65.28a5.89,5.89,0,0,0-2.57.42c-.54.28-.81.92-.81,1.91ZM97.84,92.63,91.13,82.9a4.59,4.59,0,0,1-.44-2.11h-.17V92.63H81.75V65.22H90L96.7,75a4.54,4.54,0,0,1,.44,2.1h.18V65.22h8.76V92.63Z"/></svg>`;
    }
  }

  /** debounce in search */
  function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        func.apply(this, args);
      }, delay);
    };
  }
  
});
