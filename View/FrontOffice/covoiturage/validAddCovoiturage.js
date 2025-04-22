// Mobile menu toggle
document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
    document.querySelector('.main-nav').classList.toggle('active');
});

// Ensure dashboard button is visible
document.querySelector('.dashboard-btn').style.display = 'inline-flex';
document.querySelector('.logout-btn').style.display = 'inline-flex';

document.addEventListener("DOMContentLoaded", () => {
    // Add Ride Logic
    const createRideForm = document.querySelector(".create-ride-form");
    const detailsOptions = document.getElementById("details-options");
    const rideDetails = document.getElementById("ride-details");

    // Update the details field when an option is selected
    detailsOptions.addEventListener("change", function () {
        const selectedOption = detailsOptions.value;
        if (selectedOption === "other") {
            // Allow the user to manually enter details if "other" is selected
            rideDetails.value = "";
            rideDetails.placeholder = "Ajoutez des détails ou complétez l'option sélectionnée";
        } else {
            // Populate the details field with the selected option
            rideDetails.value = selectedOption;
        }
    });

    createRideForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll(".error-message").forEach((span) => {
            span.textContent = "";
        });

        // Get form fields
        const lieuDepart = document.getElementById("start-point").value.trim();
        const lieuArrivee = document.getElementById("end-point").value.trim();
        const dateDepart = document.getElementById("ride-date-create").value.trim();
        const tempsDepart = document.getElementById("ride-time-create").value.trim();
        const placesDispo = parseInt(document.getElementById("seats").value);
        const prix = parseFloat(document.getElementById("price-per-seat").value);
        const accepteColis = document.getElementById("accept-parcels").value.trim();
        const colisComplet = document.getElementById("full-parcels").value.trim();
        const details = rideDetails.value.trim();

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
        } else {
            const today = new Date();
            const selectedDate = new Date(dateDepart);
            today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

            if (selectedDate < today) {
                document.getElementById("ride-date-error").textContent = "La date de départ ne peut pas être dans le passé.";
                hasError = true;
            }
        }

        if (!tempsDepart) {
            document.getElementById("ride-time-error").textContent = "Veuillez sélectionner une heure.";
            hasError = true;
        }

        if (!details) {
            document.getElementById("ride-details-error").textContent = "Veuillez ajouter des détails.";
            hasError = true;
        } else if (details.length > 100) {
            document.getElementById("ride-details-error").textContent = "Les détails ne peuvent pas dépasser 100 caractères.";
            hasError = true;
        }

        // Validate numeric fields
        if (isNaN(placesDispo) || placesDispo <= 0) {
            document.getElementById("seats-error").textContent = "Le nombre de places disponibles doit être supérieur à zéro.";
            hasError = true;
        }

        if (isNaN(prix) || prix <= 0) {
            document.getElementById("price-error").textContent = "Le prix par place doit être supérieur à zéro.";
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
});