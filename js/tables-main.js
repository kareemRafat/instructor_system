const groupBtn = document.querySelectorAll("button.outline-none");

document.addEventListener("DOMContentLoaded", async () => {
  groupBtn.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      document.getElementById('drawer-left-example').scrollTop = 0; // Scroll to top

      const id = e.currentTarget.dataset.groupId;
      const url = "functions/Tables/get_group.php";

      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          group_id: id,
          branch_id: getQueryString("branch") || 1,
        }),
      });

      const res = await response.json();

      if (res.data) {
        setToDom(res.data);

        if (res.data.name.toLowerCase().includes("training")) {
          document.getElementById("finish-btn").classList.add("hidden");
          document
            .getElementById("finish-training-btn")
            .classList.remove("hidden");
        } else {
          document.getElementById("finish-btn").classList.remove("hidden");
          document
            .getElementById("finish-training-btn")
            .classList.add("hidden");
        }

        finishTrainingGroups();
      }
    });
  });

  /** set Data To DOM */
  function setToDom(groupData) {
    document.getElementById(
      "edit-btn"
    ).href = `groups.php?action=edit&group_id=${groupData.id}`;
    document.getElementById(
      "finish-btn"
    ).href = `groups.php?action=finish_group&group_id=${groupData.id}`;
    document.getElementById("finish-training-btn").dataset.groupId =
      groupData.id;

    document.getElementById("drawerGroup").textContent = capitalizeFirstLetter(
      groupData.name
    );
    document.getElementById("drawerGroup2").textContent = capitalizeFirstLetter(
      groupData.name
    );
    document.getElementById("drawerTrack").textContent =
      capitalizeFirstLetter(groupData.track_name) || "Not Updated";
    document.getElementById("langIcon").innerHTML = setLangIcon(
      groupData.track_name
    );
    document.getElementById("drawerTime").textContent = displayTime(
      groupData.time
    );
    document.getElementById("drawerDay").textContent = capitalizeFirstLetter(
      groupData.day
    );
    document.getElementById("drawerInstructor").textContent =
      capitalizeFirstLetter(groupData.instructor_name);
    document.getElementById("drawerBranch").textContent = capitalizeFirstLetter(
      groupData.branch_name
    );
    document.getElementById("drawerStartMonth").innerHTML =
      capitalizeFirstLetter(groupData.month);
    document.getElementById("drawerStartDate").innerHTML = groupData.start_date;
    document.getElementById("drawerEndMonth").innerHTML = capitalizeFirstLetter(
      groupData.group_end_month
    );
    document.getElementById("drawerEndDate").innerHTML =
      groupData.group_end_date;
    document.getElementById("today").innerHTML = getTodayDate();
    document.getElementById("time-left").innerHTML = getTimeRemaining(
      groupData.start_date,groupData.name
    );
    document.getElementById("time-left2").innerHTML = getTimeRemaining(
      groupData.start_date,groupData.name
    );
  }

  /** show real time group time (6.10 to online 6) */
  const displayTime = (group_time) => {
    const gTime = +group_time;
    if (gTime === 2 || gTime === 5) {
      return `${group_time} - Friday`;
    } else if (gTime === 6.1 || gTime === 8) {
      return `Online ${Math.floor(group_time)}`;
    } else {
      return `${group_time}`;
    }
  };

  /** set icon with track */
  function setLangIcon(lang) {
    if (!lang) return "";
    switch (lang.toLowerCase()) {
      case "html":
        return `<i class="fa-brands fa-html5 text-5xl text-slate-700"></i>`;
        break;
      case "css":
        return `<i class="fa-brands fa-css text-5xl text-slate-700"></i>`;
        break;
      case "javascript":
        return `<i class="fa-brands fa-js text-5xl text-slate-700"></i>`;
        break;
      case "php":
        return `<img class="w-[55px]" src="images/php.png" >`;
        break;
      case "database":
        return `<img class="w-20" src="https://www.mysql.com/common/logos/logo-mysql-170x115.png" >`;
        break;
      case "project":
        break;
      case "Not Updated":
        return "";
        break;
      default:
        return "";
        break;
    }
  }

  /** Finish Training groups */
  function finishTrainingGroups() {
    // i used event delegation because the finish btn added to the dom when search
    document
      .getElementById("finish-training-btn")
      .addEventListener("click", async (e) => {
        e.preventDefault();
        const groupId = e.target.dataset.groupId;

        if (
          confirm(
            "Are you sure you want to mark this Training group as finished?"
          )
        ) {
          try {
            const response = await fetch(
              "functions/Groups/finish_training_group.php",
              {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `id=${encodeURIComponent(groupId)}`,
              }
            );

            const result = await response.json();
            if (result.status === "success") {
              // create php session to make notfy toaster
              // and create session['page'] = tables.php to use it in headers
              await fetch("functions/Tables/flash_session.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `session_name=Training Group Finished Successfully`,
              });

              //reload page after finish
              window.location.reload();
            }
          } catch (error) {
            alert("Request failed.");
            console.error(error);
          }
        }
      });
  }

  /** query string get */
  function getQueryString(key) {
    const params = new URLSearchParams(window.location.search);
    return params.get(key);
  }

  /** helper functions */
  function capitalizeFirstLetter(value) {
    if (typeof value !== "string" || value.length === 0) {
      return value;
    }
    return value.charAt(0).toUpperCase() + value.slice(1);
  }

  /** get Today */
  function getTodayDate(date = new Date()) {
    const monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];

    const day = String(date.getDate()).padStart(2, "0");
    const monthNumber = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    const monthName = monthNames[date.getMonth()];

    return `
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">NOW</p>
    <p class="month">${monthName}</p>
    <p class="date">${day}-${monthNumber}-${year}</p>
  `;
  }

  /** get how much time to end */
  function getTimeRemaining(startDateStr, groupName) {
    // Parse the start date (DD-MM-YYYY format)
    const [day, month, year] = startDateStr.split("-").map(Number);
    const startDate = new Date(year, month - 1, day);

    // Determine duration based on group name
    const isTraining = groupName.toLowerCase().includes("training");
    const monthsToAdd = isTraining ? 2 : 5;
    const daysToAdd = isTraining ? 15 : 14;

    // Calculate target date
    const targetDate = new Date(startDate);
    targetDate.setMonth(targetDate.getMonth() + monthsToAdd);
    targetDate.setDate(targetDate.getDate() + daysToAdd);

    // Get current date
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Normalize to start of day

    // If target date has passed
    if (targetDate < today) {
      return `<p class="time-remaining">Completed</p>`;
    }

    // Calculate remaining time (more precise calculation)
    let monthsRemaining = targetDate.getMonth() - today.getMonth();
    let yearsDiff = targetDate.getFullYear() - today.getFullYear();

    // Adjust for year crossover
    monthsRemaining += yearsDiff * 12;

    // Calculate remaining days
    const tempDate = new Date(today);
    tempDate.setMonth(tempDate.getMonth() + monthsRemaining);

    let daysRemaining = Math.floor(
      (targetDate - tempDate) / (1000 * 60 * 60 * 24)
    );

    // Adjust if days are negative (month rollover)
    if (daysRemaining < 0) {
      monthsRemaining--;
      tempDate.setMonth(tempDate.getMonth() - 1);
      daysRemaining = Math.floor(
        (targetDate - tempDate) / (1000 * 60 * 60 * 24)
      );
    }

    return `
    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Time Left</p>
    <p class="time-remaining text-orange-500">${monthsRemaining} Months</p>
    <p class="time-remaining">${daysRemaining} Days</p>
  `;

  }
});
