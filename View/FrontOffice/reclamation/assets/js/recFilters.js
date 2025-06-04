document.addEventListener("DOMContentLoaded", function () {
    const objetFilter = document.getElementById("objet-filter");
    const dateFilter = document.getElementById("date-filter");
    const searchFilter = document.getElementById("search-filter");

    const applyBtn = document.querySelector(".apply-btn");
    const resetBtn = document.querySelector(".reset-btn");

    const tableRows = document.querySelectorAll(".rec-table tbody tr");
    const tabs = document.querySelectorAll(".tabs-container .tab");

    function updateCounts() {
        const counts = {
            all: 0,
            refused: 0,
            pending: 0,
            "in-progress": 0,
            resolved: 0
        };

        tableRows.forEach(row => {
            const statusSpan = row.querySelector(".status");
            if (!statusSpan) return;

            const status = statusSpan.classList[1]; // e.g., 'pending'
            counts.all++;
            if (counts[status] !== undefined) counts[status]++;
        });

    }

    function applyFilters() {
        const selectedObjet = objetFilter.value.toLowerCase();
        const selectedDate = dateFilter.value;
        const searchTerm = searchFilter.value.toLowerCase();
        const selectedStatusClass = document.querySelector(".tab.active")?.dataset.status || "all";

        const objetMap = {
            retard: "retard",
            annulation: "annulation",
            dommage: "dommage",
            qualite_service: "qualité de service",
            facturation: "facturation",
            autre: "autre"
        };

        tableRows.forEach(row => {
            const objet = row.children[1].textContent.trim().toLowerCase();
            const date = row.children[2].textContent.trim();
            const covoitText = row.children[3].textContent.toLowerCase();
            const statusSpan = row.querySelector(".status");
            const status = statusSpan?.classList[1];

            const mappedObjet = objetMap[selectedObjet];
            const matchesObjet = selectedObjet === "all" || (mappedObjet && objet.includes(mappedObjet));
            const matchesDate = !selectedDate || date === selectedDate;
            const matchesSearch = !searchTerm || covoitText.includes(searchTerm);
            const matchesStatus = selectedStatusClass === "all" || status === selectedStatusClass;

            row.style.display = matchesObjet && matchesDate && matchesSearch && matchesStatus ? "" : "none";
        });

        updateCounts();
    }

    applyBtn.addEventListener("click", applyFilters);

    resetBtn.addEventListener("click", () => {
        objetFilter.value = "all";
        dateFilter.value = "";
        searchFilter.value = "";

        tabs.forEach(tab => tab.classList.remove("active"));
        tabs[0].classList.add("active");

        tableRows.forEach(row => row.style.display = "");
        updateCounts();
    });

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");
            applyFilters();
        });
    });

    updateCounts();
});
