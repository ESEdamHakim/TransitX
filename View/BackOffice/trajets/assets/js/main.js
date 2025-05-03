// Trajet Search
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.querySelector('.header-right .search-bar input');

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase();

      document.querySelectorAll('.buses-table-container tbody tr').forEach(row => {
        const arrivalCell = row.querySelector('td:nth-child(3)');
        const arrivalText = arrivalCell ? arrivalCell.textContent.toLowerCase() : '';

        // Show/hide row based on whether arrival includes search term
        row.style.display = arrivalText.includes(searchTerm) ? '' : 'none';
      });
    });
  }
});


// Setup handlers for modal closing
function setupCloseModalHandlers() {
  // Close modal when clicking the close button or cancel button
  document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
    button.addEventListener('click', function() {
      const modal = this.closest('.modal');
      if (modal) {
        modal.classList.remove('active'); // Remove 'active' to hide the modal
      }
    });
  });
}

// Setup handlers for delete button and confirmation modal
function setupDeleteButtonHandlers() {
  document.querySelectorAll('.action-btn.delete').forEach(button => {
    button.addEventListener('click', function() {
      const busId = this.dataset.id;  // Get the bus ID from the data-id attribute

      // Set hidden input for delete form
      const deleteFormIdInput = document.getElementById('delete-id');
      if (deleteFormIdInput) {
        deleteFormIdInput.value = busId;
      }

      // Update modal text
      const modalBodyText = document.querySelector('#delete-modal .modal-body p');
      if (modalBodyText) {
        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer le bus ${busId} ? Cette action est irréversible.`;
      }

      // Show delete modal
      const deleteModal = document.getElementById('delete-modal');
      if (deleteModal) {
        deleteModal.classList.add('active');  // Add 'active' to show the modal
      }
    });
  });
}

// Handle confirm delete button click
function setupConfirmDeleteButton() {
  document.querySelectorAll('#confirm-delete-btn').forEach(button => {
    button.addEventListener('click', function() {
      this.disabled = true; // Disable to avoid double-click

      // Get the form and submit it for deletion
      const deleteForm = document.getElementById('delete-form');
      if (deleteForm) {
        deleteForm.submit(); // Submit the form to confirm deletion
      }
    });
  });
}

// Initialize all modal and button handlers
function initializeModalHandlers() {
  setupCloseModalHandlers();      // Setup handlers for closing the modal
  setupDeleteButtonHandlers();    // Setup handlers for delete buttons
  setupConfirmDeleteButton();     // Setup handlers for the confirm delete button
}

// Initialize all handlers when the document is ready
document.addEventListener('DOMContentLoaded', function() {
  initializeModalHandlers();
});

// Statistics calculation for trajets
document.addEventListener('DOMContentLoaded', function () {
  const rows = document.querySelectorAll('.buses-table tbody tr');

  let totalTrajets = 0;
  let totalDuration = 0;
  let totalPrice = 0;
  let totalDistance = 0;

  rows.forEach(row => {
    const cells = row.querySelectorAll('td');
    const duration = parseFloat(cells[4]?.textContent) || 0; 
    const distance = parseFloat(cells[5]?.textContent) || 0; 
    const price = parseFloat(cells[6]?.textContent) || 0;

    totalTrajets++;
    totalDuration += duration;
    totalDistance += distance;
    totalPrice += price;
  });

  const averageDuration = totalTrajets > 0 ? (totalDuration / totalTrajets).toFixed(2) : 0;
  const averageDistance = totalTrajets > 0 ? (totalDistance / totalTrajets).toFixed(2) : 0;
  const averagePrice = totalTrajets > 0 ? (totalPrice / totalTrajets).toFixed(2) : 0;

  document.getElementById('total-trajets').textContent = totalTrajets;
  document.getElementById('average-duration').textContent = averageDuration + " h";
  document.getElementById('average-distance').textContent = averageDistance + " km";
  document.getElementById('average-price').textContent = averagePrice + " TND";
});
