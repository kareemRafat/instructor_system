const courseContent = [
  {
    track: "HTML",
    lectures: {
      html: [
        "1 - HTML intro and tags to link or image",
        "2 - HTML 5 table - video - audio",
        "3 - HTML form & meta tags",
      ],
    },
  },
  {
    track: "CSS",
    lectures: {
      css: [
        "1 - CSS Intro",
        "2 - CSS Margin - padding - fonts",
        "3 - CSS display - float - position",
        "4 - CSS animation",
        "5 - CSS Project",
      ],
    },
  },
  {
    track: "JavaScript",
    lectures: {
      javascript: [
        "1 - JavaScript intro",
        "2 - JavaScript Functions",
        "3 - JavaScript bi function & if",
        "4 - JavaScript Date - Loops - Switch",
        "5 - JavaScript intro - Dom - selectors - events",
        "6 - JavaScript Get - set",
        "7 - JavaScript Elements - Css styles - Classes",
        "8 - JavaScript Array",
        "9 - JavaScript EcmaScript",
        "10 - JavaScript object",
        "11 - JavaScript nested ES object",
        "12 - JavaScript multi selector - jQuery and cdn",
      ],
      bootstrap: [
        "13 - Libraries",
        "14 - media query and Bootstrap intro - components",
        "15 - grid system",
      ],
      vuejs: [
        "16 - Vuejs intro",
        "17 - Vuejs events - methods - form",
        "18 - Vuejs - bootstrap Project",
      ],
    },
  },
  {
    track: "PHP",
    lectures: {
      php: [
        "1 - PHP intro - functions",
        "2 - PHP array",
        " PHP file system",
        "4 - Requests - PHP form",
        "5 - PHP session - cookies",
        "6 - OOP intro",
        "7 - OOP inheritance , abstract and static",
        "8 - OOP trait - interface - api",
      ],
    },
  },
  {
    track: "database",
    lectures: {
      database: [
        "1 - SQL intro - relations",
        "2 - Create - Read",
        "3 - Insert - Update - Delete",
      ],
    },
  },
  {
    track: "Project",
    lectures: {
      project: [
        "1 - Project intro - html ",
        "2 - Crud - Login",
        "3 - Image upload - Design",
      ],
      ajax: ["jQuery ajax"],
    },
  },
];

// Function to populate the select element with options
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
      showSearch: true,
      searchPlaceholder: "Search Lectures...",
      searchText: "No Results",
      searchHighlight: true,
    },
  });
}

// Add event listener to track select
document.getElementById("track").addEventListener("change", function () {
  populateLectures(this.value);
});
