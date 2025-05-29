/** get roles from meta */
function getMetaContent(value) {
  return document.querySelector(`meta[name="${value}"]`)?.content || null;
}

/** helper functions */
function capitalizeFirstLetter(value) {
  if (typeof value !== "string" || value.length === 0) {
    return value;
  }
  return value.charAt(0).toUpperCase() + value.slice(1);
}

/** wait */
function wait(time) {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve();
      lecturesCards.classList.remove("hidden");
    }, time);
  });
  
}

/** query string get */
function getQueryString(key) {
  const params = new URLSearchParams(window.location.search);
  return params.get(key) || null;
}

/** query string set */
function setQueryString(key, value) {
  const url = new URL(window.location);
  if (value) {
    url.searchParams.set(key, value); // Set or update the query param
  } else {
    url.searchParams.delete(key); // Remove param if value is empty
  }
  // Reload the page with the updated URL
  // window.location = url;
}

export { getMetaContent, capitalizeFirstLetter , wait , getQueryString, setQueryString };