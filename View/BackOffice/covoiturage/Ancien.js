document.addEventListener("DOMContentLoaded", () => {
    const pastButton = document.querySelector(".tab-btn[data-tab='pending']");
    const tableRows = document.querySelectorAll(".rides-table tbody tr");

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split("T")[0];

    // Function to filter past rows
    function filterPastRows() {
        tableRows.forEach(row => {
            const dateCell = row.querySelector("td:nth-child(4)"); // 4th column is the date
            const rowDate = dateCell.textContent.trim();

            if (rowDate < today) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        });
    }

    // Add event listener to the "Anciens" button
    if (pastButton) {
        pastButton.addEventListener("click", () => {
            filterPastRows();
            pastButton.classList.add("active");

            // Remove the active class from other buttons
            const otherButtons = document.querySelectorAll(".tab-btn");
            otherButtons.forEach(button => {
                if (button !== pastButton) {
                    button.classList.remove("active");
                }
            });
        });
    } else {
        console.error("Anciens button not found.");
    }
});