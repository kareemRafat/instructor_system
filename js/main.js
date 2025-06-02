import { capitalizeFirstLetter } from "./helpers.js";

const groupSelect = document.getElementById("group");
const track = document.getElementById("track");
const list = document.getElementById("lecture-list");
const startDate = document.getElementById("start-date");
const endDate = document.getElementById("end-date");

document.addEventListener("DOMContentLoaded", () => {
  // Fetch instructor Groups
  fetch("functions/Groups/get_groups.php")
    .then((response) => response.json())
    .then((res) => {
      if (res.data) {
        res.data.forEach((group) => {
          const option = document.createElement("option");
          option.value = group.id;
          option.textContent = capitalizeFirstLetter(group.name);
          if(!group.name.toLowerCase().includes('training')) {
            groupSelect.append(option);
          }
        });
      }
    })
    .catch((error) => console.error("Error fetching groups:", error));

  // Fetch tracks
  fetch("functions/Tracks/get_tracks.php")
    .then((response) => response.json())
    .then((res) => {
      track.innerHTML = `<option value="">Select Track</option>`;
      if (res.data) {
        res.data.forEach((trk) => {
          const option = document.createElement("option");
          option.value = trk.id;
          option.textContent = trk.name.toUpperCase();
          track.appendChild(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching groups:", error));
});

/** Fetch tracks based on selected group */
groupSelect.oninput = async function () {
  const groupId = this.value;
  if(!groupId) {
    // Reset track , list and dates if no group is selected
    track.value = '' ;
    list.innerHTML = `<li class="text-left px-3 py-1 text-gray-500 font-semibold cursor-default">Select Track First</li>`;
    startDate.innerText = "Group Start Date";
    endDate.innerText = "Excpected End Date";
    return;
  }

  try {
    await getGroupTrack(groupId);
    await getGroupInfo(groupId);
  } catch (error) {
    console.error("Error fetching tracks:", error);
  }

  resetListScroll();
};

/** when select group autoselect the track */
async function getGroupTrack(groupId) {
  const response = await fetch(
    `functions/Lectures/get_group_track.php?group_id=${groupId}`
  );
  const res = await response.json();
  if (res.data) {
    let opt = document.querySelector(
      `#track option[value="${res.data.track_id}"]`
    );
    opt.selected = true;

    // Trigger change event manually
    track.dispatchEvent(new Event("input", { bubbles: true }));

  }
}

/** get start and end date group info */
async function getGroupInfo(groupId) {
  const response = await fetch(
    `functions/Groups/get_group.php?group_id=${groupId}`
  );
  const res = await response.json();
  if (res.data) {
    startDate.innerText = res.data.formatted_date;
    endDate.innerText = `End : ` + res.data.group_end_date;
  }
}

/** reset comment list scroll  */
function resetListScroll(){
  list.style.display = "block"; 
  list.scrollTop = 0;
  list.style.display = "none";
}