const monthSelect = document.getElementById("month-select");

document.addEventListener("DOMContentLoaded", async function () {
  await getMonths();
});

monthSelect.addEventListener("change", function (e) {  
  if (this.value) {
    const date = this.value.split(" - ");
    const month = date[0].toLowerCase();
    const year = date[1].toLowerCase();

    setQueryString({ month, year });
  } else {
    // reset select and query string
    setQueryString({ month : null, year:  null });
    monthSelect.innerHTML = "<option>Select a Month</option>";
  }
});

/** get months */
async function getMonths() {
  const pageQueryString =
    getQueryString("month") + " - " + getQueryString("year");

  try {
    const response = await fetch("functions/Bonus/get_months.php");
    const month = await response.json();

    monthSelect.innerHTML = "<option value=''>Select a Month</option>";

    if (month.data && month.data.length > 1) {
      month.data.forEach((resData) => {
        const option = document.createElement("option");
        option.value = `${resData.month.toLowerCase()} - ${resData.year}`;
        option.textContent = `${resData.month} - ${resData.year}`;
        if (option.value == pageQueryString ) {          
          option.selected = true ;
        }
        monthSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error fetching dates:", error);
    monthSelect.innerHTML = "<option value=''>Error loading dates</option>";
  }
}

/** set query string */
function setQueryString(params) {
  const url = new URL(window.location);

  Object.entries(params).forEach(([key, value]) => {
    if (value) {
      url.searchParams.set(key, value); // Set or update
    } else {
      url.searchParams.delete(key); // Remove if value is falsy
    }
  });

  window.location.href = url.toString(); // Reload with new query string
}

/** query string get */
function getQueryString(key) {
  const params = new URLSearchParams(window.location.search);
  return params.get(key);
}
