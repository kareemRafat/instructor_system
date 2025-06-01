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
    lectures: [
      "1 - html intro and tags to link or image",
      "2 - html 5 & table - iframe - video - audio - table",
      "3 - html form & meta tags"
    ]
  },
  {
    track: "CSS",
    lectures: [
      "1 - css Intro",
      "2 - css Margin - padding - fonts",
      "3 - css display - float - position",
      "4 - css animation",
      "5 - Project"
    ]
  },
  {
    track: "JavaScript",
    lectures: [
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
      "12 - multi selector in js - intervals - jquery and cdn"
    ]
  },
  {
    track: "Bootstrap",
    lectures: [
      "13 - media query and bootstrap intro and components",
      "14 - bootstrap grid system",
      "15 - libraries and bootstrap task"
    ]
  },
  {
    track: "Vue JS",
    lectures: [
      "16 - vuejs intro",
      "17 - vuejs events - vuejs methods",
      "18 - vuejs - bootstrap Project"
    ]
  },
  {
    track: "PHP",
    lectures: [
      "1 - Php intro",
      "2 - php array - indexed , associative and multidimensional array",
      "3 - php file system",
      "4 - superglobals and http Requests - form",
      "5 - session",
      "6 - cookies - oop intro"
    ]
  },
  {
    track: "MySQL",
    lectures: [
      "1 - SQL intro - relations",
      "2 - Create - Read",
      "3 - insert - update - delete"
    ]
  },
  {
    track: "Project",
    lectures: [
      "3 - 4 lectures"
    ]
  },
  {
    track: "Ajax",
    lectures: []
  }
];

console.log(courseContent);
