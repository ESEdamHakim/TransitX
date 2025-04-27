// Trajet Search
  document.querySelector('.header-right .search-bar:nth-of-type(2) input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.dashboard-content .crud-container > .buses-table-container:nth-of-type(2) tbody tr').forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
  });
  