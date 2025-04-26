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
  
    // Open Add Vehicle Modal
    document.getElementById('add-vehicule-btn').addEventListener('click', function () {
      document.getElementById('modal-title').textContent = 'Ajouter un Véhicule';
      document.getElementById('vehicle-form').reset();
      document.getElementById('vehicle-modal').classList.add('active');
    });
  
    // Open Edit Vehicle Modal
    const editButtons = document.querySelectorAll('.btn.edit');
editButtons.forEach(button => {
    button.addEventListener('click', function () {
        const idVehicule = this.getAttribute('data-id');
        const row = this.closest('tr');
        const matricule = row.querySelector('td:nth-child(2)').textContent.trim();
        const typeVehicule = row.querySelector('td:nth-child(3)').textContent.trim();
        const nbPlaces = row.querySelector('td:nth-child(4)').textContent.trim();
        const couleur = row.querySelector('td:nth-child(5)').textContent.trim();
        const marque = row.querySelector('td:nth-child(6)').textContent.trim();
        const modele = row.querySelector('td:nth-child(7)').textContent.trim();
        const confort = row.querySelector('td:nth-child(8)').textContent.trim();
        const photoVehicule = row.querySelector('td:nth-child(9) img')?.getAttribute('src') || '';

        // Populate the modal fields
        document.getElementById('id_vehicule').value = idVehicule;
        document.getElementById('vehicle-matricule').value = matricule;
        document.getElementById('vehicle-type').value = typeVehicule;
        document.getElementById('vehicle-seats').value = nbPlaces;
        document.getElementById('vehicle-color').value = couleur;
        document.getElementById('vehicle-brand').value = marque;
        document.getElementById('vehicle-model').value = modele;
        document.getElementById('vehicle-comfort').value = confort;

        // Populate the existing-photo field with the current photo's name
        const existingPhotoName = photoVehicule.split('/').pop(); // Extract the file name from the path
        document.getElementById('existing-photo').value = existingPhotoName;

        // Set the modal title and open the modal
        document.getElementById('modal-title').textContent = 'Modifier un Véhicule';
        document.getElementById('vehicle-modal').classList.add('active');
    });
});
  
    // Handle Form Submission for Add/Edit
    const vehicleForm = document.getElementById('vehicle-form');
    vehicleForm.addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent default form submission
  
      const formData = new FormData(vehicleForm);
  
      fetch('updateVehicule.php', {
          method: 'POST',
          body: formData,
      })
          .then(response => {
              // Ensure the response is valid JSON
              if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
              }
              return response.json();
          })
          .then(data => {
              if (data.success) {
                  alert(data.message); // Show success message
                  location.reload(); // Reload the page to reflect changes
              } else {
                  alert('Erreur : ' + data.message); // Show server-side error message
              }
          })
          .catch(error => {
              console.error('Erreur lors de la requête :', error);
              alert('Une erreur est survenue lors de la requête.');
          });
  });
  
    // Open Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.btn.delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        const row = this.closest('tr');
        const idVehicule = this.getAttribute('data-id');
        const deleteModal = document.getElementById('delete-modal');
        deleteModal.classList.add('active');
  
        const confirmDeleteButton = document.getElementById('confirm-delete-btn');
        confirmDeleteButton.onclick = function () {
          fetch('deletVehicule.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_vehicule=${idVehicule}`,
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                row.remove();
                alert('Véhicule supprimé avec succès !');
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
        document.getElementById('vehicle-modal').classList.remove('active');
        document.getElementById('delete-modal').classList.remove('active');
      });
    });
  });