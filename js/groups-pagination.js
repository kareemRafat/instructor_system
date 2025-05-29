import { getQueryString  } from "./helpers.js";

const branchSelect = document.getElementById("branchSelect");

// Function to update query strings and reload the page
function updateQueryString(key, value) {
  const url = new URL(window.location); // keeps existing params like 'branch'
  if (value) {
    url.searchParams.set(key, value);
  } else {
    url.searchParams.delete(key);
  }
  window.location = url;
}

function goToPage(page) {
  updateQueryString("page", page); // will preserve 'branch' if it exists
}

// Event listener for select dropdown
branchSelect.addEventListener("change", (e) => {
  const selectedBranch = e.target.value;

  // Remove 'page' from query string when changing branch
  const url = new URL(window.location);
  url.searchParams.delete("page");

  if (selectedBranch) {
    url.searchParams.set("branch", selectedBranch);
  } else {
    url.searchParams.delete("branch");
  }

  window.location = url;
});

// Pagination click
const pageNum = document.querySelectorAll(".page-num");
const next = document.querySelector(".page-num-next");

pageNum.forEach((page) => {
  page.addEventListener("click", (e) => {
    e.preventDefault();
    const page = e.target.dataset.page;
    if (page) {
      goToPage(page); // branch is preserved automatically
    }
  });
});

