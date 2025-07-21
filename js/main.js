import { capitalizeFirstLetter } from "./helpers.js";
import { courseContent } from "./course-content.js";

const groupSelect = document.getElementById("group");
const track = document.getElementById("track");
const startDate = document.getElementById("start-date");
const endDate = document.getElementById("end-date");
const latestComment = document.getElementById('latest-comment');
const latestCommentDate = document.getElementById('latest-comment-date');

document.addEventListener("DOMContentLoaded", () => {
  // Fetch instructor Groups
  fetch("functions/Groups/get_groups.php")
    .then((response) => response.json())
    .then((res) => {      
      const branches = [...new Set(res.data.map((d) => d["branch_name"]))];
      branches.forEach((branch) => {
        const optgroup = document.createElement("optgroup");
        optgroup.className = 'capitalize text-base font-normal'
        optgroup.label = branch;
        if (res.data) {
          res.data.forEach((group) => {
            if (res.isMultiBranch) {
              const option = document.createElement("option");
              if (group.branch_name == branch) {
                option.value = group.id;
                option.textContent = capitalizeFirstLetter(group.name);
                optgroup.append(option);
                if (!group.name.toLowerCase().includes("training")) {
                  groupSelect.append(optgroup);
                }
              }
            } else {
              const option = document.createElement("option");
              option.value = group.id;
              option.textContent = capitalizeFirstLetter(group.name);
              if (!group.name.toLowerCase().includes("training")) {
                groupSelect.append(option);
              }
            }
          });
        }
      });
      setTimeout(() => {
        document.getElementById('preload').classList.add('hidden');
      }, 500);
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

/** populate lectures in the comment slimselect   */
track.addEventListener("change", function () {
  populateLectures(this.value);
});

/** Fetch tracks based on selected group */
groupSelect.oninput = async function () {
  const groupId = this.value;
  if (!groupId) {
    // Reset track , dates if no group is selected
    track.value = "";
    startDate.innerText = "Group Start Date";
    endDate.innerText = "Excpected End Date";
    latestComment.innerText = "Latest Comment";
    latestCommentDate.innerText = "";
    document.querySelector('i.fa-message').classList.remove("!text-rose-600");
    populateLectures(null); // reset comment box
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

  if(res.empty) {
    track.value = "";
  }

  if (res.data) {
    let opt = document.querySelector(
      `#track option[value="${res.data.track_id}"]`
    );
    opt.selected = true;

    // Trigger change event manually to populate lectrues in comment select box
    track.value = res.data.track_id;
    const event = new Event("change", { bubbles: true });
    track.dispatchEvent(event);
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
    latestComment.innerText = res.data.comment.length > 36 ? res.data.comment.substring(0, 36) + ' ...' : res.data.comment;
    latestCommentDate.innerText = res.data.date;
    document.querySelector('i.fa-message').classList.remove("!text-rose-600");
     
  } else {
    latestComment.innerHTML = "<span class='text-rose-600'>No Comments yet ... </span>";
    latestCommentDate.innerText = "";
    document.querySelector('i.fa-message').classList.add("!text-rose-600");
  }
}

/** Function to populate the select element with options */
function populateLectures(trackValue) {
  const commentSelect = document.getElementById("comment-input");

  // Destroy existing SlimSelect instance if it exists
  if (window.slimSelect) {
    window.slimSelect.destroy();
  }

  if (!trackValue) {
    commentSelect.innerHTML = '<option value="">Select Track First</option>';
  } else {
    commentSelect.innerHTML = '<option value="">Search for Lectures</option>';
  }

  // Find the selected track in courseContent
  const selectedTrack = courseContent.find((item) => {
    const trackMap = {
      1: "HTML",
      2: "CSS",
      3: "JavaScript",
      4: "PHP",
      5: "database",
      6: "Project",
    };
    return item.track === trackMap[trackValue];
  });

  if (!selectedTrack) return;

  // Get all lecture categories for the selected track
  const lectureCategories = Object.keys(selectedTrack.lectures);

  // If there's only one category, don't use optgroup
  if (lectureCategories.length === 1) {
    const lectures = selectedTrack.lectures[lectureCategories[0]];
    lectures.forEach((lecture, index) => {
      const option = document.createElement("option");
      option.value = lecture;
      option.textContent = lecture;
      commentSelect.appendChild(option);
    });
  } else {
    // If multiple categories, use optgroup
    lectureCategories.forEach((category) => {
      const optgroup = document.createElement("optgroup");
      optgroup.label = category;

      selectedTrack.lectures[category].forEach((lecture) => {
        const option = document.createElement("option");
        option.value = lecture;
        option.textContent = lecture;
        optgroup.appendChild(option);
      });

      commentSelect.appendChild(optgroup);
    });
  }

  // Store the SlimSelect instance globally so we can destroy it later
  window.slimSelect = new SlimSelect({
    select: "#comment-input",
    settings: {
      placeholderText: "Search for Lectures",
      allowDeselect: true,
      closeOnSelect: true,
      showSearch: false,
      searchPlaceholder: "Search Lectures...",
      searchText: "No Results",
      searchHighlight: true,
    },
    events: {
      beforeOpen: () => {
        // Optional: force body scroll
        document.body.style.overflow = "auto";
      },
      beforeClose: () => {
        // Restore if needed
        document.body.style.overflow = "";
      },
    },
  });
}
