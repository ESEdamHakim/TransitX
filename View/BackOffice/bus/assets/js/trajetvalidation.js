document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        // Retrieve and trim form values
        const placeDepart = form.place_depart.value.trim();
        const placeArrivee = form.place_arrivee.value.trim();
        const heureDepart = form.heure_depart.value.trim();
        const duree = form.duree.value.trim();
        const distanceKm = form.distance_km.value.trim();
        const prix = form.prix.value.trim();

        let errors = [];
        const containsLetterRegex = /[A-Za-zÀ-ÿ]/;

        const lettersOnlyRegex = /^[A-Za-zÀ-ÿ\s'-]+$/;

        // Validate place of departure
        if (!placeDepart) {
            errors.push("Le lieu de départ est requis.");
        } else if (!containsLetterRegex.test(placeDepart)) {
            errors.push("Le lieu de départ ne doit contenir que des lettres.");
        }
        
        // Validate place of arrival
        if (!placeArrivee) {
            errors.push("Le lieu d'arrivée est requis.");
        } else if (!containsLetterRegex.test(placeArrivee)) {
            errors.push("Le lieu d'arrivée ne doit contenir que des lettres.");
        }
        

        // Validate departure time (HH:MM AM/PM format)
        if (!heureDepart) {
            errors.push("L'heure de départ est requise.");
        } else {
            const timePattern = /^([01]?[0-9]|2[0-3]):([0-5][0-9])$/;
            if (!timePattern.test(heureDepart)) {
                errors.push("L'heure de départ doit être au format HH:MM (ex: 14:30).");
            }
        }
        

        // Validate duration (e.g., 02h30)
        if (!duree) {
            errors.push("La durée est requise.");
        } else {
            const dureePattern = /^([0-1]?[0-9]|2[0-3])h([0-5][0-9])$/;
            if (!dureePattern.test(duree)) {
                errors.push("La durée doit être au format HHhMM (ex: 02h30).");
            }
        }

        // Validate distance (positive number)
        if (!distanceKm || isNaN(distanceKm) || Number(distanceKm) <= 0) {
            errors.push("La distance doit être un nombre positif.");
        }

        // Validate price (positive number)
        if (!prix || isNaN(prix) || Number(prix) <= 0) {
            errors.push("Le prix doit être un nombre positif.");
        }

        // If there are any errors, prevent form submission and show error messages
        if (errors.length > 0) {
            event.preventDefault();
            alert("Erreurs dans le formulaire :\n\n" + errors.join("\n"));
        }
    });
});
