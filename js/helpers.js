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

export { getMetaContent, capitalizeFirstLetter , wait  };