document.querySelector('#exporter-btn').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Extracting the table data
    const headers = ['ID', 'Objet', 'Date', 'Covoiturage', 'Description', 'Statut'];
    const rows = [];

    const tableRows = document.querySelectorAll('.rec-table tbody tr');
    tableRows.forEach(row => {
        const cols = row.querySelectorAll('td');
        const rowData = [
            cols[0].innerText,  // ID
            cols[1].innerText,  // Objet
            cols[2].innerText,  // Date
            cols[3].innerText,  // Covoiturage
            cols[4].innerText,  // Description
            cols[5].innerText,  // Statut
        ];
        rows.push(rowData);
    });

    // Adding the table to the PDF
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 20, // Start Y position
        theme: 'grid',
    });

    // Save the generated PDF
    doc.save('reclamations.pdf');
});
