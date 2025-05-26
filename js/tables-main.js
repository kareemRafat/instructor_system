const radios = document.querySelectorAll("input[type='radio']");

const urlParams = new URLSearchParams(window.location.search);

console.log(urlParams.get('branch'));

if (!urlParams.get('branch')) {
  radios.forEach((radio) => {
    if (radio.value == 1) {
      radio.checked = true;
    }
  })
}