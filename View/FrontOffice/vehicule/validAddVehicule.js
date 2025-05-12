document.addEventListener("DOMContentLoaded", () => {
    // ========== Mobile Menu Toggle ==========
    document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
        document.querySelector('.main-nav').classList.toggle('active');
    });

    // ========== Ensure dashboard and logout buttons are visible ==========
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';

    // ========== Add Vehicle Logic ==========
    const createVehicleForm = document.querySelector(".create-ride-form");
    const confortSelect = document.getElementById("confort");
    const customConfortTextarea = document.getElementById("custom-confort");

    confortSelect.addEventListener("change", function () {
        const selectedOption = confortSelect.value;

        if (selectedOption === "other") {
            customConfortTextarea.placeholder = "Ajoutez votre confort personnalisé ici...";
            customConfortTextarea.disabled = false;
            customConfortTextarea.value = "";
        } else if (selectedOption) {
            customConfortTextarea.placeholder = "Ajoutez des détails ou modifiez l'option sélectionnée";
            customConfortTextarea.disabled = false;
            customConfortTextarea.value = selectedOption;
        } else {
            customConfortTextarea.placeholder = " complétez l'option sélectionnée";
            customConfortTextarea.disabled = true;
            customConfortTextarea.value = "";
        }
    });

    createVehicleForm.addEventListener("submit", function (e) {
        e.preventDefault();

        document.querySelectorAll(".error-message").forEach((span) => {
            span.textContent = "";
        });

        const matricule = document.getElementById("matricule").value.trim();
        const typeVehicule = document.getElementById("type-vehicule").value.trim();
        const nbPlaces = parseInt(document.getElementById("nb-places").value);
        const couleur = document.getElementById("couleur").value.trim();
        const marque = document.getElementById("marque").value.trim();
        const modele = document.getElementById("modele").value.trim();
        const selectedConfort = confortSelect.value.trim();
        const customConfort = customConfortTextarea.value.trim();
        const photoVehicule = document.getElementById("photo-vehicule").value;

        let hasError = false;

        if (!matricule) {
            document.getElementById("matricule-error").textContent = "Veuillez remplir le numéro de matricule.";
            hasError = true;
        } else if (matricule === "0") {
            document.getElementById("matricule-error").textContent = "Le matricule ne peut pas être égal à 0.";
            hasError = true;
        }

        if (!typeVehicule) {
            document.getElementById("type-vehicule-error").textContent = "Veuillez sélectionner un type de véhicule.";
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
        } else if (marque === modele) {
            document.getElementById("modele-error").textContent = "La marque et le modèle ne peuvent pas être identiques.";
            hasError = true;
        }

        if (!selectedConfort && !customConfort) {
            document.getElementById("confort-error").textContent = "Veuillez sélectionner ou ajouter une option de confort.";
            hasError = true;
        } else if (customConfort.length > 100) {
            document.getElementById("confort-error").textContent = "Le confort personnalisé ne peut pas dépasser 100 caractères.";
            hasError = true;
        }

        if (!photoVehicule) {
            document.getElementById("photo-vehicule-error").textContent = "Veuillez ajouter une photo du véhicule.";
            hasError = true;
        }

        if (!hasError) {
            if (selectedConfort && customConfort) {
                confortSelect.value = `${selectedConfort} - ${customConfort}`;
            } else if (customConfort) {
                confortSelect.value = customConfort;
            }

            createVehicleForm.submit();
        }
    });
});
