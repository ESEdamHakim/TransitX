<?php
require_once '../../../Controller/ReclamationController.php';

session_start();

$ReclamationC = new ReclamationController();

// Always load dropdown data
$covoiturages = $ReclamationC->getAllCovoiturages();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (
    isset(
    $_POST['id_client'],
    $_POST['statut'],
    $_POST['date_rec'],
    $_POST['objet'],
    $_POST['description'],
    $_POST['id_covoit']
  )
  ) {
    $ReclamationC = new ReclamationController();
    $ReclamationC->addReclamation(
      $_POST['id_client'],
      $_POST['id_covoit'],
      $_POST['objet'],
      $_POST['description'],
      $_POST['date_rec'],
      $_POST['statut']
    );
    header("Location: RecList.php");
    exit();
  } else {
    echo "Erreur : tous les champs obligatoires ne sont pas remplis.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TransitX - Réclamation</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
</head>

<body>
  <?php include '../../assets/chatbot/chatbot.php'; ?>
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
          <li><a href="../blog/index.php">Blog</a></li>
          <li class="active"><a href="index.php">Réclamation</a></li>
          <li><a href="../vehicule/index.php">Véhicule</a></li>

        </ul>
      </nav>
      <div class="header-right">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
          <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
        <?php endif; ?>
        <a href="../../../index.php" class="btn btn-primary logout-btn">Déconnexion</a>
        <button class="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <main>
    <section class="reclamation-hero">
      <div class="hero-content">
        <h1>Service de Réclamations</h1>
        <p>Votre satisfaction est notre priorité. Nous sommes à votre écoute pour résoudre tout problème rencontré.</p>
        <a href="RecList.php" class="btn btn-primary">Mes Réclamations</a>
      </div>
    </section>

    <section class="reclamation-content">
      <div class="container">
        <div class="reclamation-tabs">
          <button class="tab-btn active" data-tab="new">Nouvelle réclamation</button>
          <button class="tab-btn" data-tab="faq">Questions fréquentes</button>
        </div>

        <div class="tab-content">
          <div class="tab-pane active" id="new-reclamation">
            <div class="form-intro">
              <h2>Soumettre une nouvelle réclamation</h2>
              <p>Veuillez remplir le formulaire ci-dessous avec autant de détails que possible pour nous permettre de
                traiter votre demande efficacement.</p>
            </div>

            <form class="reclamation-form" method="POST">

              <div class="form-section">
                <h3>Détails de la réclamation</h3>
                <input type="hidden" name="id_client" id="id_client" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="form-row">
                  <div class="form-group">
                    <label for="complaint-type">Objet de la réclamation</label>
                    <select id="complaint-type" name="objet">
                      <option value="">Sélectionner</option>
                      <option value="Retard">Retard</option>
                      <option value="Annulation">Annulation</option>
                      <option value="Dommage">Dommage</option>
                      <option value="Qualité de service">Qualité de service</option>
                      <option value="Facturation">Facturation</option>
                      <option value="Autre">Autre</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="incident-date">Date de l'incident</label>
                  <input type="date" name="date_rec" id="incident-date">
                </div>
                <div class="form-group">
                  <label for="id_covoit">Covoiturage :</label>
                  <select name="id_covoit" id="id_covoit">
                    <option value="">-- Sélectionner un covoiturage --</option>
                    <?php foreach ($covoiturages as $cov): ?>
                      <option value="<?= $cov['id_covoit'] ?>">
                        <?= $cov['lieu_depart'] ?> → <?= $cov['lieu_arrivee'] ?> (ID: <?= $cov['id_covoit'] ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="description">Description détaillée</label>
                  <textarea name="description" id="description" rows="5"
                    placeholder="Veuillez décrire votre problème en détail..."></textarea>
                </div>
                <input type="hidden" name="statut" id="statut" value="En attente">
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                  Soumettre ma réclamation
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="faq-reclamation">
            <div class="form-intro">
              <h2>Questions fréquentes</h2>
              <p>Consultez nos réponses aux questions les plus fréquentes concernant les réclamations.</p>
            </div>
            <div class="faq-items">
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Quel est le délai de traitement d'une réclamation ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Nous nous efforçons de traiter toutes les réclamations dans un délai de 5 jours ouvrables. Pour les
                    cas complexes, ce délai peut être prolongé jusqu'à 14 jours. Vous serez informé par email de
                    l'avancement de votre dossier.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Comment puis-je annuler une réclamation déjà soumise ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Pour annuler une réclamation, veuillez contacter notre service client par email à
                    reclamations@transitx.com en indiquant votre numéro de référence dans l'objet du message.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Quelles sont les compensations possibles en cas de retard ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>En cas de retard, plusieurs types de compensations peuvent être proposés selon la durée du retard
                    et la nature du service : remboursement partiel ou total, bon d'achat pour un prochain trajet, ou
                    services additionnels gratuits. Chaque situation est évaluée individuellement.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Puis-je modifier les informations de ma réclamation après l'avoir soumise ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Oui, vous pouvez modifier ou ajouter des informations à votre réclamation en utilisant la section
                    "Suivre ma réclamation" et en laissant un commentaire. Vous pouvez également contacter directement
                    notre service client.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Que faire si je ne suis pas satisfait de la résolution proposée ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Si vous n'êtes pas satisfait de la résolution proposée, vous pouvez demander une réévaluation de
                    votre dossier en répondant directement à l'email de résolution ou en laissant un commentaire dans la
                    section de suivi. Un responsable examinera à nouveau votre situation.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-info">
      <div class="container">
        <div class="section-header">
          <span class="badge">Contact</span>
          <h2>Autres moyens de nous contacter</h2>
          <p>Nous sommes disponibles pour vous aider par différents canaux de communication.</p>
        </div>
        <div class="contact-cards">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-phone"></i>
            </div>
            <h3>Téléphone</h3>
            <p>Service client disponible du lundi au vendredi, 9h-18h</p>
            <a href="tel:+33123456789">+216 71 123 456</a>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <h3>Email</h3>
            <p>Nous répondons généralement dans les 24 heures</p>
            <a href="mailto:reclamations@transitx.com">reclamations@transitx.com</a>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-comment-alt"></i>
            </div>
            <h3>Chat en direct</h3>
            <p>Assistance immédiate pendant les heures d'ouverture</p>
            <a href="#" class="btn btn-primary">Démarrer un chat</a>
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
            <li><a href="../blog/index.php">Blog</a></li>
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
    document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // Tab navigation
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
      button.addEventListener('click', function () {
        const tabId = button.getAttribute('data-tab');

        // Remove active class from all buttons and panes
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.classList.remove('active'));

        // Add active class to current button and pane
        button.classList.add('active');
        document.getElementById(tabId + '-reclamation').classList.add('active');
      });
    });

    // FAQ toggles
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-question');
      question.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });

    // Show tracking result on submit (demo purpose)
    const trackingForm = document.querySelector('.tracking-form');
    const trackingResult = document.querySelector('.tracking-result');

    if (trackingForm) {
      trackingForm.addEventListener('submit', function (e) {
        e.preventDefault();
        trackingResult.style.display = 'block';
        trackingForm.querySelector('input').value = '';
        // Scroll to results
        trackingResult.scrollIntoView({ behavior: 'smooth' });
      });
    }

    // Ensure dashboard button is visible
    document.querySelector('.dashboard-btn').style.display = 'inline-flex';
    document.querySelector('.logout-btn').style.display = 'inline-flex';
  </script>
  <script src="assets/js/recValidation.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="assets/js/chatbot.js"> </script>
</body>

</html>