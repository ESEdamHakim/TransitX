document.addEventListener("DOMContentLoaded", () => {
    // Select the search input field in the header
    const searchInput = document.querySelector(".search-bar input");

    // Select all rows in the vehicle table
    const tableRows = document.querySelectorAll(".rides-table tbody tr");

    // Add an event listener to the search input
    searchInput.addEventListener("input", () => {
        const searchValue = searchInput.value.toLowerCase();

        // Loop through each row and filter based on the search value
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchValue)) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        });
    });
});