<?php
session_start();
require_once __DIR__ . '/../../../Controller/UserC.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}

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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Mon Profil</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/frontprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../../assets/chatbot/chatbot.css">
    
    <style>
        .profile-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 30px auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-item h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #95a5a6;
        }

        .detail-item p {
            margin: 0;
            font-size: 16px;
            color: #2c3e50;
        }

        .profile-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .profile-actions a {
            margin-left: 10px;
        }
    </style>
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
                    <li><a href="../reclamation/index.php">Réclamation</a></li>
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
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img src="../../../Controller/get_image.php?file=<?= urlencode($currentUser->getImage() ?? 'default.png') ?>"
                            alt="Profile Picture">
                    </div>
                    <div class="profile-info">
                        <h1><?= htmlspecialchars($currentUser->getPrenom() . ' ' . $currentUser->getNom()) ?></h1>
                        <p><?= htmlspecialchars($currentUser->getEmail()) ?></p>
                        <span
                            class="user-type <?= $currentUser->getType() ?>"><?= ucfirst(htmlspecialchars($currentUser->getType())) ?></span>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <h3>Email</h3>
                        <p><?= htmlspecialchars($currentUser->getEmail()) ?></p>
                    </div>
                    <div class="detail-item">
                        <h3>Téléphone</h3>
                        <p><?= htmlspecialchars($currentUser->getTelephone() ?: 'Non renseigné') ?></p>
                    </div>
                    <?php if ($currentUser instanceof Client): ?>
                        <div class="detail-item">
                            <h3>Date de Naissance</h3>
                            <p><?= $currentUser->getDateNaissance() ? $currentUser->getDateNaissance()->format('d/m/Y') : 'Non renseignée' ?>
                            </p>
                        </div>
                    <?php elseif ($currentUser instanceof Employe): ?>
                        <div class="detail-item">
                            <h3>Poste</h3>
                            <p><?= htmlspecialchars($currentUser->getPoste()) ?></p>
                        </div>
                        <div class="detail-item">
                            <h3>Rôle</h3>
                            <p><?= htmlspecialchars($currentUser->getRole()) ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <h3>Date d'inscription</h3>
                        <p><?= $currentUser->getDateInscription()->format('d/m/Y') ?></p>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="../index.php" class="btn btn-secondary"><i class="fas fa-home"></i> Retour à l'accueil</a>
                    <a href="edit_profile.php" class="btn btn-primary"><i class="fas fa-edit"></i> Modifier mon
                        profil</a>
                </div>
            </div>
        </div>
    </main>

    <?php include '../../assets/footer.php'; ?>
    <script src="../assets/js/profile.js"></script>

</body>

</html>