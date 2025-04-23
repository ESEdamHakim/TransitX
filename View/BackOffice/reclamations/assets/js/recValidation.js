document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form.reclamation-form');
  
    forms.forEach(form => {
      form.addEventListener('submit', function (e) {
        let isValid = true;
  
        // Clear previous errors
        const errorElements = form.querySelectorAll('.error-message');
        errorElements.forEach(el => el.remove());
  
        // Helper to show errors
        function showError(input, message) {
          isValid = false;
          const error = document.createElement('div');
          error.className = 'error-message';
          error.style.color = 'red';
          error.style.fontSize = '0.85em';
          error.textContent = message;
          input.parentNode.appendChild(error);
        }
  
        // Inputs
        const idClient = form.querySelector('#id_client');
        const objet = form.querySelector('#objet, #complaint-type');
        const dateRec = form.querySelector('#incident-date');
        const idCovoit = form.querySelector('#id_covoit');
        const description = form.querySelector('#description');
        const statut = form.querySelector('#statut');
  
        // Validation
        if (!idClient.value.trim()) showError(idClient, "L'ID client est requis.");
        if (!objet.value) showError(objet, "Veuillez choisir un objet.");
        if (!dateRec.value) {
          showError(dateRec, "La date est requise.");
        } else {
          const selectedDate = new Date(dateRec.value);
          const today = new Date();
          today.setHours(0, 0, 0, 0); // remove time
          if (selectedDate > today) {
            showError(dateRec, "La date ne peut pas être dans le futur.");
          }
        }
        if (!idCovoit.value.trim()) showError(idCovoit, "L'ID du covoiturage est requis.");
        if (!description.value.trim()) showError(description, "Veuillez décrire votre réclamation.");
        if (!statut.value) showError(statut, "Veuillez choisir un statut.");
  
        if (!isValid) e.preventDefault(); // Block submit
      });
    });
  });
  