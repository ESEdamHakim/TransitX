<?php
require_once __DIR__ . '/../../../Controller/userC.php';
require_once __DIR__ . '/../../../Model/client.php';
require_once __DIR__ . '/../../../Model/employe.php';

// Define the upload directory constant
define('UPLOAD_DIR', __DIR__ . '/../../assets/uploads/profiles/');

$userController = new UserC();

$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header('Location: crud.php');
    exit();
}

$user = $userController->showUser($id);
if (!$user) {
    header('Location: crud.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Server-side validation
    $errors = [];
    
    // Process image upload
    $current_image = $user->getImage() ?? 'default.png';
    $image = $current_image; // Default to current image
    
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filesize = $_FILES['profile_image']['size'];
        
        // Get file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Verify file extension
        if(!in_array(strtolower($ext), $allowed)) {
            $errors['profile_image'] = "Erreur: Veuillez sélectionner un format d'image valide.";
        }
        
        // Verify file size - 5MB maximum
        if($filesize > 5 * 1024 * 1024) {
            $errors['profile_image'] = "Erreur: La taille de l'image ne doit pas dépasser 5MB.";
        }
        
        if(!isset($errors['profile_image'])) {
            // Generate unique filename
            $new_filename = uniqid('user_') . '.' . $ext;
            
            // Create directory if it doesn't exist
            if(!file_exists(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }
            
            // Move the uploaded file
            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], UPLOAD_DIR . $new_filename)) {
                $image = $new_filename;
                
                // Delete old image if not the default
                if($current_image !== 'default.png' && file_exists(UPLOAD_DIR . $current_image)) {
                    unlink(UPLOAD_DIR . $current_image);
                }
            }
        }
    }

    // Validate nom (alphabets only)
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nom'])) {
        $errors['nom'] = "Le nom ne doit contenir que des lettres";
    }

    // Validate prenom (alphabets only)
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['prenom'])) {
        $errors['prenom'] = "Le prénom ne doit contenir que des lettres";
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email doit contenir un @ et être valide";
    }

    // Validate password if provided
    if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
    }

    // Validate telephone (numbers and + only)
    if (!preg_match('/^\+?[0-9]+$/', $_POST['telephone'])) {
        $errors['telephone'] = "Le téléphone ne doit contenir que des chiffres et éventuellement un +";
    }

    if (empty($errors)) {
        $user->setNom($_POST['nom']);
        $user->setPrenom($_POST['prenom']);
        $user->setEmail($_POST['email']);
        $user->setTelephone($_POST['telephone']);
        $user->setImage($image);

        // Handling password change securely
        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
        }

        if ($user instanceof Client) {
            // Validate date format
            if (!empty($_POST['date_naissance']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_naissance'])) {
                $errors['date_naissance'] = "Choisir une date";
            } else {
                $user->setDateNaissance(!empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null);
            }
        } elseif ($user instanceof Employe) {
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_embauche'])) {
                $errors['date_embauche'] = "Choisir une date";
            }

            // Validate poste (alphabets only)
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['poste'])) {
                $errors['poste'] = "Le poste ne doit contenir que des lettres";
            }

            // Validate salaire (numbers only)
            if (!is_numeric($_POST['salaire'])) {
                $errors['salaire'] = "Le salaire doit être un nombre";
            }

            // Validate role (alphabets only)
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['role'])) {
                $errors['role'] = "Le rôle ne doit contenir que des lettres";
            }

            if (empty($errors)) {
                $user->setDateEmbauche(new DateTime($_POST['date_embauche']));
                $user->setPoste($_POST['poste']);
                $user->setSalaire(floatval($_POST['salaire']));
                $user->setRole($_POST['role']);
            }
        }

        if (empty($errors) && $userController->updateUser($user)) {
            header('Location: crud.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>

    <!-- Style Imports -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/users.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .field-required::after {
            content: " *";
            color: red;
        }

        .hidden-field {
            display: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <section>
                <div class="container">
                    <div class="section-header">
                        <h2>Modifier Utilisateur
                            <p>Modifiez les informations ci-dessous</p>
                        </h2>
                    </div>

                    <div class="user-form-container">
                        <form class="user-form" method="post" id="userForm" enctype="multipart/form-data" novalidate>
                            <div class="form-group">
                                <label class="form-label">Type d'utilisateur</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars(ucfirst($user->getType())) ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Nom</label>
                                <input type="text" name="nom"
                                    class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>"
                                    value="<?= isset($errors['nom']) ? '' : htmlspecialchars($user->getNom()) ?>"
                                    pattern="[a-zA-ZÀ-ÿ\s]+" required>
                                <div class="invalid-feedback"><?= $errors['nom'] ?? '' ?></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Prénom</label>
                                <input type="text" name="prenom"
                                    class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>"
                                    value="<?= isset($errors['prenom']) ? '' : htmlspecialchars($user->getPrenom()) ?>"
                                    pattern="[a-zA-ZÀ-ÿ\s]+" required>
                                <div class="invalid-feedback"><?= $errors['prenom'] ?? '' ?></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Email</label>
                                <input type="email" name="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                    value="<?= isset($errors['email']) ? '' : htmlspecialchars($user->getEmail()) ?>"
                                    required>
                                <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                                <input type="password" name="password"
                                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                    minlength="8">
                                <div class="invalid-feedback"><?= $errors['password'] ?? '' ?></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Téléphone</label>
                                <input type="tel" name="telephone"
                                    class="form-control <?= isset($errors['telephone']) ? 'is-invalid' : '' ?>"
                                    value="<?= isset($errors['telephone']) ? '' : htmlspecialchars($user->getTelephone()) ?>"
                                    pattern="^\+?[0-9]+$" required>
                                <div class="invalid-feedback"><?= $errors['telephone'] ?? '' ?></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Photo de profil</label>
                                <?php if($user->getImage()): ?>
                                <div class="current-image-preview mb-2">
                                    <img src="../../../Controller/get_image.php?file=<?= urlencode($user->getImage()) ?>" 
                                         alt="Current profile" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                                    <p class="mt-1">Image actuelle</p>
                                </div>
                                <?php endif; ?>
                                <input type="file" name="profile_image"
                                    class="form-control <?= isset($errors['profile_image']) ? 'is-invalid' : '' ?>"
                                    accept="image/*">
                                <div class="form-text">Formats acceptés: JPG, JPEG, PNG, GIF. Taille max: 5MB</div>
                                <div class="invalid-feedback"><?= $errors['profile_image'] ?? '' ?></div>
                            </div>

                            <?php if ($user instanceof Client): ?>
                                <div class="form-group">
                                    <label class="form-label">Date de Naissance</label>
                                    <input type="date" name="date_naissance"
                                        class="form-control <?= isset($errors['date_naissance']) ? 'is-invalid' : '' ?>"
                                        value="<?= isset($errors['date_naissance']) ? '' : ($user->getDateNaissance() ? $user->getDateNaissance()->format('Y-m-d') : '') ?>">
                                    <div class="invalid-feedback"><?= $errors['date_naissance'] ?? '' ?></div>
                                </div>
                            <?php elseif ($user instanceof Employe): ?>
                                <div class="form-group">
                                    <label class="form-label field-required">Date d'Embauche</label>
                                    <input type="date" name="date_embauche"
                                        class="form-control <?= isset($errors['date_embauche']) ? 'is-invalid' : '' ?>"
                                        value="<?= isset($errors['date_embauche']) ? '' : $user->getDateEmbauche()->format('Y-m-d') ?>"
                                        required>
                                    <div class="invalid-feedback"><?= $errors['date_embauche'] ?? '' ?></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Poste</label>
                                    <input type="text" name="poste"
                                        class="form-control <?= isset($errors['poste']) ? 'is-invalid' : '' ?>"
                                        value="<?= isset($errors['poste']) ? '' : htmlspecialchars($user->getPoste()) ?>"
                                        pattern="[a-zA-ZÀ-ÿ\s]+" required>
                                    <div class="invalid-feedback"><?= $errors['poste'] ?? '' ?></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Salaire</label>
                                    <input type="number" name="salaire"
                                        class="form-control <?= isset($errors['salaire']) ? 'is-invalid' : '' ?>"
                                        value="<?= isset($errors['salaire']) ? '' : htmlspecialchars($user->getSalaire()) ?>"
                                        step="0.01" required>
                                    <div class="invalid-feedback"><?= $errors['salaire'] ?? '' ?></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Rôle</label>
                                    <input type="text" name="role"
                                        class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>"
                                        value="<?= isset($errors['role']) ? '' : htmlspecialchars($user->getRole()) ?>"
                                        pattern="[a-zA-ZÀ-ÿ\s]+" required>
                                    <div class="invalid-feedback"><?= $errors['role'] ?? '' ?></div>
                                </div>
                            <?php endif; ?>

                            <div class="form-actions">
                                <button type="submit" class="btn primary">Enregistrer</button>
                                <a href="crud.php" class="btn secondary">Annuler <i class="fas fa-times"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>