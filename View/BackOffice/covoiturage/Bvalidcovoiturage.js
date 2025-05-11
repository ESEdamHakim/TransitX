document.addEventListener("DOMContentLoaded", () => {
  // Sidebar Toggle
  document.querySelector('.sidebar-toggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
    });
  });

  // Tab Switching
  const tabButtons = document.querySelectorAll('.tab-btn');
  tabButtons.forEach(button => {
    button.addEventListener('click', function () {
      tabButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      const tabName = this.getAttribute('data-tab');
      console.log(`Switching to tab: ${tabName}`);
    });
  });

  // View Switching
  const viewButtons = document.querySelectorAll('.view-btn');
  const viewContainers = document.querySelectorAll('.view-container');
  viewButtons.forEach(button => {
    button.addEventListener('click', function () {
      viewButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      const viewType = this.getAttribute('data-view');
      viewContainers.forEach(container => {
        container.classList.remove('active');
        if (container.classList.contains(`${viewType}-view`)) {
          container.classList.add('active');
        }
      });
    });
  });

  // Open Add Ride Modal
  document.getElementById('add-covoiturage-btn').addEventListener('click', function () {
    document.getElementById('modal-title').textContent = 'Ajouter un Trajet';
    document.getElementById('ride-form').reset();
    document.getElementById('ride-modal').classList.add('active');
  });

  // Open Edit Ride Modal
  const editButtons = document.querySelectorAll('.btn.edit');
  editButtons.forEach(button => {
    button.addEventListener('click', function () {
      // Get the data attributes from the clicked button
      const idCovoit = this.getAttribute('data-id');
      const row = this.closest('tr');
      const lieuDepart = row.querySelector('td:nth-child(2)').textContent.trim();
      const lieuArrivee = row.querySelector('td:nth-child(3)').textContent.trim();
      const dateDepart = row.querySelector('td:nth-child(4)').textContent.trim();
      const tempsDepart = row.querySelector('td:nth-child(5)').textContent.trim();
      const placesDispo = row.querySelector('td:nth-child(6)').textContent.trim();
      const prix = row.querySelector('td:nth-child(7)').textContent.trim().replace(' TND', '');
      const accepteColis = row.querySelector('td:nth-child(8)').textContent.trim() === 'Oui' ? 'oui' : 'non';
      const details = row.querySelector('td:nth-child(9)').textContent.trim();

      // Populate the modal fields with the data
      document.getElementById('ride-departure').value = lieuDepart;
      document.getElementById('ride-destination').value = lieuArrivee;
      document.getElementById('ride-date').value = dateDepart;
      document.getElementById('ride-time').value = tempsDepart;
      document.getElementById('ride-seats').value = placesDispo;
      document.getElementById('ride-price').value = prix;
      document.getElementById('accept-parcels').value = accepteColis;
      document.getElementById('ride-description').value = details;

      // Add the ID of the covoiturage to the form as a hidden input
      let idInput = document.getElementById('id_covoit');
      if (!idInput) {
        idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.id = 'id_covoit';
        idInput.name = 'id_covoit';
        document.getElementById('ride-form').appendChild(idInput);
      }
      idInput.value = idCovoit;

      // Change the modal title to "Modifier un Trajet"
      document.getElementById('modal-title').textContent = 'Modifier un Trajet';

      // Open the modal
      document.getElementById('ride-modal').classList.add('active');
    });
  });

  // Open Delete Confirmation Modal
  const deleteButtons = document.querySelectorAll('.btn.delete');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const row = this.closest('tr');
      const idCovoit = this.getAttribute('data-id');
      const deleteModal = document.getElementById('delete-modal');
      deleteModal.classList.add('active');

      // Handle the confirmation button click
      const confirmDeleteButton = document.getElementById('confirm-delete-btn');
      confirmDeleteButton.onclick = function () {
        fetch('deleteCovoiturage.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id_covoit=${idCovoit}`,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              row.remove();
              alert('Trajet supprimé avec succès !');
            } else {
              alert('Erreur : ' + data.message);
            }
            deleteModal.classList.remove('active');
          })
          .catch(error => {
            console.error('Erreur lors de la suppression :', error);
            alert('Une erreur est survenue lors de la suppression.');
            deleteModal.classList.remove('active');
          });
      };
    });
  });

  // Close Modals
  const closeButtons = document.querySelectorAll('.close-modal, .cancel-btn');
  closeButtons.forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('ride-modal').classList.remove('active');
      document.getElementById('delete-modal').classList.remove('active');
    });
  });

 // Form Validation and Submission
const rideForm = document.getElementById('ride-form');
rideForm.addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent default form submission

  // Clear previous error messages
  document.querySelectorAll(".error-message").forEach((span) => {
    span.textContent = "";
  });

  // Get form fields
  const lieuDepart = document.getElementById("ride-departure").value.trim();
  const lieuArrivee = document.getElementById("ride-destination").value.trim();
  const dateDepart = document.getElementById("ride-date").value.trim();
  const tempsDepart = document.getElementById("ride-time").value.trim();
  const placesDispo = parseInt(document.getElementById("ride-seats").value);
  const prix = parseFloat(document.getElementById("ride-price").value);
  const details = document.getElementById("ride-description").value.trim();

  let hasError = false;

  // Validate required fields
  if (!lieuDepart) {
    document.getElementById("ride-departure-error").textContent = "Veuillez remplir le point de départ.";
    hasError = true;
  }

  if (!lieuArrivee) {
    document.getElementById("ride-destination-error").textContent = "Veuillez remplir la destination.";
    hasError = true;
  }

  if (!dateDepart) {
    document.getElementById("ride-date-error").textContent = "Veuillez sélectionner une date.";
    hasError = true;
  }

  if (!tempsDepart) {
    document.getElementById("ride-time-error").textContent = "Veuillez sélectionner une heure.";
    hasError = true;
  }

  if (!details) {
    document.getElementById("ride-description-error").textContent = "Veuillez ajouter des détails.";
    hasError = true;
  }

  // Validate that departure and arrival locations are not the same
  if (lieuDepart === lieuArrivee) {
    document.getElementById("ride-destination-error").textContent = "Le point de départ et la destination ne peuvent pas être identiques.";
    hasError = true;
  }

  // Validate details length
  if (details.length > 100) {
    document.getElementById("ride-description-error").textContent = "Les détails ne peuvent pas dépasser 100 caractères.";
    hasError = true;
  }

  // Validate date (no past dates allowed)
  const today = new Date();
  const selectedDate = new Date(dateDepart);
  today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

  if (selectedDate < today) {
    document.getElementById("ride-date-error").textContent = "La date de départ ne peut pas être dans le passé.";
    hasError = true;
  }

  // Validate numeric fields
  if (isNaN(placesDispo) || placesDispo <= 0) {
    document.getElementById("ride-seats-error").textContent = "Le nombre de places disponibles doit être supérieur à zéro.";
    hasError = true;
  }

  if (isNaN(prix) || prix <= 0) {
    if (prix < 0) {
      document.getElementById("ride-price-error").textContent = "Le prix par place ne peut pas être négatif.";
    } else {
      document.getElementById("ride-price-error").textContent = "Le prix par place doit être supérieur à zéro.";
    }
    hasError = true;
  }

  // If no errors, submit the form via AJAX
  if (!hasError) {
    const formData = new FormData(rideForm);

    fetch("updateCovoiturage.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        console.log(data); // Debug response
        if (data.includes("Trajet mis à jour avec succès")) {
          alert("Trajet mis à jour avec succès!");
          document.getElementById("ride-modal").classList.remove("active");
        } else {
          alert("Erreur lors de la mise à jour : " + data);
        }
      })
      .catch((error) => {
        console.error("Erreur :", error);
        alert("Une erreur est survenue lors de la mise à jour.");
      });
  }
});