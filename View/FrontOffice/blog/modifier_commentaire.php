<?php
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

session_start();

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = ?");
$stmt->execute([$id]);
$commentaire = $stmt->fetch();

if (!$commentaire) {
    echo "Commentaire introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier le Commentaire</title>
    <!-- Feuilles de style -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">

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
                    <li class="active"><a href="index.php">Blog</a></li>
                    <li><a href="../reclamation/index.php">Réclamation</a></li>
                    <li><a href="../vehicule/index.php">Véhicule</a></li>

                </ul>
            </nav>
            <div class="header-right">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
                    <a href="../../BackOffice/index.php" class="btn secondary ">Dashboard</a>
                <?php endif; ?>
                <a href="../../../index.php" class="btn primary">Déconnexion</a>
                <a href="calendrier.php" class="calen-button">
                    <i class="fas fa-calendar-alt" style="color: #86b391;"></i>
                </a>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    <main>
        <section class="blog-hero">
            <div class="hero-content">
                <h1>Modifier un Commentaire</h1>
                <p> Restez informé avec les dernières nouvelles, idées et tendances sur la mobilité urbaine durable.
                    Explorez nos articles pour découvrir l'avenir des transports.</p>
            </div>
        </section>
        <div class="container">
            <form class="blog-form" method="POST" action="traiter_modif_commentaire.php">
                <input type="hidden" name="id_commentaire" value="<?php echo $commentaire['id_commentaire']; ?>">
                <input type="hidden" name="id_article" value="<?php echo $commentaire['id_article']; ?>">

                <div class="form-group">
                    <label for="contenu_commentaire">Votre commentaire </label>
                    <textarea name="contenu_commentaire" id="contenu_commentaire" rows="6"
                        required><?php echo htmlspecialchars($commentaire['contenu_commentaire']); ?></textarea>
                </div>
                <br>
                <div class="form-actions text-center">
                    <a href="index.php" class="btn secondary">
                        Annuler
                        <i class="fas fa-times"></i>
                    </a>
                    <button class='btn primary' type="submit">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
            <br>
        </div>
    </main>

    <?php include '../../assets/footer.php'; ?>

</body>

</html>