import { getQueryString } from "./helpers.js";

// Function to update query strings and reload the page
function updateQueryString(key, value) {
  const url = new URL(window.location);
  if (value) {
    url.searchParams.set(key, value); // Set or update the query param
  } else {
    removeQueryString(key); // Remove param if value is empty
  }
  // Reload the page with the updated URL
  window.location = url;
}

function removeQueryString(key) {
  const url = new URL(window.location.href);
  url.searchParams.delete(key);
  window.history.replaceState({}, '', url);
}

// Function to handle pagination
function goToPage(page) {
  updateQueryString("page", page);
}

// Event listeners for inputs
branchSelect.addEventListener("change", (e) => {
  const selectedBranch = e.target.value;

  // Remove 'page' from query string regardless of branch selection
  removeQueryString("page");

  if (selectedBranch) {
    // Update 'branch' query string with the selected value
    updateQueryString("branch", selectedBranch);
  } else {
    // Reset to base URL if no branch is selected
    window.location = window.location.pathname;
  }
});

const pageNum = document.querySelectorAll(".page-num");
pageNum.forEach((page) => {
  page.addEventListener("click", (e) => {
    e.preventDefault();
    const page = e.target.dataset.page;
    if (page) {
      goToPage(page);
    }
  });
});

// Initialize: Load query params on page load
window.addEventListener("load", () => {
  const params = new URLSearchParams(window.location.search);
  const branch = params.get("branch") || "";
  //   const search = params.get('search') || '';
  const page = params.get("page") || "1";

  // Set input values based on query params
  branchSelect.value = branch;
});
