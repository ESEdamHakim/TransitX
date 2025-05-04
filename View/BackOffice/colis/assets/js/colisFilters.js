document.addEventListener("DOMContentLoaded", () => {
  const statusFilter = document.getElementById("status-filter");
  const dateFilter = document.getElementById("date-filter");
  const searchFilter = document.getElementById("search-filter");
  const priceSort = document.getElementById("price-sort");
  const applyBtn = document.getElementById("apply-filters");
  const resetBtn = document.getElementById("reset-filters");
  const tabButtons = document.querySelectorAll(".tab-btn");
  const parcels = document.querySelectorAll(".parcel-row");

  let currentTab = "all";

  function updateParcelStats() {
    const counts = {
      all: 0,
      pending: 0,
      "in-progress": 0,
      resolved: 0,
    };

    parcels.forEach(parcel => {
      const status = parcel.dataset.status;
      counts.all++;
      if (counts[status] !== undefined) {
        counts[status]++;
      }
    });

    document.querySelector(".stat-box.primary .stat-value").textContent = counts.all;
    document.querySelector(".stat-box.success .stat-value").textContent = counts.resolved;
    document.querySelector(".stat-box.warning .stat-value").textContent = counts["in-progress"];
    document.querySelector(".stat-box.danger .stat-value").textContent = counts.pending;
  }

  function filterParcels() {
    const selectedStatus = statusFilter?.value;
    const selectedDate = dateFilter?.value;
    const searchQuery = searchFilter?.value.toLowerCase();
    const priceDirection = priceSort?.value;

    let filteredParcels = Array.from(parcels).filter(parcel => {
      const parcelStatus = parcel.dataset.status;
      const parcelDate = parcel.dataset.date;
      const parcelCovoitId = parcel.dataset.covoitId?.toLowerCase();

      if (currentTab !== "all" && parcelStatus !== currentTab) return false;
      if (selectedStatus && selectedStatus !== "all" && parcelStatus !== selectedStatus) return false;
      if (selectedDate && parcelDate !== selectedDate) return false;
      if (searchQuery && parcelCovoitId && !parcelCovoitId.includes(searchQuery)) return false;

      return true;
    });

    // Sort by price
    if (priceDirection === "asc") {
      filteredParcels.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
    } else if (priceDirection === "desc") {
      filteredParcels.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
    }

    // Display logic
    parcels.forEach(parcel => parcel.style.display = "none");
    filteredParcels.forEach(parcel => parcel.style.display = "table-row");

    updateParcelStats();
  }

  function resetFilters() {
    statusFilter.value = "all";
    dateFilter.value = "";
    searchFilter.value = "";
    priceSort.value = "none";
    filterParcels();
  }

  tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      tabButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentTab = btn.dataset.tab;
      filterParcels();
    });
  });

  applyBtn.addEventListener("click", filterParcels);
  resetBtn.addEventListener("click", resetFilters);

  filterParcels(); // Initial run
});
