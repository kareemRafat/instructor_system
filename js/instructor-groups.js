
const timeLeft = document.querySelectorAll('.time-left');

document.addEventListener("DOMContentLoaded" , ()=> {
    timeLeft.forEach(item => {        
        const start = item.dataset.start;
        const groupName = item.dataset.groupName;
        item.innerHTML = getTimeRemaining(start , groupName);
    })
})


/** get how much time to end */
function getTimeRemaining(startDateStr, groupName) {
  // Parse the start date (DD-MM-YYYY format)
  const [day, month, year] = startDateStr.split("-").map(Number);
  const startDate = new Date(year, month - 1, day);

  // Determine duration based on group name
  const isTraining = groupName.toLowerCase().includes("training");
  const monthsToAdd = isTraining ? 2 : 5;
  const daysToAdd = isTraining ? 15 : 21;

  // Calculate target date
  const targetDate = new Date(startDate);
  targetDate.setMonth(targetDate.getMonth() + monthsToAdd);
  targetDate.setDate(targetDate.getDate() + daysToAdd);

  // Get current date
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Normalize to start of day

  // If target date has passed
  if (targetDate < today) {
    return `<p class="time-remaining">Completed</p>`;
  }

  // Calculate remaining time (more precise calculation)
  let monthsRemaining = targetDate.getMonth() - today.getMonth();
  let yearsDiff = targetDate.getFullYear() - today.getFullYear();

  // Adjust for year crossover
  monthsRemaining += yearsDiff * 12;

  // Calculate remaining days
  const tempDate = new Date(today);
  tempDate.setMonth(tempDate.getMonth() + monthsRemaining);

  let daysRemaining = Math.floor(
    (targetDate - tempDate) / (1000 * 60 * 60 * 24)
  );

  // Adjust if days are negative (month rollover)
  if (daysRemaining < 0) {
    monthsRemaining--;
    tempDate.setMonth(tempDate.getMonth() - 1);
    daysRemaining = Math.floor((targetDate - tempDate) / (1000 * 60 * 60 * 24));
  }

  return `
    <p class="time-remaining text-orange-600">${monthsRemaining} Months</p>
    <p class="time-remaining">${daysRemaining} Days</p>
  `;
}
