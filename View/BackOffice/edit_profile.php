<?php
require_once __DIR__ . '/../../Controller/UserC.php';
require_once __DIR__ . '/../../Model/client.php';
require_once __DIR__ . '/../../Model/employe.php';

define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/profiles/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

$isModal = isset($_GET['modal']); // Check if modal mode

$userController = new UserC();
$user = $userController->showUser($_SESSION['user_id']);

if (!$user) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_image = $user->getImage() ?? 'default.png';
    $image = $current_image;

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filesize = $_FILES['profile_image']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $allowed)) {
            $errors['profile_image'] = "Erreur: Veuillez sélectionner un format d'image valide.";
        }

        if ($filesize > 5 * 1024 * 1024) {
            $errors['profile_image'] = "Erreur: La taille de l'image ne doit pas dépasser 5MB.";
        }

        if (empty($errors)) {
            $new_filename = uniqid('user_') . '.' . $ext;
            if (!file_exists(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], UPLOAD_DIR . $new_filename)) {
                $image = $new_filename;
                if ($current_image !== 'default.png' && file_exists(UPLOAD_DIR . $current_image)) {
                    unlink(UPLOAD_DIR . $current_image);
                }
            }
        }
    }

    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nom'])) {
        $errors['nom'] = "Le nom ne doit contenir que des lettres";
    }

    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['prenom'])) {
        $errors['prenom'] = "Le prénom ne doit contenir que des lettres";
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email doit contenir un @ et être valide";
    }

    if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
    }

    if (!preg_match('/^\+?[0-9]+$/', $_POST['telephone'])) {
        $errors['telephone'] = "Le téléphone ne doit contenir que des chiffres et éventuellement un +";
    }

    if (empty($errors)) {
        $user->setNom($_POST['nom']);
        $user->setPrenom($_POST['prenom']);
        $user->setEmail($_POST['email']);
        $user->setTelephone($_POST['telephone']);
        $user->setImage($image);

        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
        }

        if ($user instanceof Client) {
            if (!empty($_POST['date_naissance']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_naissance'])) {
                $errors['date_naissance'] = "Choisir une date";
            } else {
                $user->setDateNaissance(!empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null);
            }
        }

        if (empty($errors) && $userController->updateUser($user)) {
            $success = true;
        }
    }
}
?>

<?php if (!$isModal): ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TransitX - Modifier Mon Profil</title>
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel="stylesheet" href="../assets/css/profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
        <style>
            .field-required::after {
                content: " *";
                color: red;
            }

            .form-control.is-invalid {
                border-color: #dc3545;
            }
        </style>
    </head>

    <body>
        <div class="dashboard">
            <main class="main-content">
                <header class="dashboard-header">
                    <div class="header-left">
                        <h1>Modifier Mon Profil</h1>
                        <p>Mettre à jour vos informations personnelles</p>
                    </div>
                    <div class="header-right">
                        <div class="header-buttons">
                            <a href="profile.php" class="btn secondary"><i class="fas fa-arrow-left"></i> Retour</a>
                        </div>
                    </div>
                </header>
                <div class="dashboard-content">
                <?php endif; ?>

                <?php // Do NOT output the close button or container in modal mode ?>

                <?php if ($success): ?>
                    <div class="alert alert-success profile-update-success">Profil mis à jour avec succès.</div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="profile-form">
                    <div class="profile-header">
                        <div class="profile-image">
                            <img src="../../Controller/get_image.php?file=<?= urlencode($user->getImage() ?? 'default.png') ?>"
                                alt="Profile Picture">
                            <input type="file" name="profile_image" accept="image/*">
                            <?php if (isset($errors['profile_image'])): ?>
                                <small class="text-danger"><?= $errors['profile_image'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <input type="text" name="prenom" placeholder="Prénom"
                                value="<?= htmlspecialchars($user->getPrenom()) ?>"
                                class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['prenom'])): ?><small
                                    class="text-danger"><?= $errors['prenom'] ?></small><?php endif; ?>

                            <input type="text" name="nom" placeholder="Nom"
                                value="<?= htmlspecialchars($user->getNom()) ?>"
                                class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['nom'])): ?><small
                                    class="text-danger"><?= $errors['nom'] ?></small><?php endif; ?>

                            <input type="email" name="email" placeholder="Email"
                                value="<?= htmlspecialchars($user->getEmail()) ?>"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['email'])): ?><small
                                    class="text-danger"><?= $errors['email'] ?></small><?php endif; ?>

                            <input type="text" name="telephone" placeholder="Téléphone"
                                value="<?= htmlspecialchars($user->getTelephone()) ?>"
                                class="form-control <?= isset($errors['telephone']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['telephone'])): ?><small
                                    class="text-danger"><?= $errors['telephone'] ?></small><?php endif; ?>

                            <input type="password" name="password"
                                placeholder="Nouveau mot de passe (laisser vide si inchangé)"
                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['password'])): ?><small
                                    class="text-danger"><?= $errors['password'] ?></small><?php endif; ?>
                        </div>
                    </div>

                    <?php if ($user instanceof Client): ?>
                        <div class="detail-item">
                            <label for="date_naissance">Date de Naissance</label>
                            <input type="date" name="date_naissance"
                                value="<?= $user->getDateNaissance() ? $user->getDateNaissance()->format('Y-m-d') : '' ?>"
                                class="form-control <?= isset($errors['date_naissance']) ? 'is-invalid' : '' ?>">
                            <?php if (isset($errors['date_naissance'])): ?><small
                                    class="text-danger"><?= $errors['date_naissance'] ?></small><?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="profile-actions">
                        <button type="submit" class="btn primary"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </form>
                <!-- End Edit Form -->

                <?php if (!$isModal): ?>
                </div>
            </main>
        </div>
        <script src="assets/js/profileManage.js"></script>
    </body>

    </html>
<?php endif; ?>