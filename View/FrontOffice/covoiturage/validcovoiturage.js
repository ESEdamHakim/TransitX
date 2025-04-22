// Mobile menu toggle
document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
    document.querySelector('.main-nav').classList.toggle('active');
  });
  
  // Ensure dashboard button is visible
  document.querySelector('.dashboard-btn').style.display = 'inline-flex';
  document.querySelector('.logout-btn').style.display = 'inline-flex';
  
  // Form validation and submission
  document.addEventListener("DOMContentLoaded", () => {
    const detailsOptions = document.getElementById("details-options");
    const rideDetails = document.getElementById("ride-details");
    const deleteButtons = document.querySelectorAll(".btn.delete");
    const editButtons = document.querySelectorAll(".btn.edit");
    const modal = document.getElementById("ride-modal");
    const closeModalButton = document.querySelector(".close-modal");
    const cancelButton = document.querySelector(".cancel-btn");
    // Populate the textarea when an option is selected
    detailsOptions.addEventListener("change", () => {
      const selectedOption = detailsOptions.value;
  
      if (selectedOption === "other") {
        // Clear the textarea for custom input
        rideDetails.value = "";
        rideDetails.placeholder = "Ajoutez vos propres détails...";
      } else {
        // Populate the textarea with the selected option
        rideDetails.value = selectedOption;
      }
    });
  
    const createRideForm = document.querySelector(".create-ride-form");
  
    createRideForm.addEventListener("submit", function (e) {
      e.preventDefault();
      

      // Clear previous error messages
      document.querySelectorAll(".error-message").forEach((span) => {
        span.textContent = "";
      });
  
      // Get form fields
      const lieuDepart = document.getElementById("start-point").value.trim();
      const lieuArrivee = document.getElementById("end-point").value.trim();
      const dateDepart = document.getElementById("ride-date").value.trim();
      const tempsDepart = document.getElementById("ride-time").value.trim();
      const placesDispo = parseInt(document.getElementById("seats").value);
      const prix = parseFloat(document.getElementById("price-per-seat").value);
      const accepteColis = document.getElementById("accept-parcels-unique").value.trim();
      const colisComplet = document.getElementById("full-parcels-unique").value.trim();
      const details = document.getElementById("ride-details").value.trim();
  
      let hasError = false;
  
      // Validate required fields
      if (!lieuDepart) {
        document.getElementById("start-point-error").textContent = "Veuillez remplir le point de départ.";
        hasError = true;
      }
  
      if (!lieuArrivee) {
        document.getElementById("end-point-error").textContent = "Veuillez remplir la destination.";
        hasError = true;
      }
  
      if (!dateDepart) {
        document.getElementById("ride-date-error").textContent = "Veuillez sélectionner une date.";
        hasError = true;
      }
  
      if (!tempsDepart) {
        document.getElementById("ride-time-error").textContent = "Veuillez sélectionner une heure.";
        hasError = true;
      }
  
      if (!details) {
        document.getElementById("ride-details-error").textContent = "Veuillez ajouter des détails.";
        hasError = true;
      }
  
      // Validate that departure and arrival locations are not the same
      if (lieuDepart === lieuArrivee) {
        document.getElementById("end-point-error").textContent = "Le point de départ et la destination ne peuvent pas être identiques.";
        hasError = true;
      }
  
      // Validate details length
      if (details.length > 100) {
        document.getElementById("ride-details-error").textContent = "Les détails ne peuvent pas dépasser 100 caractères.";
        hasError = true;
      }
  
      // Validate date (no past dates allowed)
      const today = new Date();
      const selectedDate = new Date(dateDepart);
      today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison
  
      if (selectedDate < today) {
        document.getElementById("ride-date-error").textContent = "La date de départ ne peut pas être dans le passé.";
        hasError = true;
      }
  
      // Validate numeric fields
      if (isNaN(placesDispo) || placesDispo <= 0) {
        document.getElementById("seats-error").textContent = "Le nombre de places disponibles doit être supérieur à zéro.";
        hasError = true;
      }
  
      if (isNaN(prix) || prix <= 0) {
        if (prix < 0) {
          document.getElementById("price-error").textContent = "Le prix par place ne peut pas être négatif.";
        } else {
          document.getElementById("price-error").textContent = "Le prix par place doit être supérieur à zéro.";
        }
        hasError = true;
      }
  
      // Validate select fields
      if (!accepteColis) {
        document.getElementById("accept-parcels-error").textContent = "Veuillez indiquer si vous acceptez les colis.";
        hasError = true;
      }
  
      if (!colisComplet) {
        document.getElementById("full-parcels-error").textContent = "Veuillez indiquer si les colis sont complets.";
        hasError = true;
      }
  
      // If no errors, submit the form
      if (!hasError) {
        
        createRideForm.submit();
      }
    });
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const idCovoit = this.getAttribute("data-id");
  
        // Confirm deletion
        if (confirm("Êtes-vous sûr de vouloir supprimer ce trajet ?")) {
          // Send delete request to UserDeleteCovoiturage.php
          fetch("UserDeleteCovoiturage.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id_covoit=${idCovoit}`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                // Remove the deleted covoiturage from the DOM
                this.closest(".route-card").remove();
                alert("Trajet supprimé avec succès !");
              } else {
                alert("Erreur : " + data.message);
              }
            })
            .catch((error) => {
              console.error("Erreur lors de la suppression :", error);
              alert("Une erreur est survenue lors de la suppression.");
            });
        }
      });
    });
    // Handle Edit Button Click
    editButtons.forEach((button) => {
      button.addEventListener("click", function () {
          const idCovoit = this.getAttribute("data-id");
          const departure = this.getAttribute("data-departure");
          const destination = this.getAttribute("data-destination");
          const date = this.getAttribute("data-date");
          const time = this.getAttribute("data-time");
          const seats = this.getAttribute("data-seats");
          const price = this.getAttribute("data-price");
          const acceptParcels = this.getAttribute("data-accept-parcels");
          const fullParcels = this.getAttribute("data-full-parcels");
          const description = this.getAttribute("data-description");

          // Populate the modal fields
          document.getElementById("id_covoit").value = idCovoit;
          document.getElementById("ride-departure").value = departure;
          document.getElementById("ride-destination").value = destination;
          document.getElementById("ride-date").value = date;
          document.getElementById("ride-time").value = time;
          document.getElementById("ride-seats").value = seats;
          document.getElementById("ride-price").value = price;
          document.getElementById("accept-parcels").value = acceptParcels;
          document.getElementById("full-parcels").value = fullParcels;
          document.getElementById("ride-description").value = description;

          // Open the modal
          modal.style.display = "block";
      });
  });

  // Close Modal
  closeModalButton.addEventListener("click", () => {
    modal.style.display = "none";
  });

  cancelButton.addEventListener("click", () => {
    modal.style.display = "none";
  });
});