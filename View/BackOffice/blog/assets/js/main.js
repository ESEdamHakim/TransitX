document.addEventListener("DOMContentLoaded", () => {

  // ========== Form Validation ==========
  window.validateForm = function () {
    let valid = true;

    const titre = document.getElementById("titre").value;
    const contenu = document.getElementById("contenu").value;
    const date_publication = document.getElementById("date_publication").value;
    const categorie = document.getElementById("categorie").value;
    const photo = document.getElementById("photo").value;
    const auteur = document.getElementById("auteur").value;
    const tags = document.getElementById("tags").value;
    const tagsPattern = /^#\w+(?:,\s*#\w+)*$/;

    document.getElementById("titre-error").textContent = titre ? "" : "Le titre est requis.";
    document.getElementById("contenu-error").textContent = contenu ? "" : "Le contenu est requis.";
    document.getElementById("date-error").textContent = date_publication ? "" : "La date de publication est requise.";
    document.getElementById("categorie-error").textContent = categorie ? "" : "La catégorie est requise.";
    document.getElementById("photo-error").textContent = photo ? "" : "La photo est requise.";
    document.getElementById("auteur-error").textContent = auteur ? "" : "L'auteur est requis.";

    if (!tags) {
      document.getElementById("tags-error").textContent = "Les tags sont requis.";
      valid = false;
    } else if (!tagsPattern.test(tags)) {
      document.getElementById("tags-error").textContent =
        "Les tags doivent commencer par un '#' et être séparés par des virgules.";
      valid = false;
    } else {
      document.getElementById("tags-error").textContent = "";
    }

    if (!titre || !contenu || !date_publication || !categorie || !photo || !auteur) valid = false;

    return valid;
  };

  // ========== Table Filtering ==========
  const searchInput = document.getElementById("searchInput"); // make sure HTML has id="searchInput"
  const tableRows = document.querySelectorAll(".buses-table tbody tr");
  const tabButtons = document.querySelectorAll(".tab-btn");

  let currentTab = "all"; // default tab

  function applyFilters() {
    const searchValue = searchInput.value.toLowerCase();

    tableRows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      const categoryCell = row.querySelector("td:nth-child(6)");
      const category = categoryCell ? categoryCell.textContent.trim() : "";

      const matchesSearch = rowText.includes(searchValue);
      const matchesTab = currentTab === "all" ||
        (currentTab === "actif" && category === "Conseils de voyage") ||
        (currentTab === "maintenance" && category === "Sécurité") ||
        (currentTab === "inactif" && category === "Économie et écologie");

      row.style.display = (matchesSearch && matchesTab) ? "" : "none";
    });

    updateCategoryCounters();
  }

  // Search filter
  searchInput.addEventListener("input", applyFilters);

  // Tab filtering
  tabButtons.forEach(button => {
    button.addEventListener("click", () => {
      tabButtons.forEach(btn => btn.classList.remove("active"));
      button.classList.add("active");
      currentTab = button.getAttribute("data-tab");
      applyFilters();
    });
  });

  // ========== Category Stats Counter ==========
  function updateCategoryCounters() {
    const counts = {
      "Conseils de voyage": 0,
      "Sécurité": 0,
      "Économie et écologie": 0
    };

    tableRows.forEach(row => {
      if (row.style.display !== "none") {
        const categoryCell = row.querySelector("td:nth-child(6)");
        if (categoryCell) {
          const category = categoryCell.textContent.trim();
          if (counts.hasOwnProperty(category)) {
            counts[category]++;
          }
        }
      }
    });

    document.getElementById("standardCount").textContent = counts["Conseils de voyage"];
    document.getElementById("tourismeCount").textContent = counts["Sécurité"];
    document.getElementById("scolaireCount").textContent = counts["Économie et écologie"];
  }

  // Initial count on load
  applyFilters();

});
