// Sidebar Toggle
document.addEventListener("DOMContentLoaded", () => {
  // Sidebar Toggle
  document.querySelector('.sidebar-toggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
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

  // Open Edit Ride Page
  const editButtons = document.querySelectorAll('.edit');
  editButtons.forEach(button => {
    button.addEventListener('click', function () {
      const idCovoit = this.getAttribute('data-id'); // Get the ID of the covoiturage
      // Redirect to the update page with the ID as a query parameter
      window.location.href = `updateCovoiturage.php?id_covoit=${idCovoit}`;
    });
  });

  // Open Delete Confirmation Modal
  const deleteButtons = document.querySelectorAll('.delete');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const row = this.closest('tr'); // Get the row to delete
      const idCovoit = this.getAttribute('data-id'); // Get the ID of the covoiturage
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
    // Get form fields
    const lieuDepart = document.getElementById('start-point').value.trim();
    const lieuArrivee = document.getElementById('end-point').value.trim();
    const dateDepart = document.getElementById('ride-date').value.trim();
    const tempsDepart = document.getElementById('ride-time').value.trim();
    const placesDispo = parseInt(document.getElementById('seats').value);
    const prix = parseFloat(document.getElementById('price-per-seat').value);
    const details = document.getElementById('ride-details').value.trim();

    // Validate required fields
    if (!lieuDepart || !lieuArrivee || !dateDepart || !tempsDepart || !details) {
      alert('Veuillez remplir tous les champs obligatoires.');
      e.preventDefault();
      return;
    }

    // Validate date (no past dates allowed)
    const today = new Date();
    const selectedDate = new Date(dateDepart);
    today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

    if (selectedDate < today) {
      alert('La date de départ ne peut pas être dans le passé.');
      e.preventDefault();
      return;
    }

    // Validate numeric fields
    if (isNaN(placesDispo) || placesDispo <= 0) {
      alert('Le nombre de places disponibles doit être supérieur à zéro.');
      e.preventDefault();
      return;
    }

    if (isNaN(prix) || prix <= 0) {
      alert('Le prix par place doit être supérieur à zéro.');
      e.preventDefault();
      return;
    }

    // If all validations pass, the form will be submitted
  });
});