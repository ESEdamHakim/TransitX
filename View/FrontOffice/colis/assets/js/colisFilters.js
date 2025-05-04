document.addEventListener("DOMContentLoaded", function () {
  const statusFilter = document.getElementById("status-filter");
  const dateFilter = document.getElementById("date-filter");
  const searchFilter = document.getElementById("search-filter");
  const priceSort = document.getElementById("price-sort");

  const applyBtn = document.querySelector(".apply-btn");
  const resetBtn = document.querySelector(".reset-btn");

  const cardsContainer = document.querySelector(".route-cards");
  const tabs = document.querySelectorAll(".tabs-container .tab");

  // Update counts for all route cards, irrespective of visibility
  function updateCounts() {
      const counts = {
          all: 0,
          pending: 0,
          "in-progress": 0,
          resolved: 0,
      };

      const cards = document.querySelectorAll(".route-card");
      cards.forEach(card => {
          const status = card.dataset.status;
          counts.all++;
          if (counts[status] !== undefined) counts[status]++;
      });

      tabs.forEach(tab => {
          const status = tab.dataset.status;
          if (counts[status] !== undefined) {
              tab.querySelector(".count").textContent = counts[status];
          }
      });
  }

  // Apply filters to cards and update visibility
  function applyFilters() {
      const selectedStatus = statusFilter.value;
      const selectedDate = dateFilter.value;
      const searchTerm = searchFilter.value.toLowerCase();
      const sortOption = priceSort.value;

      let cards = Array.from(document.querySelectorAll(".route-card"));

      cards.forEach(card => {
          const cardStatus = card.dataset.status; // e.g., "pending", "in-progress", "resolved"
          const cardDate = card.dataset.date;
          const covoitID = card.dataset.covoitId;
          const cardText = card.textContent.toLowerCase();

          const statusMatch = (selectedStatus === "all" || cardStatus === selectedStatus);
          const dateMatch = (!selectedDate || cardDate === selectedDate);
          const searchMatch = (!searchTerm || cardText.includes(searchTerm) || covoitID.includes(searchTerm));

          if (statusMatch && dateMatch && searchMatch) {
              card.style.display = "flex";
          } else {
              card.style.display = "none";
          }
      });

      // Apply sorting
      if (sortOption !== "none") {
          cards = cards.filter(card => card.style.display !== "none");

          cards.sort((a, b) => {
              const priceA = parseFloat(a.dataset.price);
              const priceB = parseFloat(b.dataset.price);
              return sortOption === "asc" ? priceA - priceB : priceB - priceA;
          });

          // Append sorted cards
          cards.forEach(card => cardsContainer.appendChild(card));
      }

      updateCounts();
  }

  applyBtn.addEventListener("click", applyFilters);

  resetBtn.addEventListener("click", () => {
      statusFilter.value = "all";
      dateFilter.value = "";
      searchFilter.value = "";
      priceSort.value = "none";

      document.querySelectorAll(".route-card").forEach(card => {
          card.style.display = "flex";
      });

      updateCounts();
  });

  // Tab clicks to trigger filters
  tabs.forEach(tab => {
      tab.addEventListener("click", () => {
          tabs.forEach(t => t.classList.remove("active")); // Remove active class from all tabs
          tab.classList.add("active"); // Add active class to clicked tab

          statusFilter.value = tab.dataset.status; // Update status filter based on tab
          applyFilters(); // Apply filter
      });
  });

  // Ensure the active tab is updated based on the current filter
  function updateActiveTab() {
      tabs.forEach(tab => {
          if (tab.dataset.status === statusFilter.value) {
              tab.classList.add("active");
          } else {
              tab.classList.remove("active");
          }
      });
  }

  // Update the active tab when the status filter changes
  statusFilter.addEventListener("change", () => {
      updateActiveTab();
      applyFilters();
  });

  // Set the initial active tab based on the default filter value
  updateActiveTab();

  updateCounts();
});
