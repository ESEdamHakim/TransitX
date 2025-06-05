function showCovoiturageDetails(details) {
  let modal = document.getElementById("covoiturageModal");
  let desc = document.getElementById("covoiturageDescription");
  if (modal && desc) {
    desc.innerHTML = details;
    modal.style.display = "block";
  }
}

function closeCovoiturageModal() {
  let modal = document.getElementById("covoiturageModal");
  if (modal) {
    modal.style.display = "none";
  }
}

// Optional: Close modal when clicking outside
window.addEventListener("click", function (e) {
  let modal = document.getElementById("covoiturageModal");
  if (modal && e.target === modal) {
    closeCovoiturageModal();
  }
});
function showHolidayCovoiturageDetails(details) {
  let modal = document.getElementById("holidayCovoiturageModal");
  let desc = document.getElementById("holidayCovoiturageDescription");
  if (modal && desc) {
    desc.innerHTML = details;
    modal.style.display = "block";
  }
}
function closeHolidayCovoiturageModal() {
  let modal = document.getElementById("holidayCovoiturageModal");
  if (modal) {
    modal.style.display = "none";
  }
}
window.addEventListener("click", function (e) {
  let modal = document.getElementById("holidayCovoiturageModal");
  if (modal && e.target === modal) {
    closeHolidayCovoiturageModal();
  }
});
