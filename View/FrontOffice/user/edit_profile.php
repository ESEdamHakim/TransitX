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

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    
    // Update user information
    $profileUser->setNom($nom);
    $profileUser->setPrenom($prenom);
    $profileUser->setEmail($email);
    $profileUser->setTelephone($telephone);
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../assets/uploads/profiles/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('profile_') . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            $error_message = "Type de fichier non autorisé. Veuillez télécharger une image JPG, JPEG, PNG ou GIF.";
        } else if ($_FILES['image']['size'] > 5000000) { // 5MB limit
            $error_message = "L'image est trop volumineuse. La taille maximale est de 5 MB.";
        } else {
            // Delete old image if it exists and is not the default
            $old_image = $profileUser->getImage();
            if ($old_image && $old_image !== 'default.png' && file_exists($upload_dir . $old_image)) {
                unlink($upload_dir . $old_image);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $profileUser->setImage($new_filename);
            } else {
                $error_message = "Erreur lors du téléchargement de l'image.";
            }
        }
    }
    
    // Handle specific fields for client or employee
    if ($profileUser instanceof Client && isset($_POST['date_naissance'])) {
        $date_naissance = !empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null;
        $profileUser->setDateNaissance($date_naissance);
    } elseif ($profileUser instanceof Employe) {
        // For employees, we don't allow editing role-specific info in the frontend
    }
    
    // Save changes
    if (empty($error_message)) {
        if ($userController->updateUser($profileUser)) {
            $success_message = "Profil mis à jour avec succès!";
            // Refresh user data
            $profileUser = $userController->showUser($_SESSION['user_id']);
        } else {
            $error_message = "Une erreur s'est produite lors de la mise à jour du profil.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitX - Modifier Mon Profil</title>
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
        
        .profile-form {
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .profile-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .current-image {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }
        
        .current-image img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid #ddd;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                <h1>Modifier Mon Profil</h1>
                
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?= $success_message ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?= $error_message ?>
                    </div>
                <?php endif; ?>
                
                <form class="profile-form" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($profileUser->getNom()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($profileUser->getPrenom()) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($profileUser->getEmail()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($profileUser->getTelephone()) ?>">
                    </div>
                    
                    <?php if ($profileUser instanceof Client): ?>
                        <div class="form-group">
                            <label for="date_naissance">Date de Naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="<?= $profileUser->getDateNaissance() ? $profileUser->getDateNaissance()->format('Y-m-d') : '' ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="image">Photo de profil</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="current-image">
                            <img src="../../../Controller/get_image.php?file=<?= urlencode($profileUser->getImage() ?? 'default.png') ?>" alt="Current Profile Picture">
                            <span>Image actuelle</span>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <a href="../index.php" class="btn btn-secondary"><i class="fas fa-home"></i> Retour à l'accueil</a>
                        <a href="view_profile.php" class="btn btn-outline">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
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
