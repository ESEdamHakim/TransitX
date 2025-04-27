document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
  
    const showError = (input, message) => {
      // Remove existing error message
      const oldError = input.parentElement.querySelector(".input-error");
      if (oldError) oldError.remove();
  
      // Create and add new error message
      const error = document.createElement("span");
      error.className = "input-error";
      error.textContent = message;
      input.parentElement.appendChild(error);
    };
  
    const clearErrors = () => {
      document.querySelectorAll(".input-error").forEach(e => e.remove());
    };
  
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      clearErrors();
  
      let isValid = true;
  
      const fields = {
        place_depart: form.place_depart,
        place_arrivee: form.place_arrivee,
        heure_depart: form.heure_depart,
        duree: form.duree,
        distance_km: form.distance_km,
        prix: form.prix
      };
  
      const containsLetterRegex = /[A-Za-zÀ-ÿ]/;
      const timePattern = /^([01]?[0-9]|2[0-3]):([0-5][0-9])$/;
      const dureePattern = /^([0-1]?[0-9]|2[0-3])h([0-5][0-9])$/;
  
      // Validation logic
      if (!fields.place_depart.value.trim()) {
        showError(fields.place_depart, "Le lieu de départ est requis.");
        isValid = false;
      } else if (!containsLetterRegex.test(fields.place_depart.value.trim())) {
        showError(fields.place_depart, "Le lieu de départ doit contenir des lettres.");
        isValid = false;
      }
  
      if (!fields.place_arrivee.value.trim()) {
        showError(fields.place_arrivee, "Le lieu d'arrivée est requis.");
        isValid = false;
      } else if (!containsLetterRegex.test(fields.place_arrivee.value.trim())) {
        showError(fields.place_arrivee, "Le lieu d'arrivée doit contenir des lettres.");
        isValid = false;
      }
  
      if (!fields.heure_depart.value.trim()) {
        showError(fields.heure_depart, "L'heure de départ est requise.");
        isValid = false;
      } else if (!timePattern.test(fields.heure_depart.value.trim())) {
        showError(fields.heure_depart, "Format attendu : HH:MM.");
        isValid = false;
      }
  
      if (!fields.duree.value.trim()) {
        showError(fields.duree, "La durée est requise.");
        isValid = false;
      } else if (!dureePattern.test(fields.duree.value.trim())) {
        showError(fields.duree, "Format attendu : HHhMM (ex: 02h30).");
        isValid = false;
      }
  
      if (!fields.distance_km.value.trim() || isNaN(fields.distance_km.value) || Number(fields.distance_km.value) <= 0) {
        showError(fields.distance_km, "La distance doit être un nombre positif.");
        isValid = false;
      }
  
      if (!fields.prix.value.trim() || isNaN(fields.prix.value) || Number(fields.prix.value) <= 0) {
        showError(fields.prix, "Le prix doit être un nombre positif.");
        isValid = false;
      }
  
      if (isValid) {
        form.submit();
      }
    });
  });
  