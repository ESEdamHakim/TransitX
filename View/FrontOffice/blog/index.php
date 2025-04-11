<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Blog</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
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

  <main>
    <section class="blog-hero">
      <div class="hero-content">
        <h1>Blog TransitX</h1>
        <p>Actualités, conseils et tendances sur la mobilité urbaine durable.</p>
      </div>
    </section>

    <section class="blog-content">
      <div class="container">
        <div class="section-header">
          <span class="badge">Articles</span>
          <h2>Nos derniers articles</h2>
          <p>Découvrez nos dernières publications sur la mobilité durable et les transports écologiques.</p>
        </div>
        <div class="blog-layout">
          <div class="blog-main">
            <div class="blog-filters">
              <div class="search-box">
                <input type="text" placeholder="Rechercher un article...">
                <button><i class="fas fa-search"></i></button>
              </div>
              <div class="category-filters">
                <span>Filtrer par catégorie :</span>
                <div class="filter-buttons">
                  <button class="filter-btn active">Tous</button>
                  <button class="filter-btn">Mobilité Verte</button>
                  <button class="filter-btn">Covoiturage</button>
                  <button class="filter-btn">Transport Public</button>
                  <button class="filter-btn">Conseils</button>
                </div>
              </div>
            </div>

            <div class="featured-post">
              <div class="post-image">
                <img src="../../assets/images/blog-1.jpg" alt="Featured Post">
              </div>
              <div class="post-content">
                <div class="post-metadata">
                  <span class="category">Mobilité Verte</span>
                  <span class="date"><i class="far fa-calendar-alt"></i> 10 Juin 2023</span>
                </div>
                <h2>Comment la mobilité partagée transforme nos villes</h2>
                <p>Les services de mobilité partagée tels que le covoiturage, l'autopartage et les vélos en libre-service révolutionnent la façon dont nous nous déplaçons en ville...</p>
                <a href="article.php" class="btn btn-primary">
                  Lire la suite
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>

            <div class="posts-grid">
              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-2.jpg" alt="Post">
                  <div class="category-badge">Covoiturage</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 5 Juin 2023</span>
                  </div>
                  <h3>5 conseils pour un covoiturage réussi</h3>
                  <p>Découvrez nos astuces pour rendre vos expériences de covoiturage plus agréables et sécurisées...</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-3.jpg" alt="Post">
                  <div class="category-badge">Transport Public</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 1 Juin 2023</span>
                  </div>
                  <h3>L'avenir des bus électriques en France</h3>
                  <p>Le déploiement des bus électriques s'accélère dans les villes françaises. Quels sont les bénéfices et les défis à relever ?</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-1.jpg" alt="Post">
                  <div class="category-badge">Conseils</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 28 Mai 2023</span>
                  </div>
                  <h3>Comment calculer et réduire votre empreinte carbone liée aux transports</h3>
                  <p>Des outils simples et des actions concrètes pour mesurer et diminuer l'impact environnemental de vos déplacements quotidiens...</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-2.jpg" alt="Post">
                  <div class="category-badge">Mobilité Verte</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 25 Mai 2023</span>
                  </div>
                  <h3>Les zones à faibles émissions se multiplient : ce que vous devez savoir</h3>
                  <p>Comprendre le fonctionnement des ZFE et leur impact sur vos déplacements urbains...</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-3.jpg" alt="Post">
                  <div class="category-badge">Covoiturage</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 20 Mai 2023</span>
                  </div>
                  <h3>Covoiturage et éco-conduite : doublez vos économies</h3>
                  <p>Combinez le partage de trajets avec des techniques d'éco-conduite pour réduire drastiquement vos dépenses en carburant et l'usure de votre véhicule...</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>

              <div class="post-card">
                <div class="post-image">
                  <img src="../../assets/images/blog-1.jpg" alt="Post">
                  <div class="category-badge">Transport Public</div>
                </div>
                <div class="post-content">
                  <div class="post-metadata">
                    <span class="date"><i class="far fa-calendar-alt"></i> 15 Mai 2023</span>
                  </div>
                  <h3>Les applications de mobilité multimodale : comparatif des meilleures solutions</h3>
                  <p>Comment choisir la meilleure application pour combiner différents modes de transport et optimiser vos itinéraires...</p>
                  <a href="article.php" class="btn btn-outline">
                    Lire la suite
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="pagination">
              <a href="#" class="pagination-link active">1</a>
              <a href="#" class="pagination-link">2</a>
              <a href="#" class="pagination-link">3</a>
              <a href="#" class="pagination-link next">Suivant <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>

          <div class="blog-sidebar">
            <div class="sidebar-section about">
              <h3>À propos du blog</h3>
              <p>Bienvenue sur le blog TransitX, votre source d'information sur la mobilité urbaine durable, le covoiturage, et les solutions de transport écologiques.</p>
            </div>

            <div class="sidebar-section popular-posts">
              <h3>Articles populaires</h3>
              <div class="popular-post">
                <img src="../../assets/images/blog-1.jpg" alt="Popular Post">
                <div>
                  <h4>Comment économiser sur vos trajets quotidiens</h4>
                  <span class="date">15 Avril 2023</span>
                </div>
              </div>
              <div class="popular-post">
                <img src="../../assets/images/blog-2.jpg" alt="Popular Post">
                <div>
                  <h4>Les villes les plus avancées en mobilité durable</h4>
                  <span class="date">2 Mai 2023</span>
                </div>
              </div>
              <div class="popular-post">
                <img src="../../assets/images/blog-3.jpg" alt="Popular Post">
                <div>
                  <h4>Guide du débutant pour le covoiturage</h4>
                  <span class="date">10 Avril 2023</span>
                </div>
              </div>
            </div>

            <div class="sidebar-section categories">
              <h3>Catégories</h3>
              <ul>
                <li><a href="#">Mobilité Verte <span>(12)</span></a></li>
                <li><a href="#">Covoiturage <span>(8)</span></a></li>
                <li><a href="#">Transport Public <span>(10)</span></a></li>
                <li><a href="#">Conseils <span>(15)</span></a></li>
                <li><a href="#">Nouvelles Technologies <span>(7)</span></a></li>
              </ul>
            </div>

            <div class="sidebar-section newsletter">
              <h3>Restez informé</h3>
              <p>Inscrivez-vous à notre newsletter pour recevoir nos derniers articles et conseils.</p>
              <form>
                <input type="email" placeholder="Votre email">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="main-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-logo">
          <img src="../../assets/images/logo.png" alt="TransitX Logo" class="footer-logo-img">
          <span>TransitX</span>
        </div>
        <div class="footer-slogan">
          <p>Move Green, Live Clean</p>
        </div>
        <div class="footer-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-middle">
        <div class="footer-column">
          <h4>Services</h4>
          <ul>
            <li><a href="../bus/index.php">Bus</a></li>
            <li><a href="../covoiturage/index.php">Covoiturage</a></li>
            <li><a href="../colis/index.php">Colis</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>À propos</h4>
          <ul>
            <li><a href="../about.php">Notre mission</a></li>
            <li><a href="index.php">Blog</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Légal</h4>
          <ul>
            <li><a href="#">Conditions d'utilisation</a></li>
            <li><a href="#">Politique de confidentialité</a></li>
            <li><a href="#">Cookies</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Contact</h4>
          <ul>
            <li><i class="fas fa-map-marker-alt"></i> 123 Avenue Habib Bourguiba, Tunis</li>
            <li><i class="fas fa-phone"></i> +216 71 123 456</li>
            <li><i class="fas fa-envelope"></i> contact@transitx.com</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 TransitX. Tous droits réservés.</p>
      </div>
    </div>
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
