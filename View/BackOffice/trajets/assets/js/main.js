// Trajet Search 
document.querySelector('.header-right .search-bar:nth-of-type(1) input').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase();

  // Select all rows from the table (1st buses-table-container)
  document.querySelectorAll('.dashboard-content .crud-container .buses-table-container tbody tr').forEach(row => {
    // Get the third column, which corresponds to place_arrivee
    const placeArriveeCell = row.querySelector('td:nth-child(3)');
    const placeArriveeText = placeArriveeCell ? placeArriveeCell.innerText.toLowerCase() : '';

    // If the place_arrivee text matches the search term, display the row, otherwise hide it
    row.style.display = placeArriveeText.includes(searchTerm) ? '' : 'none';
  });
});
