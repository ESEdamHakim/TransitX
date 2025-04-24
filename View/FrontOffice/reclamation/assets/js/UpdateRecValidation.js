document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form.reclamation-form');
  
    forms.forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission until all checks pass
        let isValid = true;
        let firstInvalidInput = null;
  
        // Remove old error messages and reset shaking effect
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
        const objet = form.querySelector('[name="objet"]');
        const dateRec = form.querySelector('[name="date_rec"]');
        const idCovoit = form.querySelector('[name="id_covoit"]');
        const description = form.querySelector('[name="description"]');
  
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
  
        if (!idCovoit || idCovoit.value.trim() === '') {
          showError(idCovoit, "Veuillez entrer l'ID du covoiturage.");
        }
  
        if (!description || description.value.trim() === '') {
          showError(description, "Veuillez ajouter une description.");
        }
  
        // If form is invalid after immediate checks, focus on first invalid input and prevent submit
        if (!isValid) {
          firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
          return; // Stop form submission here
        }
  
        // Asynchronous check for id_covoit existence (Foreign Key check)
        if (idCovoit && idCovoit.value.trim() !== '') {
          fetch(`fk_check.php?id_covoit=${encodeURIComponent(idCovoit.value.trim())}`)
            .then((response) => response.json())
            .then((result) => {
              if (!result.exists) {
                showError(idCovoit, result.errorMessage || "L'ID du covoiturage n'existe pas.");
                firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
              } else {
                // Only submit the form if it's valid
                form.submit();  // Submit the form manually after validation
              }
            })
            .catch((error) => {
              console.error("Erreur serveur :", error);
              showError(idCovoit, "Erreur serveur. Réessayez.");
              firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
            });
        } else {
          // If no ID covoiturage, just submit the form
          form.submit();
        }
      });
    });
  });
  