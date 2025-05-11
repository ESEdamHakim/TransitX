document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  const showError = (input, message) => {
    const oldError = input.parentElement.querySelector(".input-error");
    if (oldError) oldError.remove();

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

    const idTrajet = form.id_trajet;
    const numBus = form.num_bus;
    const capacite = form.capacite;
    const typeBus = form.type_bus;
    const marque = form.marque;
    const modele = form.modele;
    const dateMiseEnService = form.date_mise_en_service;
    const statut = form.statut;

    let isValid = true;
    const containsLetterRegex = /[A-Za-zÀ-ÿ]/;

    if (!idTrajet.value.trim()) {
      showError(idTrajet, "Trajet est requis.");
      isValid = false;
    }

    if (!numBus.value.trim() || isNaN(numBus.value) || Number(numBus.value) <= 0) {
      showError(numBus, "Numéro de bus doit être un nombre strictement positif.");
      isValid = false;
    }

    if (!capacite.value.trim() || isNaN(capacite.value) || Number(capacite.value) <= 0) {
      showError(capacite, "Capacité doit être un nombre positif.");
      isValid = false;
    }

    if (!typeBus.value.trim()) {
      showError(typeBus, "Type de bus est requis.");
      isValid = false;
    }

    if (!marque.value.trim()) {
      showError(marque, "Marque est requise.");
      isValid = false;
    } else if (!containsLetterRegex.test(marque.value.trim())) {
      showError(marque, "Marque doit contenir au moins une lettre.");
      isValid = false;
    }

    if (!modele.value.trim()) {
      showError(modele, "Modèle est requis.");
      isValid = false;
    } else if (!containsLetterRegex.test(modele.value.trim())) {
      showError(modele, "Modèle doit contenir au moins une lettre.");
      isValid = false;
    }

    if (!dateMiseEnService.value.trim()) {
      showError(dateMiseEnService, "Date de mise en service est requise.");
      isValid = false;
    } else {
      const today = new Date();
      const selectedDate = new Date(dateMiseEnService.value);
      today.setHours(0, 0, 0, 0);
      selectedDate.setHours(0, 0, 0, 0);
      if (selectedDate > today) {
        showError(dateMiseEnService, "La date ne peut pas être dans le futur.");
        isValid = false;
      }
    }

    if (!statut.value.trim()) {
      showError(statut, "Statut est requis.");
      isValid = false;
    }

    if (isValid) {
      form.submit(); 
    }
  });
});


