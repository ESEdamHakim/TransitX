// Function to handle modal opening with data attributes
function openViewModal(button) {
  const modal = document.getElementById('viewModal');
  // Fill modal fields with data attributes
  document.getElementById('modal-objet').innerText = button.getAttribute('data-objet');
  document.getElementById('modal-date').innerText = button.getAttribute('data-date');
  document.getElementById('modal-covoit').innerText = button.getAttribute('data-covoit');
  document.getElementById('modal-description').innerText = button.getAttribute('data-description');
  document.getElementById('modal-statut').innerText = button.getAttribute('data-statut');
  modal.classList.add('active'); // Open the modal by adding the 'active' class
}

// Setup handlers for view buttons
function setupViewButtonHandlers() {
  document.querySelectorAll('.action-btn.view').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      openViewModal(this);
    });
  });
}

// Setup handlers for modal closing
function setupCloseModalHandlers() {
  // Close modal when clicking the close button or cancel button
  document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
    button.addEventListener('click', function() {
      const modal = this.closest('.modal');
      if (modal) {
        modal.classList.remove('active');
      }
    });
  });

  // Close modal when clicking outside the modal
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('viewModal');
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
}

// Setup handlers for delete button and confirmation modal
function setupDeleteButtonHandlers() {
  document.querySelectorAll('.action-btn.delete').forEach(button => {
    button.addEventListener('click', function() {
      const recId = this.dataset.id;
      const fullName = `${recId}`.trim();

      // Set hidden input for delete form
      const deleteFormIdInput = document.getElementById('delete-id');
      if (deleteFormIdInput) {
        deleteFormIdInput.value = recId;
      }

      // Update modal text
      const modalBodyText = document.querySelector('#delete-modal .modal-body p');
      if (modalBodyText) {
        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer la réclamation ${fullName} ? Cette action est irréversible.`;
      }

      // Show delete modal
      const deleteModal = document.getElementById('delete-modal');
      if (deleteModal) {
        deleteModal.classList.add('active');
      }
    });
  });
}

// Handle confirm delete button click
function setupConfirmDeleteButton() {
  document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    this.disabled = true; // Disable to avoid double-click
    const deleteForm = document.getElementById('delete-form');
    if (deleteForm) {
      deleteForm.submit(); // Submit the form to confirm deletion
    }
  });
}

// Initialize all modal and button handlers
function initializeModalHandlers() {
  setupViewButtonHandlers();
  setupCloseModalHandlers();
  setupDeleteButtonHandlers();
  setupConfirmDeleteButton();
}

// Initialize all handlers when document is ready
initializeModalHandlers();
