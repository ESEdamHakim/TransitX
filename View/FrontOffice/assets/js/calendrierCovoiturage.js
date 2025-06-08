function showCovoiturageDetails(details, title = "Détails du covoiturage") {
  let modal = document.getElementById("covoiturageModal");
  let desc = document.getElementById("covoiturageDescription");
  let modalTitle = modal.querySelector("h2");
  if (modal && desc && modalTitle) {
    desc.innerHTML = details;
    modalTitle.innerText = title;
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
function showBookedCovoiturageDetails(details, title = "Votre covoiturage réservé") {
  let modal = document.getElementById("covoiturageModal");
  let desc = document.getElementById("covoiturageDescription");
  let modalTitle = modal.querySelector("h2");
  if (modal && desc && modalTitle) {
    desc.innerHTML = details;
    modalTitle.innerText = title;
    modal.style.display = "block";
  }
}
