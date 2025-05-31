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

/** wait */
function globalWait(time) {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve();
    }, time);
  });
  
}

/** query string get */
function getQueryString(key) {
  const params = new URLSearchParams(window.location.search);
  return params.get(key);
}

/** query string set */
function setQueryString(key, value) {
  const url = new URL(window.location);
  if (value) {
    url.searchParams.set(key, value); // Set or update the query param
  } else {
    url.searchParams.delete(key); // Remove param if value is empty
  }
  // window.history.pushState({}, '', url.toString());
}

export { getMetaContent, capitalizeFirstLetter , wait , getQueryString, setQueryString , globalWait };