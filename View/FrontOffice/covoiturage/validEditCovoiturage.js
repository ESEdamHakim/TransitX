document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".btn.edit");
    const modal = document.getElementById("ride-modal");
    const closeModalButton = document.querySelector(".close-modal");
    const cancelButton = document.querySelector(".cancel-btn");
    const editForm = document.querySelector("#edit-ride-form"); // Use the updated ID
    const vehiculeDropdown = document.getElementById("id_vehicule_edit"); // Dropdown for vehicles

    // Handle Edit Button Click
    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            console.log("Edit button clicked:", this); // Debugging line
            const idCovoit = this.getAttribute("data-id");
            console.log("Covoiturage ID:", idCovoit); // Debugging line

            const departure = this.getAttribute("data-departure");
            const destination = this.getAttribute("data-destination");
            const date = this.getAttribute("data-date");
            const time = this.getAttribute("data-time");
            const seats = this.getAttribute("data-seats");
            const price = this.getAttribute("data-price");
            const acceptParcels = this.getAttribute("data-accept-parcels");
            const fullParcels = this.getAttribute("data-full-parcels");
            const description = this.getAttribute("data-description");
            const idVehicule = this.getAttribute("data-id-vehicule");

            // Populate the modal fields
            document.getElementById("id_vehicule_edit").value = idVehicule;

            let idInput = document.getElementById("id_covoit_edit"); // Use a unique ID
            if (!idInput) {
                idInput = document.createElement("input");
                idInput.type = "hidden";
                idInput.id = "id_covoit_edit";
                idInput.name = "id_covoit";
                document.getElementById("edit-ride-form").appendChild(idInput);
            }
            idInput.value = idCovoit;

            document.getElementById("ride-departure").value = departure;
            document.getElementById("ride-destination").value = destination;
            document.getElementById("ride-date-edit").value = date;
            document.getElementById("ride-time-edit").value = time;
            document.getElementById("ride-seats").value = seats;
            document.getElementById("ride-price").value = price;
            document.getElementById("accept-parcels").value = acceptParcels;
            document.getElementById("full-parcels").value = fullParcels;
            document.getElementById("ride-description").value = description;

            // Open the modal
            modal.style.display = "block";
            console.log("Modal opened"); // Debugging line
        });
    });
    vehiculeDropdown.addEventListener("change", function () {
        const selectedVehiculeId = this.value;
        console.log("Selected Vehicule ID:", selectedVehiculeId); // Debugging line

        // Update the hidden input or any other field that needs the selected id_vehicule
        document.getElementById("id_vehicule_edit").value = selectedVehiculeId;
    });
    // Close Modal
    closeModalButton.addEventListener("click", () => {
        modal.style.display = "none";
    });

    cancelButton.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Edit Form Validation
    editForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll(".error-message").forEach((span) => {
            span.textContent = "";
        });

        // Get form fields
        const dateEdit = document.getElementById("ride-date-edit").value.trim();
        const timeEdit = document.getElementById("ride-time-edit").value.trim();
        const detailsEdit = document.getElementById("ride-description").value.trim();
        const seatsEdit = document.getElementById("ride-seats").value.trim();
        const priceEdit = document.getElementById("ride-price").value.trim();
        const idVehicule = document.getElementById("id_vehicule_edit").value.trim();
        console.log("Submitting with Vehicule ID:", idVehicule);
        let hasError = false;

        // Validate required fields
        if (!dateEdit) {
            document.getElementById("ride-date-error").textContent = "Veuillez sélectionner une date.";
            hasError = true;
        } else {
            const today = new Date();
            const selectedDate = new Date(dateEdit);
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                document.getElementById("ride-date-error").textContent = "La date de départ ne peut pas être dans le passé.";
                hasError = true;
            }
        }

        if (!timeEdit) {
            document.getElementById("ride-time-error").textContent = "Veuillez sélectionner une heure.";
            hasError = true;
        }

        if (!detailsEdit) {
            document.getElementById("ride-description-error").textContent = "Veuillez ajouter des détails.";
            hasError = true;
        } else if (detailsEdit.length > 100) {
            document.getElementById("ride-description-error").textContent = "Les détails ne peuvent pas dépasser 100 caractères.";
            hasError = true;
        }

        // Validate numeric fields
        if (isNaN(seatsEdit) || seatsEdit <= 0) {
            document.getElementById("ride-seats-error").textContent = "Le nombre de places disponibles doit être supérieur à zéro.";
            hasError = true;
        }

        if (isNaN(priceEdit) || priceEdit <= 0) {
            document.getElementById("ride-price-error").textContent = "Le prix par place doit être supérieur à zéro.";
            hasError = true;
        }
        // Validate required fields
        if (!idVehicule) {
            document.getElementById("id-vehicule-error-edit").textContent = "Veuillez sélectionner un véhicule.";
            return;
        }
        // If no errors, submit the form
        if (!hasError) {
            editForm.submit();
        }
    });
});