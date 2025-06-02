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

function selectLecture(el) {
  input.value = el.textContent;
  list.style.display = "none";
}

function showList() {
  list.style.display = "block";
}

function hideListDelayed() {
  setTimeout(() => (list.style.display = "none"), 200);
}

// track array
const courseContent = [
  {
    track: "HTML",
    lectures: {
      html: [
        "1 - html intro and tags to link or image",
        "2 - html 5 & table - iframe - video - audio - table",
        "3 - html form & meta tags",
      ],
    },
  },
  {
    track: "CSS",
    lectures: {
      css: [
        "1 - css Intro",
        "2 - css Margin - padding - fonts",
        "3 - css display - float - position",
        "4 - css animation",
        "5 - Project",
      ],
    },
  },
  {
    track: "JavaScript",
    lectures: {
      javascript: [
        "1 - javaScript intro",
        "2 - javaScript Functions",
        "3 - javaScript builtIn function && if condition",
        "4 - javaScript Date - Loops - Switch",
        "5 - javaScript intro - Dom - selectors - events",
        "6 - Get - set",
        "7 - Add and Remove elements - Css styles - Classes",
        "8 - Array",
        "9 - array functions",
        "10 - ecmaScript and object",
        "11 - nested ES object",
        "12 - multi selector in js - intervals - jquery and cdn",
      ],
      bootstrap: [
        "13 - media query and bootstrap intro and components",
        "14 - bootstrap grid system",
        "15 - libraries and bootstrap task",
      ],
      vuejs: [
        "16 - vuejs intro",
        "17 - vuejs events - vuejs methods",
        "18 - vuejs - bootstrap Project",
      ],
    },
  },
  {
    track: "PHP",
    lectures: {
      php: [
        "1 - Php intro - functions",
        "2 - php array",
        "3 - php file system",
        "4 - http Requests - form",
        "5 - session - cookies",
        "6 - oop into",
        "7 - oop inheritance , abstract and static",
        "8 - trait - interface - api",
      ],
    },
  },
  {
    track: "MySQL",
    lectures: {
      mysql: [
        "1 - SQL intro - relations",
        "2 - Create - Read",
        "3 - insert - update - delete",
      ],
    },
  },
  {
    track: "Project",
    lectures: {
      project: [
        "1 - project intro - html ",
        "2 - crud - login",
        "3 - image upload - design",
      ],
      ajax: ["1 - ajax"],
    },
  },
];

console.log(courseContent);

/** get track name */
track.oninput = function (e) {
  value = e.target.options[e.target.selectedIndex].text.toLowerCase();
  /** add courseContent to comment select */
  courseContent.forEach((content) => {
    if (content.track.toLowerCase() == value) {
      listItems(content , value);
    }
  });
};

/** list items */
function listItems(content , value) {
  list.innerHTML = "";

  let option = "";

  if (Object.keys(content.lectures).length > 1) {
    for (let i = 0; i < Object.keys(content.lectures).length; i++) {
      //! console.log(Object.keys(content.lectures)[value]);
      
      option += `
      <li class="px-3 py-1.5 text-zinc-500 bg-gray-100 font-semibold cursor-default">${Object.keys(content.lectures)[i]}</li>
      <li class="p-2 hover:bg-blue-600 hover:text-white cursor-pointer" onclick="selectLecture(this)">${content.lectures[i][value]}</li>
      <li class="p-2 hover:bg-blue-600 hover:text-white cursor-pointer" onclick="selectLecture(this)">محاضرة 2 - CSS</li>
    `;
    }
  } else {
    for(let i = 0; i < content.lectures[value].length; i++) {
      option += `
        <li class="p-2 hover:bg-blue-600 hover:text-white cursor-pointer" onclick="selectLecture(this)">${content.lectures[value][i]}</li>
      `;
    }
    
  }

  list.innerHTML += option;
}
