
const groupSelect = document.getElementById("group");
const track = document.getElementById("track");

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
/** when select group autoselect the track */
groupSelect.oninput = function(){
    const groupId = this.value;

    fetch(`functions/Lectures/get_group_track.php?group_id=${groupId}`)
      .then((response) => response.json())
      .then((res) => {        
        if(res.data) {
            let opt = document.querySelector(`#track option[value="${res.data.track_id}"]`); 
            opt.selected = true ;
        } else {
            document.querySelector(`#track option[value="1"]`).selected = true ;
        }
      })
      .catch((error) => console.error("Error fetching tracks:", error));
}

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}
