// Function to update stats based on the table data
function updateStats() {
    // Select all table rows
    const rows = document.querySelectorAll('.complaints-table tbody tr');
  
    // Initialize counters
    let totalCount = 0;
    let resolvedCount = 0;
    let inProgressCount = 0;
    let pendingCount = 0;
    let refusedCount = 0;
  
    // Loop through each row and count the statuses
    rows.forEach(row => {
      totalCount++;
  
      // Get the status of the current row
      const statusElement = row.querySelector('.status');
      const statusClass = statusElement ? statusElement.classList.value : '';
  
      // Increment the counters based on the status class
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
  
    // Update the stat values in the dashboard
    document.querySelector('.stat-box.primary .stat-value').textContent = totalCount;
    document.querySelector('.stat-box.success .stat-value').textContent = resolvedCount;
    document.querySelector('.stat-box.warning .stat-value').textContent = inProgressCount;
    document.querySelector('.stat-box.danger .stat-value').textContent = pendingCount;
    document.querySelector('.stat-box.refused .stat-value').textContent = refusedCount;
  }
  
  // Call updateStats when the page loads
  document.addEventListener('DOMContentLoaded', function() {
    updateStats(); // Initial update when page loads
  });
  
  // Call updateStats whenever the tab filter changes
  document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', function () {
      const status = this.dataset.tab;
  
      // Set active tab
      document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
  
      // Call the filterRows function to filter table rows based on the selected tab
      filterRows(status);
      
      // Update stats after the table is filtered
      updateStats();
    });
  });
  
  function filterRows(status) {
    // Select all table rows
    const rows = document.querySelectorAll('.complaints-table tbody tr');
  
    // Loop through each row
    rows.forEach(row => {
      // Get the status of the current row (the class name assigned in PHP)
      const rowStatus = row.querySelector('.status');
  
      // If the row has no status, hide it
      if (!rowStatus) {
        row.style.display = 'none';
        return;
      }
  
      // Extract the status class from the row's status cell
      const statusClass = rowStatus.classList.value.match(/status (\w+)/);
      const currentStatus = statusClass ? statusClass[1] : '';
  
      // Show or hide rows based on the selected filter
      if (status === 'all' || currentStatus === status.toLowerCase()) {
        row.style.display = '';  // Show row
      } else {
        row.style.display = 'none';  // Hide row
      }
    });
  }
  