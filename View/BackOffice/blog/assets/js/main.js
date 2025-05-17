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
      document.getElementById("tags-error").textContent = "Les tags doivent commencer par un '#' et être séparés par des virgules.";
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

  let currentTab = "all";

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

  searchInput.addEventListener("input", applyFilters);

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
  function setupCloseModalHandlers() {
    document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
      button.addEventListener('click', function () {
        const modal = this.closest('.modal');
        if (modal) modal.classList.remove('active');
      });
    });
  }

  function setupDeleteButtonHandlers() {
    document.querySelectorAll('.action-btn.delete').forEach(button => {
      button.addEventListener('click', function () {
        const id = this.dataset.id;
        const deleteFormIdInput = document.getElementById('delete-id');
        if (deleteFormIdInput) deleteFormIdInput.value = id;

        const modalBodyText = document.querySelector('#delete-modal .modal-body p');
        if (modalBodyText) {
          modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer le bus ${id} ? Cette action est irréversible.`;
        }

        const deleteModal = document.getElementById('delete-modal');
        if (deleteModal) deleteModal.classList.add('active');
      });
    });
  }

  function setupConfirmDeleteButton() {
    const confirmDeleteButton = document.getElementById('confirm-delete-btn');
    if (confirmDeleteButton) {
      confirmDeleteButton.addEventListener('click', function () {
        this.disabled = true;
        const deleteForm = document.getElementById('delete-form');
        if (deleteForm) deleteForm.submit();
      });
    }
  }

  function setupViewButtonHandlers() {
    document.querySelectorAll('.action-btn.view').forEach(button => {
      button.addEventListener('click', function () {
        const titre = this.getAttribute('data-titre');
        const contenu = this.getAttribute('data-contenu');
        const auteur = this.getAttribute('data-auteur');
        const date = this.getAttribute('data-date');
        const categorie = this.getAttribute('data-categorie');
        const tags = this.getAttribute('data-tags');
        const photo = this.getAttribute('data-photo');

        document.getElementById('modalTitre').textContent = titre || '';
        document.getElementById('modalAuteur').textContent = auteur || '';
        document.getElementById('modalDate').textContent = date || '';
        document.getElementById('modalCategorie').textContent = categorie || '';
        document.getElementById('modalTags').textContent = tags || '';
        document.getElementById('modalContentText').textContent = contenu || '';

        const photoTd = document.getElementById('modalPhoto');
        if (photo) {
          photoTd.innerHTML = `<img src="../../assets/uploads/${photo}" alt="Photo Article" style="max-width:120px;">`;
        } else {
          photoTd.textContent = 'Aucune image';
        }

        document.getElementById('content-modal').classList.add('active');
      });
    });
  }

  function setupCommentsButtonHandlers() {
    document.querySelectorAll('.comments-btn').forEach(button => {
      button.addEventListener('click', function () {
        const articleId = this.getAttribute('data-article-id');
        document.querySelectorAll('.comments-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        fetch(`fetch_comments.php?id_article=${encodeURIComponent(articleId)}`)
          .then(response => response.text())
          .then(html => {
            document.getElementById('comments-list').innerHTML = html;
            document.getElementById('comments-modal').classList.add('active');
            setupDeleteCommentHandlers(); // re-attach handlers after loading
          });
      });
    });
  }

  function setupDeleteCommentHandlers() {
    document.querySelectorAll('.delete-comment-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        if (!confirm('Supprimer ce commentaire ?')) return;
        const commentId = this.getAttribute('data-comment-id');

        fetch('delete_comment.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id_commentaire=' + encodeURIComponent(commentId)
        })
          .then(res => res.text())
          .then(response => {
            if (response.trim() === 'success') {
              this.closest('.comment')?.remove();
            } else {
              alert('Erreur lors de la suppression.');
            }
          });
      });
    });
  }

  function initializeModalHandlers() {
    setupCloseModalHandlers();
    setupDeleteButtonHandlers();
    setupConfirmDeleteButton();
    setupViewButtonHandlers();
    setupCommentsButtonHandlers();
  }

  updateCategoryCounters();
  initializeModalHandlers();

});
