<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Blog</title>

  <!-- Feuilles de style -->
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <style>
    .blog-posts {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      margin-top: 40px;
    }

    .blog-post {
      width: 300px;
      background-color: #f9f9f9;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .blog-post:hover {
      transform: translateY(-5px);
    }

    .blog-post img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .post-info {
      padding: 20px;
    }

    .author {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 14px;
      color: #666;
      font-weight: bold;
    }

    .post-info h3 {
      font-size: 20px;
      color: #1f4f65;
      margin-bottom: 10px;
    }

    .post-info p {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
    }

    .btn-primary {
      background-color: #97c3a2;
      color: white;
      border: none;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #1f4f65;
      color: white;
    }

    footer {
      background-color: #1f4f65;
      color: white;
      padding: 20px;
      text-align: center;
    }

    footer p {
      margin: 0;
    }

    .content {
      text-align: center;
    }

    .tags {
      font-size: 14px;
      color: #333;
      /* Couleur du texte des tags */
      font-family: 'Montserrat', sans-serif;
      font-weight: bold;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 15px;
    }

    .tags span {
      padding: 6px 12px;
      background-color: #dcdcdc;
      /* Fond des tags : gris clair */
      border-radius: 20px;
      /* Arrondi large pour un effet fluide */
      color: white;
      /* Texte blanc pour contraster avec le fond gris */
      text-transform: uppercase;
      /* Majuscules pour un look plus dynamique */
      font-size: 14px;
    }

    .tags span:hover {
      background-color: #b0b0b0;
      /* Effet au survol : gris plus foncé */
      cursor: pointer;
    }

    .clickable-tag {
      display: inline-block;
      background-color: #eee;
      color: #333;
      padding: 4px 8px;
      margin: 0 4px;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .clickable-tag:hover {
      background-color: #007bff;
      color: white;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      fetch('get_articles.php')
        .then(response => response.json())
        .then(articles => {

          const container = document.querySelector('.blog-posts');
          if (!container) return;

          container.innerHTML = '';
          articles.forEach(article => {

            const post = document.createElement('article');
            post.className = 'blog-post';
            post.innerHTML = `
          <div class="blog-post">
            <!-- Image de l'article en haut -->
            <img src="../../assets/uploads/${article.photo}" alt="Image de ${article.titre}" />
            <div class="post-info">
              <!-- Afficher l'auteur avec l'icône en haut à droite -->
              <p class="author">
                <i class="fas ${article.auteur_icon}"></i> ${article.auteur}
              </p>

              <!-- Affichage des tags avant le titre, sans # et avec couleur -->
              <p class="tags">
  ${article.tags ? article.tags.split(',').map(tag => `<span class="tag clickable-tag" data-tag="${tag.trim()}">${tag.trim()}</span>`).join(' ') : 'Pas de tags'}
</p>

              <!-- Titre de l'article -->
              <h3>${article.titre}</h3>

              <!-- Premier extrait du contenu -->
              <p>${article.contenu.substring(0, 100)}...</p>
              <p>
                <a href="blog-detail.php?id=${article.id_article}" class="btn-primary">Lire la suite</a>
              </p>

              <!-- Ajout de la catégorie ici -->
              <p class="categorie">
                <i class="fas fa-tag"></i> ${article.categorie || 'Pas de catégorie disponible'}
              </p>

              <!-- Nombre de commentaires -->
              <p>
                <a href="blog-detail.php?id=${article.id_article}#comments" class="comment-link">
                  <i class="fas fa-comment-dots"></i> ${article.nb_commentaires} commentaire${article.nb_commentaires > 1 ? 's' : ''}
                </a>
              </p>
            </div>
          </div>
        `;
            container.appendChild(post);
          });
          document.querySelectorAll('.clickable-tag').forEach(tagElement => {
            tagElement.addEventListener('click', function () {
              const selectedTag = this.dataset.tag.toLowerCase();

              document.querySelectorAll('.blog-post').forEach(post => {
                const tagContainer = post.querySelector('.tags');
                if (tagContainer && tagContainer.textContent.toLowerCase().includes(selectedTag)) {
                  post.style.display = 'block';
                } else {
                  post.style.display = 'none';
                }
              });

              window.scrollTo({ top: 0, behavior: 'smooth' });
            });
          });

        })
        .catch(error => {
          console.error("Erreur lors du chargement des articles :", error);
        });
    });

  </script>
</head>

<body>

  <header class="landing-header">
    <div class="container">
      <div class="header-left">
        <div class="logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="main-logo">
          <span class="logo-text">TransitX</span>
        </div>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="../index.php">Accueil</a></li>
          <li><a href="../bus/index.php">Bus</a></li>
          <li><a href="../colis/index.php">Colis</a></li>
          <li><a href="../covoiturage/index.php">Covoiturage</a></li>
          <li class="active"><a href="index.php">Blog</a></li>
          <li><a href="../reclamation/index.php">Réclamation</a></li>
        </ul>
      </nav>
      <div class="header-right">
        <!-- Affiche le bouton Dashboard uniquement si l'utilisateur est un employé -->
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employe'): ?>
          <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <?php endif; ?>
        <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
        <a href="calendrier.php"
          style="display: inline-flex; align-items: center; gap: 5px; font-size: 16px; text-decoration: none; color: inherit; background: none; border: 2px solid #97c3a2; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
          📅 <span>Calendrier</span>
        </a>

        <button class="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <section class="blog" id="blog">
    <div class="content">
      <h2>Notre blog</h2>
      <p>
        Restez informé avec les dernières nouvelles, idées et tendances sur la mobilité urbaine durable.
        Explorez nos articles pour découvrir l'avenir des transports.
      </p>
    </div>



    <div class="blog-posts">
      <!-- Articles dynamiques ajoutés ici par JS -->
    </div>
  </section>

  <footer>
    <p>&copy; 2023 TransitX. Tous droits réservés.</p>
  </footer>

  <script>
    // Menu mobile
    document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // Boutons de filtre (si présents)
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
      button.addEventListener('click', function () {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Affichage conditionnel boutons dashboard / logout
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>

</body>

</html>