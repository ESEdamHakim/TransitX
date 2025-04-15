// Mobile menu toggle
document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
    document.querySelector('.main-nav').classList.toggle('active');
  });
  
  // Ensure dashboard button is visible
  document.querySelector('.dashboard-btn').style.display = 'inline-flex';
  document.querySelector('.logout-btn').style.display = 'inline-flex';
  
  // Form validation and submission
  document.addEventListener("DOMContentLoaded", () => {
    const createRideForm = document.querySelector(".create-ride-form");
  
    createRideForm.addEventListener("submit", function (e) {
      // Get form fields
      const lieuDepart = document.getElementById("start-point").value.trim();
      const lieuArrivee = document.getElementById("end-point").value.trim();
      const dateDepart = document.getElementById("ride-date").value.trim();
      const tempsDepart = document.getElementById("ride-time").value.trim();
      const placesDispo = parseInt(document.getElementById("seats").value);
      const prix = parseFloat(document.getElementById("price-per-seat").value);
      const accepteColis = document.getElementById("accept-parcels").value.trim();
      const colisComplet = document.getElementById("full-parcels").value.trim();
      const details = document.getElementById("ride-details").value.trim();
  
      // Validate required fields
      if (!lieuDepart || !lieuArrivee || !dateDepart || !tempsDepart || !details) {
        alert("Veuillez remplir tous les champs obligatoires.");
        e.preventDefault();
        return;
      }
  
      // Validate date (no past dates allowed)
      const today = new Date();
      const selectedDate = new Date(dateDepart);
      today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison
  
      if (selectedDate < today) {
        alert("La date de départ ne peut pas être dans le passé.");
        e.preventDefault();
        return;
      }
  
      // Validate numeric fields
      if (isNaN(placesDispo) || placesDispo <= 0) {
        alert("Le nombre de places disponibles doit être supérieur à zéro.");
        e.preventDefault();
        return;
      }
  
      if (isNaN(prix) || prix <= 0) {
        alert("Le prix par place doit être supérieur à zéro.");
        e.preventDefault();
        return;
      }
  
      // Validate select fields
      if (!accepteColis) {
        alert("Veuillez indiquer si vous acceptez les colis.");
        e.preventDefault();
        return;
      }
  
      if (!colisComplet) {
        alert("Veuillez indiquer si les colis sont complets.");
        e.preventDefault();
        return;
      }
  
      // If all validations pass, the form will be submitted
    });
  });