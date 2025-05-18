<?php
session_start();
require_once __DIR__ . '/../../../Controller/UserC.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit();
}

$userController = new UserC();
$profileUser = $userController->showUser($_SESSION['user_id']);

if (!$profileUser) {
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
    <link rel="stylesheet" href="../../assets/css/main.css">
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
        
        /* User profile dropdown styles */
        .user-profile-dropdown {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }
        
        .user-profile-dropdown .profile-toggle {
            display: flex;
            align-items: center;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 50px;
            padding: 5px 15px 5px 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-profile-dropdown .profile-toggle:hover {
            background-color: #f8f9fa;
        }
        
        .user-profile-dropdown .profile-pic {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }
        
        .user-profile-dropdown .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 45px;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-profile-dropdown .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.2s;
        }
        
        .user-profile-dropdown .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        
        .user-profile-dropdown .dropdown-content a i {
            margin-right: 10px;
            color: #4a6cf7;
            width: 20px;
            text-align: center;
        }
        
        .user-profile-dropdown .dropdown-content a:not(:last-child) {
            border-bottom: 1px solid #eee;
        }
        
        .user-profile-dropdown.show .dropdown-content {
            display: block;
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
                <div class="user-profile-dropdown" id="userProfileDropdown">
                    <div class="profile-toggle" id="profileToggle">
                        <img src="../../../Controller/get_image.php?file=<?= urlencode($profileUser->getImage() ?? 'default.png') ?>" alt="Profile" class="profile-pic">
                        <span><?= htmlspecialchars($profileUser->getPrenom()) ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="view_profile.php"><i class="fas fa-user"></i> Mon Profil</a>
                        <a href="edit_profile.php"><i class="fas fa-edit"></i> Modifier Profil</a>
                        <a href="../../../index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    </div>
                </div>
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'client'): ?>
                    <a href="../../BackOffice/index.php" class="btn btn-outline dashboard-btn">Dashboard</a>
                <?php endif; ?>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
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
                    <?php endif; ?>
                    <div class="detail-item">
                        <h3>Date d'inscription</h3>
                        <p><?= $profileUser->getDateInscription()->format('d/m/Y') ?></p>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="../index.php" class="btn btn-secondary"><i class="fas fa-home"></i> Retour à l'accueil</a>
                    <a href="edit_profile.php" class="btn btn-primary"><i class="fas fa-edit"></i> Modifier mon profil</a>
                </div>
            </div>
        </div>
    </main>

    <?php include '../../assets/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown functionality
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');
            const userProfileDropdown = document.getElementById('userProfileDropdown');
            
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userProfileDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userProfileDropdown.contains(e.target)) {
                        userProfileDropdown.classList.remove('show');
                    }
                });
            }
            
            // Mobile menu toggle
            document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
                document.querySelector('.main-nav').classList.toggle('active');
            });
        });
    </script>
</body>

</html>
