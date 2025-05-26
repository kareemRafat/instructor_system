import { capitalizeFirstLetter } from "./helpers.js";

const groupSelect = document.getElementById("group");
const track = document.getElementById("track");
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
          groupSelect.append(option);
        });
      }
    })
    .catch((error) => console.error("Error fetching groups:", error));

  // Fetch tracks
  fetch("functions/Tracks/get_tracks.php")
    .then((response) => response.json())
    .then((res) => {
      track.innerHTML = "";
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
    // Reset track and dates if no group is selected
    track.value = 1 ;
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
  } else {
    document.querySelector(`#track option[value="1"]`).selected = true;
  }
}

/** get group info */
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
