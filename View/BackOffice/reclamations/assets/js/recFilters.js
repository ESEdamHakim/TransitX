function searchClient() {
  const searchInput = document.querySelector('.search-bar input').value.toLowerCase(); // Get the search query
  const rows = document.querySelectorAll('.complaints-table tbody tr'); // Get all the table rows

  rows.forEach(row => {
    const clientNameCell = row.querySelector('td:nth-child(2)'); // Assuming "Client" name is in the 2nd column (adjust as needed)
    if (!clientNameCell) return;

    const clientName = clientNameCell.textContent.toLowerCase(); // Get the client name in lowercase for case-insensitive comparison

    // Show row if search matches the client name
    if (clientName.includes(searchInput)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

// Attach event listener to the search input field to trigger search on input
document.querySelector('.search-bar input').addEventListener('input', searchClient);

// Function to update stats based on status
function updateStats() {
  const rows = document.querySelectorAll('.complaints-table tbody tr');

  let totalCount = 0;
  let resolvedCount = 0;
  let inProgressCount = 0;
  let pendingCount = 0;
  let refusedCount = 0;

  rows.forEach(row => {
    totalCount++;
    const statusElement = row.querySelector('.status');
    const statusClass = statusElement ? statusElement.classList.value : '';

    // Count based on status
    if (statusClass.includes('resolved')) {
      resolvedCount++;
    } else if (statusClass.includes('in-progress')) {
      inProgressCount++;
    } else if (statusClass.includes('pending')) {
      pendingCount++;
    } else if (statusClass.includes('refused')) {
      refusedCount++;
    }
  });

  // Update stats boxes
  document.querySelector('.stat-box.primary .stat-value').textContent = totalCount;
  document.querySelector('.stat-box.success .stat-value').textContent = resolvedCount;
  document.querySelector('.stat-box.warning .stat-value').textContent = inProgressCount;
  document.querySelector('.stat-box.danger .stat-value').textContent = pendingCount;
  document.querySelector('.stat-box.refused .stat-value').textContent = refusedCount;
}

// Filter rows based on selected "objet"
function filterRows(selectedObjet) {
  const rows = document.querySelectorAll('.complaints-table tbody tr');

  rows.forEach(row => {
    const objetCell = row.querySelector('td:nth-child(3)'); // Assuming "Objet" is the 3rd column

    if (!objetCell) {
      row.style.display = 'none';
      return;
    }

    const objet = objetCell.textContent.trim();
    // Show or hide based on selected "objet"
    if (selectedObjet === 'all' || objet === selectedObjet) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

// On page load
document.addEventListener('DOMContentLoaded', function () {
  updateStats(); // Initialize stats based on status
  setupModalHandlers(); // Setup modal opening handlers
});

// Handle tab click for filtering by "objet"
document.querySelectorAll('.tab-btn').forEach(button => {
  button.addEventListener('click', function () {
    const objet = this.dataset.objet;

    // Set active tab
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    this.classList.add('active');

    // Filter rows and update stats
    filterRows(objet);
    updateStats();
  });
});

// Handle view button click to open the modal
document.querySelectorAll('.action-btn.view').forEach(button => {
  button.addEventListener('click', function() {
    const modal = document.getElementById('viewModal');
    // Fill modal fields with data attributes
    document.getElementById('modal-client').innerText = this.getAttribute('data-client');
    document.getElementById('modal-objet').innerText = this.getAttribute('data-objet');
    document.getElementById('modal-date').innerText = this.getAttribute('data-date');
    document.getElementById('modal-covoit').innerText = this.getAttribute('data-covoit');
    document.getElementById('modal-description').innerText = this.getAttribute('data-description');
    document.getElementById('modal-statut').innerText = this.getAttribute('data-statut');
    modal.classList.add('active'); // Open the modal by adding the 'active' class
  });
});

// Close modal functionality
document.querySelector('.close-modal').addEventListener('click', function() {
  const modal = document.getElementById('viewModal');
  modal.classList.remove('active'); // Close modal by removing the 'active' class
});

// Setup modal opening and closing when clicking view buttons
function setupModalHandlers() {
  const viewButtons = document.querySelectorAll('.action-btn.view');

  // Modal click handlers
  viewButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const modal = document.getElementById('viewModal');
      
      // Fill modal with data from button's dataset
      document.getElementById('modal-client').textContent = this.dataset.client;
      document.getElementById('modal-objet').textContent = this.dataset.objet;
      document.getElementById('modal-date').textContent = this.dataset.date;
      document.getElementById('modal-covoit').textContent = this.dataset.covoit;
      document.getElementById('modal-description').textContent = this.dataset.description;
      document.getElementById('modal-statut').textContent = this.dataset.statut;

      // Display the modal
      modal.classList.add('active');
    });
  });

  // Close modal when clicking the close button
  const closeModalButton = document.querySelector('.close-modal');
  if (closeModalButton) {
    closeModalButton.addEventListener('click', function () {
      document.getElementById('viewModal').classList.remove('active');
    });
  }

  // Close modal when clicking outside
  window.addEventListener('click', function (e) {
    const modal = document.getElementById('viewModal');
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
}
// Setup Delete Button click to open the confirmation modal
document.querySelectorAll('.action-btn.delete').forEach(button => {
  button.addEventListener('click', function () {
    const recId = this.dataset.id;            
    const clientNom = this.dataset.nom || '';  
    const clientPrenom = this.dataset.prenom || '';
    const fullName = `${clientNom} ${clientPrenom}`.trim();

    // Set hidden input for delete form
    const deleteFormIdInput = document.getElementById('delete-id');
    if (deleteFormIdInput) {
      deleteFormIdInput.value = recId;
    }

    // Update modal text
    const modalBodyText = document.querySelector('#delete-modal .modal-body p');
    if (modalBodyText) {
      modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer la réclamation de ${fullName} ? Cette action est irréversible.`;
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
  const deleteForm = document.getElementById('delete-form');
  if (deleteForm) {
    deleteForm.submit();
  }
});
document.getElementById('confirm-delete-btn').addEventListener('click', function () {
  this.disabled = true; // disable to avoid double-click
  const deleteForm = document.getElementById('delete-form');
  if (deleteForm) {
    deleteForm.submit();
  }
});
