document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
  
    form.addEventListener("submit", function (event) {
      const idTrajet = form.id_trajet.value.trim();
      const numBus = form.num_bus.value.trim();
      const capacite = form.capacite.value.trim();
      const typeBus = form.type_bus.value.trim();
      const marque = form.marque.value.trim();
      const modele = form.modele.value.trim();
      const dateMiseEnService = form.date_mise_en_service.value.trim();
      const statut = form.statut.value;
  
      let errors = [];
      const containsLetterRegex = /[A-Za-zÀ-ÿ]/;
  
      if (!idTrajet) errors.push("ID Trajet est requis.");
      if (!numBus || isNaN(numBus) || Number(numBus) <= 0) {
        errors.push("Numéro de bus doit être un nombre strictement positif.");
      }
      if (!capacite || isNaN(capacite) || capacite <= 0) {
        errors.push("Capacité doit être un nombre positif.");
      }
      if (!typeBus) errors.push("Type de bus est requis.");
  
      if (!marque) {
        errors.push("Marque est requise.");
      } else if (!containsLetterRegex.test(marque)) {
        errors.push("Marque doit contenir au moins une lettre.");
      }
  
      if (!modele) {
        errors.push("Modèle est requis.");
      } else if (!containsLetterRegex.test(modele)) {
        errors.push("Modèle doit contenir au moins une lettre.");
      }
  
      if (!dateMiseEnService) {
        errors.push("Date de mise en service est requise.");
      } else {
        const today = new Date();
        const selectedDate = new Date(dateMiseEnService);
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);
  
        if (selectedDate > today) {
          errors.push("La date de mise en service ne peut pas être dans le futur.");
        }
      }
  
      if (!statut) errors.push("Statut est requis.");
  
      if (errors.length > 0) {
        event.preventDefault();
        alert("Erreurs dans le formulaire :\n\n" + errors.join("\n"));
      }
    });
  });
  