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
  const searchInput = document.getElementById("searchInput");
  const tableRows = document.querySelectorAll(".buses-table tbody tr");
  const tabButtons = document.querySelectorAll(".tab-btn");

  let currentTab = "all"; // default tab

  function applyFilters() {
    const searchValue = searchInput.value.toLowerCase();

    tableRows.forEach(row => {
      const titleCell = row.querySelector("td:nth-child(2)");
      const categoryCell = row.querySelector("td:nth-child(6)");

      const title = titleCell ? titleCell.textContent.toLowerCase().trim() : "";
      const category = categoryCell ? categoryCell.textContent.trim() : "";

      const matchesSearch = title.includes(searchValue);
      const matchesTab = currentTab === "all" ||
        (currentTab === "Conseils de voyage" && category === "Conseils de voyage") ||
        (currentTab === "Sécurité" && category === "Sécurité") ||
        (currentTab === "Économie et écologie" && category === "Économie et écologie") ||
        (currentTab === "Autre" && category === "Autre");

      row.style.display = (matchesSearch && matchesTab) ? "" : "none";
    });

  }

  // Search filter (title only)
  searchInput.addEventListener("input", applyFilters);

  // Tab filter
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
      "Économie et écologie": 0,
      "Autre": 0
    };

    tableRows.forEach(row => {
      const categoryCell = row.querySelector("td:nth-child(6)");
      if (categoryCell) {
        const category = categoryCell.textContent.trim();
        if (counts.hasOwnProperty(category)) {
          counts[category]++;
        }
      }
    });

    document.getElementById("standardCount").textContent = counts["Conseils de voyage"];
    document.getElementById("tourismeCount").textContent = counts["Sécurité"];
    document.getElementById("scolaireCount").textContent = counts["Économie et écologie"];
    document.getElementById("autreCount").textContent = counts["Autre"];
  }

  updateCategoryCounters(); 
});
