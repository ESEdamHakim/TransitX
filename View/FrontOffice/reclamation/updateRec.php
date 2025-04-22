<?php
require_once '../../../Controller/ReclamationController.php';

// Check if an ID is provided in the URL
if (!isset($_GET['id_rec'])) {
    die("Invalid Request");
}

$id_rec = $_GET['id_rec'];
$reclamation = null;

// Fetch the existing reclamation details
$ReclamationC = new ReclamationController();
$list = $ReclamationC->listReclamation();
foreach ($list as $r) {
    if ($r['id_rec'] == $id_rec) {
        $reclamation = $r;
        break;
    }
}

if (!$reclamation) {
    die("Reclamation not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_covoit = !empty($_POST['id_covoit']) ? $_POST['id_covoit'] : NULL;

    $ReclamationC->updateReclamation(
        $id_rec,
        $_POST['id_client'],
        $id_covoit,
        $_POST['objet'],
        $_POST['description'],
        $_POST['date_rec'],
        $_POST['statut']
    );

    header("Location: RecList.php"); // Redirect to the reclamation list
    exit();
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
                <p>Votre satisfaction est notre priorité. Nous sommes à votre écoute pour résoudre tout problème
                    rencontré.</p>
                <a href="RecList.php" class="btn btn-primary">Mes Réclamations</a>
            </div>
        </section>

        <section class="reclamation-content">
            <div class="container">
                <div class="section-header">
                    <h2>Modifier une réclamation</h2>
                    <p>Modifiez les informations ci-dessous</p>
                </div>

                <form class="reclamation-form" method="POST">
                    <div class="form-group">
                        <label for="objet">Objet de la réclamation:</label>
                        <select name="objet" id="objet" required>
                            <option value="Retard" <?php if ($reclamation['objet'] == 'Retard')
                                echo 'selected'; ?>>Retard
                            </option>
                            <option value="Annulation" <?php if ($reclamation['objet'] == 'Annulation')
                                echo 'selected'; ?>>Annulation</option>
                            <option value="Dommage" <?php if ($reclamation['objet'] == 'Dommage')
                                echo 'selected'; ?>>
                                Dommage</option>
                            <option value="Qualité de service" <?php if ($reclamation['objet'] == 'Qualité de service')
                                echo 'selected'; ?>>Qualité de service</option>
                            <option value="Facturation" <?php if ($reclamation['objet'] == 'Facturation')
                                echo 'selected'; ?>>Facturation</option>
                            <option value="Autre" <?php if ($reclamation['objet'] == 'Autre')
                                echo 'selected'; ?>>Autre
                            </option>
                        </select>
                    </div>

                    <input type="hidden" name="id_client" id="id_client"
                        value="<?php echo htmlspecialchars($reclamation['id_client']); ?>">

                    <div class="form-group">
                        <label for="date_rec">Date de l'incident:</label>
                        <input type="date" name="date_rec" id="date_rec"
                            value="<?php echo htmlspecialchars($reclamation['date_rec']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="id_covoit">ID Covoiturage:</label>
                        <input type="number" name="id_covoit" id="id_covoit" placeholder="Entrez l'ID du covoiturage"
                            value="<?php echo htmlspecialchars($reclamation['id_covoit']); ?>">
                    </div>

                    <input type="hidden" name="statut" id="statut" value="<?php echo htmlspecialchars($reclamation['statut']); ?>">

                    <div class="form-group">
                        <label for="description">Description détaillée:</label>
                        <textarea name="description" id="description" rows="5" required
                            placeholder="Décrivez votre problème ici..."><?php echo htmlspecialchars($reclamation['description']); ?></textarea>
                    </div>

                    <input type="hidden" name="statut" id="statut" value="En attente">
                    <input type="hidden" name="id_rec" value="<?php echo htmlspecialchars($reclamation['id_rec']); ?>">

                    <div class="form-actions text-center">
                        <a href="RecList.php" class="btn btn-secondary">
                            Annuler
                            <i class="fas fa-times"></i>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Mettre à jour
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </form>
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
</body>

</html>