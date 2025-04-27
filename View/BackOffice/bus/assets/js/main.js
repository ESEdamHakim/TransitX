// Tabs Filtering
  document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
      const filter = button.dataset.tab;
      document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
  
      document.querySelectorAll('.buses-table tbody tr').forEach(row => {
        const statut = row.dataset.statut;
        if (filter === 'all' || statut === filter) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });
  
// Bus Search 
document.querySelector('.header-right .search-bar:nth-of-type(1) input').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase();
  document.querySelectorAll('.buses-table tbody tr').forEach(row => {
    const numeroCell = row.querySelector('td:nth-child(3)'); 
    const numeroText = numeroCell ? numeroCell.innerText.toLowerCase() : '';
    row.style.display = numeroText.includes(searchTerm) ? '' : 'none';
  });
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
