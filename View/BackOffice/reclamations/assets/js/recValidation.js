document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('form.reclamation-form');

  forms.forEach(form => {
    form.addEventListener('submit', function (e) {
      let isValid = true;
      let firstInvalidInput = null;

      // Remove old error messages
      form.querySelectorAll('.error-message').forEach(el => el.remove());
      form.querySelectorAll('.shake').forEach(el => el.classList.remove('shake'));

      // Helper: Show inline error
      function showError(input, message) {
        isValid = false;

        const error = document.createElement('div');
        error.className = 'error-message';
        error.style.color = 'red';
        error.style.fontSize = '0.85em';
        error.textContent = message;

        input.parentNode.appendChild(error);
        input.classList.add('shake');

        if (!firstInvalidInput) {
          firstInvalidInput = input;
        }
      }

      // Fields
      const idClient = form.querySelector('#id_client'); // now a <select>
      const objet = form.querySelector('#objet, #complaint-type');
      const dateRec = form.querySelector('#incident-date');
      const idCovoit = form.querySelector('#id_covoit'); // now a <select>
      const description = form.querySelector('#description');
      const statut = form.querySelector('#statut');

      // Validation
      if (!objet || !objet.value.trim()) {
        showError(objet, "Veuillez sélectionner un objet de réclamation.");
      }

      if (!dateRec || !dateRec.value) {
        showError(dateRec, "Veuillez entrer une date.");
      } else {
        const selectedDate = new Date(dateRec.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate > today) {
          showError(dateRec, "La date ne peut pas être dans le futur.");
        }
      }

      if (!idCovoit || idCovoit.value === "" || idCovoit.value === "--") {
        showError(idCovoit, "Veuillez sélectionner un trajet.");
      }

      if (!description || description.value.trim() === '') {
        showError(description, "Veuillez ajouter une description.");
      }

      if (!idClient || idClient.value === "" || idClient.value === "--") {
        showError(idClient, "Veuillez sélectionner un client.");
      }

      if (!statut || statut.value === "" || statut.value === "-- Sélectionner un statut --") {
        showError(statut, "Veuillez sélectionner un statut.");
      }

      if (!isValid) {
        e.preventDefault();
        if (firstInvalidInput) {
          firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      }
    });
  });
});
