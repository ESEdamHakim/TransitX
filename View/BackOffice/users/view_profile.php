<?php
require_once __DIR__ . '/../../../Controller/UserC.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}

$userController = new UserC();

// Check if viewing a specific user profile
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the user information based on the ID parameter
    $profileUser = $userController->showUser($_GET['id']);
    $viewingOtherProfile = true;
} else {
    // Get the logged-in user's profile
    $profileUser = $userController->showUser($_SESSION['user_id']);
    $viewingOtherProfile = false;
}

// Get the logged-in user's information for the header
$currentUser = $userController->showUser($_SESSION['user_id']);

if (!$profileUser || !$currentUser) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Mon Profil</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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

        .profile-info h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .profile-info p {
            margin: 5px 0;
            color: #7f8c8d;
            font-size: 16px;
        }

        .profile-info .user-type {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            color: #fff;
            margin-top: 10px;
        }

        .profile-info .user-type.client {
            background-color: #3498db;
        }

        .profile-info .user-type.employe {
            background-color: #2ecc71;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
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
        
        /* Header button styles */
        .header-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1><?= $viewingOtherProfile ? 'Profil Utilisateur' : 'Mon Profil' ?></h1>
                    <p><?= $viewingOtherProfile ? 'Consultez les informations de l\'utilisateur' : 'Consultez vos informations personnelles' ?></p>
                </div>
                <div class="header-right">
                    <div class="header-buttons">
                        <a href="crud.php" class="btn secondary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-image">
                            <img src="../../../Controller/get_image.php?file=<?= urlencode($profileUser->getImage() ?? 'default.png') ?>" alt="Profile Picture">
                        </div>
                        <div class="profile-info">
                            <h1><?= htmlspecialchars($profileUser->getPrenom() . ' ' . $profileUser->getNom()) ?></h1>
                            <p><?= htmlspecialchars($profileUser->getEmail()) ?></p>
                            <span class="user-type <?= $profileUser->getType() ?>"><?= ucfirst(htmlspecialchars($profileUser->getType())) ?></span>
                        </div>
                    </div>

                    <div class="profile-details">
                        <div class="detail-item">
                            <h3>Email</h3>
                            <p><?= htmlspecialchars($profileUser->getEmail()) ?></p>
                        </div>
                        <div class="detail-item">
                            <h3>Téléphone</h3>
                            <p><?= htmlspecialchars($profileUser->getTelephone() ?: 'Non renseigné') ?></p>
                        </div>
                        <?php if ($profileUser instanceof Client): ?>
                            <div class="detail-item">
                                <h3>Date de Naissance</h3>
                                <p><?= $profileUser->getDateNaissance() ? $profileUser->getDateNaissance()->format('d/m/Y') : 'Non renseignée' ?></p>
                            </div>
                        <?php elseif ($profileUser instanceof Employe): ?>
                            <div class="detail-item">
                                <h3>Poste</h3>
                                <p><?= htmlspecialchars($profileUser->getPoste()) ?></p>
                            </div>
                            <div class="detail-item">
                                <h3>Rôle</h3>
                                <p><?= htmlspecialchars($profileUser->getRole()) ?></p>
                            </div>
                            <div class="detail-item">
                                <h3>Date d'embauche</h3>
                                <p><?= $profileUser->getDateEmbauche()->format('d/m/Y') ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <h3>Date d'inscription</h3>
                            <p><?= $profileUser->getDateInscription()->format('d/m/Y') ?></p>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <?php if (!$viewingOtherProfile): ?>
                            <!-- Show edit button only for own profile -->
                            <a href="edit_profile.php" class="btn primary"><i class="fas fa-edit"></i> Modifier</a>
                        <?php else: ?>
                            <!-- Show different options when viewing another user's profile -->
                            <a href="crud.php" class="btn secondary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                            <a href="edit_user.php?id=<?= $profileUser->getId() ?>" class="btn primary"><i class="fas fa-edit"></i> Modifier</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>
