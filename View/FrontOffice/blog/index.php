<!DOCTYPE html>
<html lang="fr">
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
  </style>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Blog</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('/TransitX-main/Controller/FrontOffice/get_articles.php')
  .then(response => response.json())
    .then(articles => {
      const container = document.querySelector('.blog-posts');
      container.innerHTML = '';

      articles.forEach(article => {
        const post = document.createElement('article');
        post.className = 'blog-post';
        post.innerHTML = `
          <img src="assets/blog1.jpeg" alt="Image" />
          <div class="post-info">
            <h3>${article.titre}</h3>
            <p>${article.contenu.substring(0, 100)}...</p>
            <a href="blog-detail.php?id=${article.id_article}" class="btn-primary">Lire la suite</a>
          </div>
        `;
        container.appendChild(post);
      });
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
        <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
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
            <!-- Article 1 -->
            <article class="blog-post">
            <img src="assets/blog1.jpeg" alt="Blog Post 1" width="300" />
            <div class="post-info">
                    <h3>Il existe des variations de passage disponibles.</h3>
                    <p>Découvrez les différentes options de transport que TransitX met à votre disposition, adaptées à vos besoins et à votre rythme.</p>
                    <a href="blog-detail.php?id=1" class="btn-primary">Lire la suite</a>
                </div>
            </article>

            <!-- Article 2 -->
            <article class="blog-post">
                <img src="assets/blog1.jpeg" alt="Blog Post 2" width="300" />
                <div class="post-info">
                    <h3>Autre titre d'article</h3>
                    <p>Brève description de l'article...</p>
                    <a href="blog-detail.php?id=2" class="btn-primary">Lire la suite</a>
                </div>
            </article>

            <!-- Article 3 -->
            <article class="blog-post">
                <img src="assets/blog1.jpeg" alt="Blog Post 3" width="300" />
                <div class="post-info">
                    <h3>Encore un autre article</h3>
                    <p>Brève description de l'article...</p>
                    <a href="blog-detail.php?id=3" class="btn-primary">Lire la suite</a>
                </div>
            </article>

            <!-- Duplicate the Articles (same content) -->
            <!-- Article 1 Duplicate -->
            <article class="blog-post">
                <img src="assets/blog1.jpeg" alt="Blog Post 1" width="300" />
                <div class="post-info">
                    <h3>Il existe des variations de passage disponibles.</h3>
                    <p>Découvrez les différentes options de transport que TransitX met à votre disposition, adaptées à vos besoins et à votre rythme.</p>
                    <a href="blog-detail.php?id=1" class="btn-primary">Lire la suite</a>
                </div>
            </article>

            <!-- Article 2 Duplicate -->
            <article class="blog-post">
                <img src="assets/blog1.jpeg" alt="Blog Post 2" width="300" />
                <div class="post-info">
                    <h3>Autre titre d'article</h3>
                    <p>Brève description de l'article...</p>
                    <a href="blog-detail.php?id=2" class="btn-primary">Lire la suite</a>
                </div>
            </article>

            <!-- Article 3 Duplicate -->
            <article class="blog-post">
                <img src="assets/blog1.jpeg" alt="Blog Post 3" width="300" />
                <div class="post-info">
                    <h3>Encore un autre article</h3>
                    <p>Brève description de l'article...</p>
                    <a href="blog-detail.php?id=3" class="btn-primary">Lire la suite</a>
                </div>
            </article>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 TransitX. Tous droits réservés.</p>
    </footer>

  <script>
    // Mobile menu toggle
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Ensure dashboard button is visible
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>
</body>
</html>
