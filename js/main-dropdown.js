import { capitalizeFirstLetter } from "./helpers.js";

const commentInput = document.getElementById("comment-input");
const list = document.getElementById("lecture-list");
const searchClear = document.getElementById('clear-search');
let optionItem = null ;

function filterLectures() {
  const query = commentInput.value.toLowerCase();
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

commentInput.addEventListener("input", filterLectures);
commentInput.addEventListener('keyup' , function(){
  searchClear.classList.remove('hidden');
})

function selectLecture() {
  commentInput.value = this.innerText;
  list.style.display = "none";
  searchClear.classList.remove('hidden');
}

function showList() {
  list.style.display = "block";
  setTimeout(() => {
    list.scrollTop = 0;
  }, 50); // slight delay to ensure rendering on mobile
}

commentInput.addEventListener("focus", showList);

function hideListDelayed() {
  setTimeout(() => (list.style.display = "none"), 200);
}

commentInput.addEventListener("blur", hideListDelayed);

searchClear.addEventListener('click' , function(){
  commentInput.value = '';
  this.classList.add('hidden');
})

// track array
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
        "13 - libraries and bootstrap task",
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
    commentInput.value = '';
    list.innerHTML = `<li class="text-left px-3 py-1 text-gray-500 font-semibold cursor-default">Select Track First</li>`;
  }

  /** add courseContent to comment select */
  courseContent.forEach((content) => {
    if (content.track.toLowerCase() == value) {
      listItems(content, value);
    }
  });

  resetListScroll();

  // add click listener to every li in comment to choose when click
  optionItem.forEach(item => {
    item.addEventListener('click' , selectLecture);
  })
   
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
        option += `<li class="option-li p-2 hover:bg-blue-600 hover:text-white cursor-pointer">
          ${capitalizeFirstLetter(sub)}
        </li>`;
      });
    }
  } else {
    for (let i = 0; i < content.lectures[value].length; i++) {
      let subTrack = content.lectures[value][i];
      
      option += `
        <li class="option-li p-2 hover:bg-blue-600 hover:text-white cursor-pointer">${capitalizeFirstLetter(
          subTrack
        )}</li>
      `;
    }
  }
  list.style.display = "block";
  list.innerHTML += option;

  optionItem = document.querySelectorAll('.option-li');
}

/** reset comment list scroll  */
function resetListScroll(){
  list.style.display = "block"; 
  list.scrollTop = 0;
  list.style.display = "none";
}