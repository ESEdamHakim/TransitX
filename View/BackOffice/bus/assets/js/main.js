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
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchTerm) ? '' : 'none';
    });
  });
  
  