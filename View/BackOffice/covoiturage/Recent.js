document.addEventListener("DOMContentLoaded", () => {
    const recentButton = document.querySelector(".tab-btn[data-tab='active']");
    const allButton = document.querySelector(".tab-btn[data-tab='all']");
    const tableRows = document.querySelectorAll(".rides-table tbody tr");

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split("T")[0];

    // Function to filter recent rows
    function filterRecentRows() {
        tableRows.forEach(row => {
            const dateCell = row.querySelector("td:nth-child(4)"); // 4th column is the date
            const rowDate = dateCell.textContent.trim();

            if (rowDate >= today) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        });
    }

    // Function to show all rows
    function showAllRows() {
        tableRows.forEach(row => {
            row.style.display = ""; // Show all rows
        });
    }

    // Add event listeners to the buttons
    recentButton.addEventListener("click", () => {
        filterRecentRows();
        recentButton.classList.add("active");
        allButton.classList.remove("active");
    });

    allButton.addEventListener("click", () => {
        showAllRows();
        allButton.classList.add("active");
        recentButton.classList.remove("active");
    });
});