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

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    // Update user information
    $currentUser->setNom($nom);
    $currentUser->setPrenom($prenom);
    $currentUser->setEmail($email);
    $currentUser->setTelephone($telephone);

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
            $old_image = $currentUser->getImage();
            if ($old_image && $old_image !== 'default.png' && file_exists($upload_dir . $old_image)) {
                unlink($upload_dir . $old_image);
            }

            // Move uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $currentUser->setImage($new_filename);
            } else {
                $error_message = "Erreur lors du téléchargement de l'image.";
            }
        }
    }

    // Handle specific fields for client or employee
    if ($currentUser instanceof Client && isset($_POST['date_naissance'])) {
        $date_naissance = !empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null;
        $currentUser->setDateNaissance($date_naissance);
    } elseif ($currentUser instanceof Employe) {
        // For employees, we don't allow editing role-specific info in the frontend
    }

    // Save changes
    if (empty($error_message)) {
        if ($userController->updateUser($currentUser)) {
            $success_message = "Profil mis à jour avec succès!";
            // Refresh user data
            $currentUser = $userController->showUser($_SESSION['user_id']);
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
    <link rel="stylesheet" href="../../assets/css/frontprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
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
                <header class="profile-header">
                    <h1>Modifier Mon Profil</h1>
                </header>

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
                            <input type="text" id="nom" name="nom"
                                value="<?= htmlspecialchars($currentUser->getNom()) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom"
                                value="<?= htmlspecialchars($currentUser->getPrenom()) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($currentUser->getEmail()) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone"
                            value="<?= htmlspecialchars($currentUser->getTelephone()) ?>">
                    </div>

                    <?php if ($currentUser instanceof Client): ?>
                        <div class="form-group">
                            <label for="date_naissance">Date de Naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance"
                                value="<?= $currentUser->getDateNaissance() ? $currentUser->getDateNaissance()->format('Y-m-d') : '' ?>">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="image">Photo de profil</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="current-image">
                            <img src="../../../Controller/get_image.php?file=<?= urlencode($currentUser->getImage() ?? 'default.png') ?>"
                                alt="Current Profile Picture">
                            <span>Image actuelle</span>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="../index.php" class="btn btn-secondary"><i class="fas fa-home"></i> Retour à
                            l'accueil</a>
                        <a href="view_profile.php" class="btn btn-outline">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include '../../assets/footer.php'; ?>
    <script src="../assets/js/profile.js"></script>
</body>

</html>