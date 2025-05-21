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

export { getMetaContent, capitalizeFirstLetter };