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
          <li><a href="../blog/index.php">Blog</a></li>
          <li class="active"><a href="index.php">Réclamation</a></li>
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
    <section class="reclamation-hero">
      <div class="hero-content">
        <h1>Service de Réclamations</h1>
        <p>Votre satisfaction est notre priorité. Nous sommes à votre écoute pour résoudre tout problème rencontré.</p>
      </div>
    </section>

    <section class="reclamation-content">
      <div class="container">
        <div class="section-header">
          <span class="badge">Assistance</span>
          <h2>Comment pouvons-nous vous aider ?</h2>
          <p>Nous sommes là pour vous aider à résoudre tout problème que vous pourriez rencontrer avec nos services.</p>
        </div>
        <div class="reclamation-tabs">
          <button class="tab-btn active" data-tab="new">Nouvelle réclamation</button>
          <button class="tab-btn" data-tab="track">Suivre ma réclamation</button>
          <button class="tab-btn" data-tab="faq">Questions fréquentes</button>
        </div>

        <div class="tab-content">
          <div class="tab-pane active" id="new-reclamation">
            <div class="form-intro">
              <h2>Soumettre une nouvelle réclamation</h2>
              <p>Veuillez remplir le formulaire ci-dessous avec autant de détails que possible pour nous permettre de traiter votre demande efficacement.</p>
            </div>

            <form class="reclamation-form">
              <div class="form-section">
                <h3>Vos informations</h3>
                <div class="form-row">
                  <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone">
                  </div>
                  <div class="form-group">
                    <label for="order-number">Numéro de réservation (si applicable)</label>
                    <input type="text" id="order-number">
                  </div>
                </div>
              </div>

              <div class="form-section">
                <h3>Détails de la réclamation</h3>
                <div class="form-row">
                  <div class="form-group">
                    <label for="service-type">Type de service concerné</label>
                    <select id="service-type" required>
                      <option value="">Sélectionner</option>
                      <option value="bus">Bus</option>
                      <option value="colis">Colis</option>
                      <option value="covoiturage">Covoiturage</option>
                      <option value="autre">Autre</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="complaint-type">Nature de la réclamation</label>
                    <select id="complaint-type" required>
                      <option value="">Sélectionner</option>
                      <option value="delay">Retard</option>
                      <option value="cancellation">Annulation</option>
                      <option value="damage">Dommage</option>
                      <option value="service">Qualité de service</option>
                      <option value="billing">Facturation</option>
                      <option value="other">Autre</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="incident-date">Date de l'incident</label>
                  <input type="date" id="incident-date" required>
                </div>
                <div class="form-group">
                  <label for="description">Description détaillée</label>
                  <textarea id="description" rows="5" required placeholder="Veuillez décrire votre problème en détail..."></textarea>
                </div>
                <div class="form-group">
                  <label for="attachments">Pièces jointes (optionnel)</label>
                  <input type="file" id="attachments" multiple>
                  <small>Vous pouvez joindre des photos, reçus ou autres documents pertinents (max 5MB par fichier)</small>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                  Soumettre ma réclamation
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="track-reclamation">
            <div class="form-intro">
              <h2>Suivre ma réclamation</h2>
              <p>Entrez votre numéro de référence pour suivre l'état de votre réclamation.</p>
            </div>
            <div class="tracking-form">
              <div class="form-group">
                <label for="reference-number">Numéro de référence</label>
                <input type="text" id="reference-number" placeholder="Ex: REC-123456">
              </div>
              <button type="submit" class="btn btn-primary">
                Vérifier
                <i class="fas fa-search"></i>
              </button>
            </div>
            <div class="tracking-result" style="display: none;">
              <h3>Statut de votre réclamation</h3>
              <div class="status-timeline">
                <div class="status-step completed">
                  <div class="step-icon"><i class="fas fa-check"></i></div>
                  <div class="step-content">
                    <h4>Réclamation reçue</h4>
                    <p>Nous avons bien reçu votre réclamation.</p>
                    <span class="step-date">12 Juin 2023</span>
                  </div>
                </div>
                <div class="status-step completed">
                  <div class="step-icon"><i class="fas fa-check"></i></div>
                  <div class="step-content">
                    <h4>En cours d'examen</h4>
                    <p>Notre équipe examine votre dossier.</p>
                    <span class="step-date">13 Juin 2023</span>
                  </div>
                </div>
                <div class="status-step active">
                  <div class="step-icon"><i class="fas fa-sync-alt"></i></div>
                  <div class="step-content">
                    <h4>Traitement en cours</h4>
                    <p>Des actions sont en cours pour résoudre votre problème.</p>
                    <span class="step-date">En cours</span>
                  </div>
                </div>
                <div class="status-step">
                  <div class="step-icon"><i class="fas fa-hourglass-half"></i></div>
                  <div class="step-content">
                    <h4>Solution proposée</h4>
                    <p>Une résolution vous a été proposée.</p>
                  </div>
                </div>
                <div class="status-step">
                  <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                  <div class="step-content">
                    <h4>Réclamation clôturée</h4>
                    <p>Votre dossier a été résolu.</p>
                  </div>
                </div>
              </div>
              <div class="agent-response">
                <h4>Message de votre conseiller</h4>
                <div class="message">
                  <p>Bonjour, nous avons bien pris en compte votre réclamation concernant le retard de votre bus. Nous analysons actuellement la situation et reviendrons vers vous dans les 48 heures avec une proposition. Nous vous remercions pour votre patience.</p>
                  <p class="signature">- Marie Dubois, Service Client</p>
                </div>
              </div>
              <div class="reply-section">
                <h4>Ajouter un commentaire</h4>
                <textarea rows="3" placeholder="Écrivez votre message ici..."></textarea>
                <button class="btn btn-primary">Envoyer</button>
              </div>
            </div>
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
                  <p>Nous nous efforçons de traiter toutes les réclamations dans un délai de 5 jours ouvrables. Pour les cas complexes, ce délai peut être prolongé jusqu'à 14 jours. Vous serez informé par email de l'avancement de votre dossier.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Comment puis-je annuler une réclamation déjà soumise ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Pour annuler une réclamation, veuillez contacter notre service client par email à reclamations@transitx.com en indiquant votre numéro de référence dans l'objet du message.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Quelles sont les compensations possibles en cas de retard ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>En cas de retard, plusieurs types de compensations peuvent être proposés selon la durée du retard et la nature du service : remboursement partiel ou total, bon d'achat pour un prochain trajet, ou services additionnels gratuits. Chaque situation est évaluée individuellement.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Puis-je modifier les informations de ma réclamation après l'avoir soumise ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Oui, vous pouvez modifier ou ajouter des informations à votre réclamation en utilisant la section "Suivre ma réclamation" et en laissant un commentaire. Vous pouvez également contacter directement notre service client.</p>
                </div>
              </div>
              <div class="faq-item">
                <div class="faq-question">
                  <h3>Que faire si je ne suis pas satisfait de la résolution proposée ?</h3>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  <p>Si vous n'êtes pas satisfait de la résolution proposée, vous pouvez demander une réévaluation de votre dossier en répondant directement à l'email de résolution ou en laissant un commentaire dans la section de suivi. Un responsable examinera à nouveau votre situation.</p>
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
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
      document.querySelector('.main-nav').classList.toggle('active');
    });

    // Tab navigation
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
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
      trackingForm.addEventListener('submit', function(e) {
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
</body>
</html>
