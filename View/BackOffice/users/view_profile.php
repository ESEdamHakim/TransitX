<?php
require_once __DIR__ . '/../../../Controller/UserC.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}

$isModal = isset($_GET['modal']); // Check if modal mode

$userController = new UserC();

// Determine which profile to view
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $profileUser = $userController->showUser($_GET['id']);
    $viewingOtherProfile = true;
} else {
    $profileUser = $userController->showUser($_SESSION['user_id']);
    $viewingOtherProfile = false;
}

$currentUser = $userController->showUser($_SESSION['user_id']);

if (!$profileUser || !$currentUser) {
    header('Location: ../index.php');
    exit();
}
?>

<?php if (!$isModal): ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TransitX - Mon Profil</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
        <link rel="stylesheet" href="assets/css/users.css">
        <link rel="stylesheet" href="../../assets/css/profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="dashboard">
            <main class="main-content">
                <header class="dashboard-header">
                    <div class="header-left">
                        <h1><?= $viewingOtherProfile ? 'Profil Utilisateur' : 'Mon Profil' ?></h1>
                        <p><?= $viewingOtherProfile ? 'Consultez les informations de l\'utilisateur' : 'Consultez vos informations personnelles' ?>
                        </p>
                    </div>
                    <div class="header-right">
                        <div class="header-buttons">
                            <a href="crud.php" class="btn secondary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                        </div>
                    </div>
                </header>
                <div class="dashboard-content">
                <?php endif; ?>

                <!-- Start Profile Component (works both in modal and full page) -->

                <?php if ($isModal): ?>
                    <button class="view-profile-modal-close-btn"
                        onclick="document.querySelector('.view-profile-modal').style.display='none'">&times;</button>
                <?php endif; ?>

                <div class="profile-header">
                    <div class="profile-image">
                        <img src="../../../Controller/get_image.php?file=<?= urlencode($profileUser->getImage() ?? 'default.png') ?>"
                            alt="Profile Picture">
                    </div>
                    <div class="profile-info">
                        <h1><?= htmlspecialchars($profileUser->getPrenom() . ' ' . $profileUser->getNom()) ?></h1>
                        <p><?= htmlspecialchars($profileUser->getEmail()) ?></p>
                        <span
                            class="user-type <?= $profileUser->getType() ?>"><?= ucfirst(htmlspecialchars($profileUser->getType())) ?></span>
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
                            <p><?= $profileUser->getDateNaissance() ? $profileUser->getDateNaissance()->format('d/m/Y') : 'Non renseignée' ?>
                            </p>
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
                        <a href="edit_profile.php" class="btn primary"><i class="fas fa-edit"></i> Modifier</a>
                    <?php else: ?>
                        <a href="edit_user.php?id=<?= $profileUser->getId() ?>" class="btn primary"><i
                                class="fas fa-edit"></i> Modifier</a>
                    <?php endif; ?>
                </div>
                <!-- End Profile Component -->

                <?php if (!$isModal): ?>
                </div>
            </main>
        </div>
    </body>

    </html>
<?php endif; ?>