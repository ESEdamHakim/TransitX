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
  this.closest('.modal').style.display = 'none';
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
