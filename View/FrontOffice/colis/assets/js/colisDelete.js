// Setup Delete Button click to open the confirmation modal
document.querySelectorAll('.open-delete-modal').forEach(button => {
    button.addEventListener('click', function () {
      const colisId = this.dataset.id;  // only id_colis available
  
      // Set hidden input for delete form
      const deleteFormIdInput = document.getElementById('delete-id');
      if (deleteFormIdInput) {
        deleteFormIdInput.value = colisId;
      }
  
      // Update modal text
      const modalBodyText = document.querySelector('#delete-modal .modal-body p');
      if (modalBodyText) {
        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.`;
      }
  
      // Show modal
      const deleteModal = document.getElementById('delete-modal');
      if (deleteModal) {
        deleteModal.classList.add('active');
      }
    });
  });
  
  // Close modal when clicking on close button or cancel
  document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
    button.addEventListener('click', function () {
      const modal = this.closest('.modal');
      if (modal) {
        modal.classList.remove('active');
      }
    });
  });
  
  // Confirm delete = Submit the hidden form
  document.getElementById('confirm-delete-btn').addEventListener('click', function () {
    this.disabled = true; // disable to avoid double-click
    const deleteForm = document.getElementById('delete-form');
    if (deleteForm) {
      deleteForm.submit();
    }
  });
  