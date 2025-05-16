document.addEventListener("DOMContentLoaded", () => {

  // ========== Form Validation ==========
  window.validateForm = function () {
    let valid = true;

    const titre = document.getElementById("titre").value.trim();
    const contenu = document.getElementById("contenu").value.trim();
    const date_publication = document.getElementById("date_publication").value.trim();
    const categorie = document.getElementById("categorie").value.trim();
    const photo = document.getElementById("photo").value.trim();
    const auteur = document.getElementById("auteur").value.trim();
    const tags = document.getElementById("tags").value.trim();
    const tagsPattern = /^#\w+(?:,\s*#\w+)*$/;

    // Clear previous errors and set new ones if invalid
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

    // Final overall check
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
    const authorCell = row.querySelector("td:nth-child(4)");
    const categoryCell = row.querySelector("td:nth-child(6)");

    const title = titleCell ? titleCell.textContent.toLowerCase().trim() : "";
    const author = authorCell ? authorCell.textContent.toLowerCase().trim() : "";
    const category = categoryCell ? categoryCell.textContent.trim() : "";

    const matchesSearch = title.includes(searchValue) || author.includes(searchValue);
    const matchesTab =
      currentTab === "all" ||
      (currentTab === "Conseils de voyage" && category === "Conseils de voyage") ||
      (currentTab === "Sécurité" && category === "Sécurité") ||
      (currentTab === "Économie et écologie" && category === "Économie et écologie") ||
      (currentTab === "Autre" && category === "Autre");

    row.style.display = (matchesSearch && matchesTab) ? "" : "none";
  });
}


  // Bind search input event
  searchInput.addEventListener("input", applyFilters);

  // Bind tab buttons event
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

  // ========== Modal Handling ==========

  // Close modal on clicking close buttons or cancel buttons
  function setupCloseModalHandlers() {
    document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
      button.addEventListener('click', function() {
        const modal = this.closest('.modal');
        if (modal) {
          modal.classList.remove('active');
        }
      });
    });
  }

  // Setup delete buttons to open confirmation modal with correct info
  function setupDeleteButtonHandlers() {
    document.querySelectorAll('.action-btn.delete').forEach(button => {
      button.addEventListener('click', function() {
        const busId = this.dataset.id;

        const deleteFormIdInput = document.getElementById('delete-id');
        if (deleteFormIdInput) {
          deleteFormIdInput.value = busId;
        }

        const modalBodyText = document.querySelector('#delete-modal .modal-body p');
        if (modalBodyText) {
          modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer le bus ${busId} ? Cette action est irréversible.`;
        }

        const deleteModal = document.getElementById('delete-modal');
        if (deleteModal) {
          deleteModal.classList.add('active');
        }
      });
    });
  }

  // Handle confirm delete button click to submit the form and disable button
  function setupConfirmDeleteButton() {
    const confirmDeleteButton = document.getElementById('confirm-delete-btn');
    if (confirmDeleteButton) {
      confirmDeleteButton.addEventListener('click', function() {
        this.disabled = true;

        const deleteForm = document.getElementById('delete-form');
        if (deleteForm) {
          deleteForm.submit();
        }
      });
    }
  }

  // Initialize modal and button handlers
  function initializeModalHandlers() {
    setupCloseModalHandlers();
    setupDeleteButtonHandlers();
    setupConfirmDeleteButton();
  }

  // Run initial updates and bind handlers
  updateCategoryCounters();
  initializeModalHandlers();

});
