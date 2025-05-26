import { getMetaContent } from './helpers.js';

const radios = document.querySelectorAll("input[type='radio']");
const branchForm = document.getElementById('branchForm');

const urlParams = new URLSearchParams(window.location.search);

if (!urlParams.get('branch')) {
  radios.forEach((radio) => {
    if (radio.value == getMetaContent('branch')) {
      radio.checked = true;
    }
  })
  branchForm.submit();
}


