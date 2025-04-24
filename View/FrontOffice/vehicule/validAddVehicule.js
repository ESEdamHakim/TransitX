// Mobile menu toggle
document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
    document.querySelector('.main-nav').classList.toggle('active');
});

// Ensure dashboard button is visible
document.querySelector('.dashboard-btn').style.display = 'inline-flex';
document.querySelector('.logout-btn').style.display = 'inline-flex';

document.addEventListener("DOMContentLoaded", () => {
    // Add Vehicle Logic
    const createVehicleForm = document.querySelector(".create-ride-form");

    createVehicleForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll(".error-message").forEach((span) => {
            span.textContent = "";
        });

        // Get form fields
        const matricule = document.getElementById("matricule").value.trim();
        const typeVehicule = document.getElementById("type-vehicule").value.trim();
        const nbPlaces = parseInt(document.getElementById("nb-places").value);
        const couleur = document.getElementById("couleur").value.trim();
        const marque = document.getElementById("marque").value.trim();
        const modele = document.getElementById("modele").value.trim();
        const confort = document.getElementById("confort").value.trim();
        const photoVehicule = document.getElementById("photo-vehicule").value;

        let hasError = false;

        // Validate required fields
        if (!matricule) {
            document.getElementById("matricule-error").textContent = "Veuillez remplir le numéro de matricule.";
            hasError = true;
        }

        if (!typeVehicule) {
            document.getElementById("type-vehicule-error").textContent = "Veuillez remplir le type de véhicule.";
            hasError = true;
        }

        if (isNaN(nbPlaces) || nbPlaces <= 0) {
            document.getElementById("nb-places-error").textContent = "Le nombre de places doit être supérieur à zéro.";
            hasError = true;
        }

        if (!couleur) {
            document.getElementById("couleur-error").textContent = "Veuillez remplir la couleur du véhicule.";
            hasError = true;
        }

        if (!marque) {
            document.getElementById("marque-error").textContent = "Veuillez remplir la marque du véhicule.";
            hasError = true;
        }

        if (!modele) {
            document.getElementById("modele-error").textContent = "Veuillez remplir le modèle du véhicule.";
            hasError = true;
        }

        if (!confort) {
            document.getElementById("confort-error").textContent = "Veuillez sélectionner une option de confort.";
            hasError = true;
        }

        if (!photoVehicule) {
            document.getElementById("photo-vehicule-error").textContent = "Veuillez ajouter une photo du véhicule.";
            hasError = true;
        }

        // If no errors, submit the form
        if (!hasError) {
            createVehicleForm.submit();
        }
    });
});