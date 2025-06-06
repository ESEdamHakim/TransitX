<?php
require_once '../../../Controller/ReclamationController.php';
require_once __DIR__ . '/../../../Controller/userC.php';

session_start(); // Important : Démarrer la session en haut du fichier

$userController = new UserC();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// For testing - use the first user from the list instead of session user
// Comment this out once testing is complete
$currentUser = null;
$currentUser = null;

if (isset($_SESSION['user_id'])) {
  $currentUser = $userController->showUser($_SESSION['user_id']);
}

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

// Fetch clients and covoiturages for dropdowns
$covoiturages = $ReclamationC->getAllCovoiturages();

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
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
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
                <div class="actions">
                    <div class="actions-container">
                        <?php include '../assets/php/profile.php'; ?>
                    </div>
                    <button class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
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
                        <select name="objet" id="objet">
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
                        <label for="id_covoit">Covoiturage</label>
                        <select name="id_covoit" id="id_covoit">
                            <option value="">-- Sélectionner un covoiturage --</option>
                            <?php
                            foreach ($covoiturages as $covoit) {
                                $selected = ($covoit['id_covoit'] == $reclamation['id_covoit']) ? 'selected' : '';
                                echo "<option value='{$covoit['id_covoit']}' $selected>{$covoit['lieu_depart']} → {$covoit['lieu_arrivee']} (ID: {$covoit['id_covoit']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="statut" id="statut"
                        value="<?php echo htmlspecialchars($reclamation['statut']); ?>">

                    <div class="form-group">
                        <label for="description">Description détaillée:</label>
                        <textarea name="description" id="description" rows="5"
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
                        <a href="tel:+33123456789">+216 26 216 216</a>
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

    <?php include '../../assets/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
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
        });
    </script>

    <script src="assets/js/recValidation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/chatbot.js"> </script>
    <script src="../assets/js/profile.js"></script>
</body>

</html>