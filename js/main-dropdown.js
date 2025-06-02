import { capitalizeFirstLetter } from "./helpers.js";

const input = document.getElementById("lecture-input");
const list = document.getElementById("lecture-list");

function filterLectures() {
  const query = input.value.toLowerCase();
  const items = list.querySelectorAll("li");
  let hasMatch = false;
  items.forEach((item) => {
    if (item.textContent.toLowerCase().includes(query)) {
      item.style.display = "block";
      hasMatch = true;
    } else {
      item.style.display = "none";
    }
  });
  list.style.display = hasMatch ? "block" : "none";
}

input.addEventListener("input", filterLectures);

function selectLecture(el) {
  input.value = el.textContent;
  list.style.display = "none";
}

function showList() {
  list.style.display = "block";
}

input.addEventListener("focus", showList);

function hideListDelayed() {
  setTimeout(() => (list.style.display = "none"), 200);
}

input.addEventListener("blur", hideListDelayed);

// track array
const courseContent = [
  {
    track: "HTML",
    lectures: {
      html: [
        "1 - HTML intro and tags to link or image",
        "2 - HTML 5 & table - iframe - video - audio - table",
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
        "3 - JavaScript builtIn function && if condition",
        "4 - JavaScript Date - Loops - Switch",
        "5 - JavaScript intro - Dom - selectors - events",
        "6 - JavaScript Get - set",
        "7 - JavaScript Add and Remove elements - Css styles - Classes",
        "8 - JavaScript Array",
        "9 - JavaScript EcmaScript",
        "10 - JavaScript object",
        "11 - JavaScript nested ES object",
        "12 - JavaScript multi selector in js - jquery and cdn",
      ],
      bootstrap: [
        "13 - libraries and bootstrap task",
        "14 - media query and Bootstrap intro and components",
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
        "3 - PHP file system",
        "4 - Requests - PHP form",
        "5 - PHP session - cookies",
        "6 - OOP into",
        "7 - OOP inheritance , abstract and static",
        "8 - OOP trait - interface - api",
      ],
    },
  },
  {
    track: "MySQL",
    lectures: {
      mysql: [
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

/** get track name */
track.oninput = function (e) {
  let value = e.target.options[e.target.selectedIndex].text.toLowerCase();

  if (!this.value) {
    list.innerHTML = `<li class="text-left px-3 py-1 text-gray-500 font-semibold cursor-default">Select Track First</li>`;
  }

  /** add courseContent to comment select */
  courseContent.forEach((content) => {
    if (content.track.toLowerCase() == value) {
      listItems(content, value);
    }
  });

  resetListScroll();
   
};

/** list items */
function listItems(content, value) {
  list.innerHTML = "";

  let option = "";
  let trackName = Object.keys(content.lectures);

  if (trackName.length > 1) {
    for (let i = 0; i < trackName.length; i++) {
      const subTrack = content.lectures[trackName[i]];
      // optgroup like
      option += `<li class="px-3 py-1.5 text-zinc-500 bg-gray-100 font-semibold cursor-default">${capitalizeFirstLetter(
        trackName[i]
      )}</li>`;
      // option like
      subTrack.forEach((sub) => {
        option += `<li class="p-2 hover:bg-blue-600 hover:text-white cursor-pointer" onclick="selectLecture(this)">
          ${capitalizeFirstLetter(sub)}
        </li>`;
      });
    }
  } else {
    for (let i = 0; i < content.lectures[value].length; i++) {
      let subTrack = content.lectures[value][i];
      console.log(capitalizeFirstLetter(subTrack));
      
      option += `
        <li class="p-2 hover:bg-blue-600 hover:text-white cursor-pointer" onclick="selectLecture(this)">${capitalizeFirstLetter(
          subTrack
        )}</li>
      `;
    }
  }
  list.innerHTML += option;
}

/** reset comment list scroll  */
function resetListScroll(){
  list.style.display = "block"; 
  list.scrollTop = 0;
  list.style.display = "none";
}