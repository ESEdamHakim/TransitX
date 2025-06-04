
document.addEventListener("DOMContentLoaded", () => {
    // Add Ride Logic
    const createRideForm = document.querySelector(".create-ride-form");
    const detailsOptions = document.getElementById("details-options");
    const rideDetails = document.getElementById("ride-details");
    const cityInput = document.getElementById("start-point"); // City input field
    const cityError = document.getElementById("start-point-error"); // Error span for city validation
    const apiKey = "8aab6949191302a6a18a11e8f68d5acf"; // OpenWeatherMap API key
    const apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";

    // Add event listener for id_vehicule change
    document.getElementById("id_vehicule").addEventListener("change", function () {
        console.log("Selected idVehicule:", this.value);
    });

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

    // Function to validate the city
    async function validateCity(city, errorElement) {
        try {
            const response = await fetch(apiUrl + city + `&appid=${apiKey}`);
            if (response.status === 404) {
                errorElement.textContent = "Ville introuvable. Veuillez entrer une ville valide.";
                return false;
            }
            errorElement.textContent = ""; // Clear error if the city is valid
            return true;
        } catch (error) {
            console.error("Error validating city:", error);
            errorElement.textContent = "Erreur lors de la validation de la ville. Veuillez réessayer.";
            return false;
        }
    }

    cityInput.addEventListener("blur", async () => {
        const city = cityInput.value.trim();
        if (city) {
            await validateCity(city, cityError); // Pass the cityError element to validateCity
        } else {
            cityError.textContent = "Veuillez entrer une ville.";
        }
    });

    // Form submission logic
    createRideForm.addEventListener("submit", async function (e) {
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
        const idVehicule = document.getElementById("id_vehicule").value.trim();
        let hasError = false;

        // Validate required fields for departure
if (!lieuDepart) {
    document.getElementById("start-point-error").textContent = "Veuillez remplir le point de départ.";
    hasError = true;
} else {
    // Validate the departure city
    const isCityValid = await validateCity(lieuDepart, document.getElementById("start-point-error"));
    if (!isCityValid) {
        hasError = true;
    }
}

// Validate required fields for destination
if (!lieuArrivee) {
    document.getElementById("end-point-error").textContent = "Veuillez remplir le point d'arrivée.";
    hasError = true;
} else {
    // Validate the destination city
    const isDestinationValid = await validateCity(lieuArrivee, document.getElementById("end-point-error"));
    if (!isDestinationValid) {
        hasError = true;
    }
}

        if (!dateDepart) {
            document.getElementById("ride-date-create-error").textContent = "Veuillez sélectionner une date.";
            hasError = true;
        } else {
            const today = new Date();
            const selectedDate = new Date(dateDepart);
            today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

            if (selectedDate < today) {
                document.getElementById("ride-date-create-error").textContent = "La date de départ ne peut pas être dans le passé.";
                hasError = true;
            }
        }

        if (!tempsDepart) {
            document.getElementById("ride-time-create-error").textContent = "Veuillez sélectionner une heure.";
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

        if (!idVehicule) {
            document.getElementById("id-vehicule-error").textContent = "Veuillez sélectionner un véhicule.";
            hasError = true;
        }

        // If no errors, submit the form
        if (!hasError) {
            createRideForm.submit();
        }
    });
});