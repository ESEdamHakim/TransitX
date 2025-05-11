document.addEventListener("DOMContentLoaded", () => {
  const statusFilter = document.getElementById("status-filter");
  const dateFilter = document.getElementById("date-filter");
  const searchFilter = document.getElementById("search-filter");
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

    const filteredParcels = Array.from(parcels).filter(parcel => {
      const parcelStatus = parcel.dataset.status;
      const parcelDate = parcel.dataset.date;
      const clientName = parcel.dataset.clientName;

      if (currentTab !== "all" && parcelStatus !== currentTab) return false;
      if (selectedStatus && selectedStatus !== "all" && parcelStatus !== selectedStatus) return false;
      if (selectedDate && parcelDate !== selectedDate) return false;
      if (searchQuery && clientName && !clientName.includes(searchQuery)) return false;

      return true;
    });

    parcels.forEach(parcel => parcel.style.display = "none");
    filteredParcels.forEach(parcel => parcel.style.display = "table-row");

    updateParcelStats();
  }

  function resetFilters() {
    if (statusFilter) statusFilter.value = "all";
    if (dateFilter) dateFilter.value = "";
    if (searchFilter) searchFilter.value = "";
    filterParcels();
  }

  // Tab clicks
  tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      tabButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentTab = btn.dataset.tab;
      filterParcels();
    });
  });

  // Search on typing
  if (searchFilter) {
    searchFilter.addEventListener("input", () => {
      filterParcels();
    });
  }

  // Apply/reset buttons
  if (applyBtn) applyBtn.addEventListener("click", filterParcels);
  if (resetBtn) resetBtn.addEventListener("click", resetFilters);

  // Initial run
  filterParcels();
});
