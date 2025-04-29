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
  document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
    button.addEventListener('click', function() {
      const modal = this.closest('.modal');
      if (modal) {
        modal.classList.remove('active');
      }
    });
  });
}

// Setup handlers for delete button and confirmation modal
function setupDeleteButtonHandlers() {
  document.querySelectorAll('.action-btn.delete').forEach(button => {
    button.addEventListener('click', function() {
      const busId = this.dataset.id;

      const deleteFormIdInput = document.getElementById('delete-id');
      if (deleteFormIdInput) {
        deleteFormIdInput.value = busId;
      }

      const modalBodyText = document.querySelector('#delete-modal .modal-body p');
      if (modalBodyText) {
        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer le bus ${busId} ? Cette action est irréversible.`;
      }

      const deleteModal = document.getElementById('delete-modal');
      if (deleteModal) {
        deleteModal.classList.add('active');
      }
    });
  });
}

// Handle confirm delete button click
function setupConfirmDeleteButton() {
  const confirmDeleteButton = document.getElementById('confirm-delete-btn');
  if (confirmDeleteButton) {
    confirmDeleteButton.addEventListener('click', function() {
      this.disabled = true;

      const deleteForm = document.getElementById('delete-form');
      if (deleteForm) {
        deleteForm.submit();
      }
    });
  }
}

// Count bus types
function countBusTypes() {
  let standardCount = 0;
  let tourismeCount = 0;
  let scolaireCount = 0;

  document.querySelectorAll('.buses-table tbody tr').forEach(row => {
    const cells = row.querySelectorAll('td');
    const typeBus = cells[4]?.textContent.trim().toLowerCase();

    if (typeBus === 'standard') {
      standardCount++;
    } else if (typeBus === 'tourisme') {
      tourismeCount++;
    } else if (typeBus === 'scolaire') {
      scolaireCount++;
    }
  });

  return { standard: standardCount, tourisme: tourismeCount, scolaire: scolaireCount };
}

// Update counters displayed in the page
function updateBusTypeCounters() {
  const counts = countBusTypes();
  if (document.getElementById('standardCount')) {
    document.getElementById('standardCount').textContent = counts.standard;
  }
  if (document.getElementById('tourismeCount')) {
    document.getElementById('tourismeCount').textContent = counts.tourisme;
  }
  if (document.getElementById('scolaireCount')) {
    document.getElementById('scolaireCount').textContent = counts.scolaire;
  }
}

// Initialize all modal and button handlers
function initializeModalHandlers() {
  setupCloseModalHandlers();
  setupDeleteButtonHandlers();
  setupConfirmDeleteButton();
}

// Initialize everything on page load
document.addEventListener('DOMContentLoaded', function() {
  initializeModalHandlers();
  updateBusTypeCounters(); // count bus types when page loads
});
