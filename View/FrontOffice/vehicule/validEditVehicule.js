document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".btn.edit");
    const modal = document.getElementById("ride-modal");
    const closeModalButton = document.querySelector(".close-modal");
    const cancelButton = document.querySelector(".cancel-btn");

    // Handle Edit Button Click
    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const idVehicule = this.getAttribute("data-id");
            const matricule = this.getAttribute("data-matricule");
            const typeVehicule = this.getAttribute("data-type");
            const nbPlaces = this.getAttribute("data-seats");
            const couleur = this.getAttribute("data-color");
            const marque = this.getAttribute("data-marque");
            const modele = this.getAttribute("data-modele");
            const confort = this.getAttribute("data-confort");
            const photoVehicule = this.getAttribute("data-photo");

            // Populate the modal fields
            document.getElementById("id_vehicule").value = idVehicule;
            document.getElementById("ride-matricule").value = matricule;
            document.getElementById("ride-type").value = typeVehicule;
            document.getElementById("ride-seats").value = nbPlaces;
            document.getElementById("ride-color").value = couleur;
            document.getElementById("ride-marque").value = marque;
            document.getElementById("ride-modele").value = modele;
            document.getElementById("ride-confort").value = confort || ""; // Populate confort field
            document.getElementById("existing-photo").value = photoVehicule;
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

    // Edit Form Validation
    const editForm = document.querySelector("#ride-form");

    editForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll(".error-message").forEach((span) => {
            span.textContent = "";
        });

        // Get form fields
        const matriculeEdit = document.getElementById("ride-matricule").value.trim();
        const typeEdit = document.getElementById("ride-type").value.trim();
        const seatsEdit = document.getElementById("ride-seats").value.trim();
        const colorEdit = document.getElementById("ride-color").value.trim();
        const marqueEdit = document.getElementById("ride-marque").value.trim();
        const modeleEdit = document.getElementById("ride-modele").value.trim();
        const confortEdit = document.getElementById("ride-confort").value.trim();

        let hasError = false;

        // Validate required fields
        if (!matriculeEdit) {
            document.getElementById("ride-matricule-error").textContent = "Veuillez entrer le matricule.";
            hasError = true;
        } else if (matriculeEdit === "0") {
            document.getElementById("ride-matricule-error").textContent = "Le matricule ne peut pas être égal à 0.";
            hasError = true;
        }

        if (!typeEdit) {
            document.getElementById("ride-type-error").textContent = "Veuillez entrer le type de véhicule.";
            hasError = true;
        }

        if (!seatsEdit || isNaN(seatsEdit) || seatsEdit <= 0) {
            document.getElementById("ride-seats-error").textContent = "Le nombre de places doit être un nombre supérieur à zéro.";
            hasError = true;
        }

        if (!colorEdit) {
            document.getElementById("ride-color-error").textContent = "Veuillez entrer la couleur.";
            hasError = true;
        }

        if (!marqueEdit) {
            document.getElementById("ride-marque-error").textContent = "Veuillez entrer la marque.";
            hasError = true;
        }

        if (!modeleEdit) {
            document.getElementById("ride-modele-error").textContent = "Veuillez entrer le modèle.";
            hasError = true;
        } else if (marqueEdit === modeleEdit) {
            document.getElementById("ride-modele-error").textContent = "La marque et le modèle ne peuvent pas être identiques.";
            hasError = true;
        }

        // Validate confort field length
        if (confortEdit.length > 100) {
            document.getElementById("ride-confort-error").textContent = "Le confort ne peut pas dépasser 100 caractères.";
            hasError = true;
        }

        // If no errors, submit the form
        if (!hasError) {
            editForm.submit();
        }
    });
});