<?php
require_once __DIR__ .  '/../Controller/userC.php';
require_once __DIR__ .  '/../Model/user.php';
require_once __DIR__ .  '/../Model/client.php';
require_once __DIR__ .  '/../Model/employe.php';

$userController = new UserC();

$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header('Location: ../View/Backoffice/users/crud.php');
    exit();
}

$user = $userController->showUser($id);
if (!$user) {
    header('Location: ../View/Backoffice/users/crud.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Server-side validation
    $errors = [];
    
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

        // Handling password change securely
        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
        }

        if ($user instanceof Client) {
            // Validate date format
            if (!empty($_POST['date_naissance']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_naissance'])) {
                $errors['date_naissance'] = "La date doit être au format YYYY-MM-DD";
            } else {
                $user->setDateNaissance(!empty($_POST['date_naissance']) ? new DateTime($_POST['date_naissance']) : null);
            }
        } elseif ($user instanceof Employe) {
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_embauche'])) {
                $errors['date_embauche'] = "La date doit être au format YYYY-MM-DD";
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
            header('Location: ../View/Backoffice/users/crud.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .field-required::after {
            content: " *";
            color: red;
        }
        .hidden-field {
            display: none;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .invalid-feedback {
            color: #dc3545;
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Modifier Utilisateur</h1>
        
        <form method="post" id="userForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Type d'utilisateur</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($user->getType())) ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Nom</label>
                <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                       name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" 
                       pattern="[a-zA-ZÀ-ÿ\s]+" title="Seules les lettres sont autorisées" required>
                <div class="invalid-feedback"><?= $errors['nom'] ?? 'Seules les lettres sont autorisées' ?></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Prénom</label>
                <input type="text" class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>" 
                       name="prenom" value="<?= htmlspecialchars($user->getPrenom()) ?>" 
                       pattern="[a-zA-ZÀ-ÿ\s]+" title="Seules les lettres sont autorisées" required>
                <div class="invalid-feedback"><?= $errors['prenom'] ?? 'Seules les lettres sont autorisées' ?></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Email</label>
                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?? 'Veuillez entrer une adresse email valide' ?></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                       name="password" minlength="8">
                <div class="invalid-feedback"><?= $errors['password'] ?? 'Le mot de passe doit contenir au moins 8 caractères' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label field-required">Téléphone</label>
                <input type="tel" class="form-control <?= isset($errors['telephone']) ? 'is-invalid' : '' ?>" 
                       name="telephone" value="<?= htmlspecialchars($user->getTelephone()) ?>" 
                       pattern="^\+?[0-9]+$" title="Seuls les chiffres et le symbole + sont autorisés" required>
                <div class="invalid-feedback"><?= $errors['telephone'] ?? 'Seuls les chiffres et le symbole + sont autorisés' ?></div>
            </div>
            
            <?php if ($user instanceof Client): ?>
                <div class="mb-3">
                    <label class="form-label">Date de Naissance (YYYY-MM-DD)</label>
                    <input type="date" class="form-control <?= isset($errors['date_naissance']) ? 'is-invalid' : '' ?>" 
                           name="date_naissance" 
                           value="<?= $user->getDateNaissance() ? $user->getDateNaissance()->format('Y-m-d') : '' ?>"
                           pattern="\d{4}-\d{2}-\d{2}">
                    <div class="invalid-feedback"><?= $errors['date_naissance'] ?? 'Le format doit être YYYY-MM-DD' ?></div>
                </div>
            <?php elseif ($user instanceof Employe): ?>
                <div class="mb-3">
                    <label class="form-label field-required">Date d'Embauche (YYYY-MM-DD)</label>
                    <input type="date" class="form-control <?= isset($errors['date_embauche']) ? 'is-invalid' : '' ?>" 
                           name="date_embauche" 
                           value="<?= $user->getDateEmbauche()->format('Y-m-d') ?>"
                           pattern="\d{4}-\d{2}-\d{2}" required>
                    <div class="invalid-feedback"><?= $errors['date_embauche'] ?? 'Le format doit être YYYY-MM-DD' ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Poste</label>
                    <input type="text" class="form-control <?= isset($errors['poste']) ? 'is-invalid' : '' ?>" 
                           name="poste" value="<?= htmlspecialchars($user->getPoste()) ?>" 
                           pattern="[a-zA-ZÀ-ÿ\s]+" title="Seules les lettres sont autorisées" required>
                    <div class="invalid-feedback"><?= $errors['poste'] ?? 'Seules les lettres sont autorisées' ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Salaire</label>
                    <input type="number" class="form-control <?= isset($errors['salaire']) ? 'is-invalid' : '' ?>" 
                           name="salaire" step="0.01" 
                           value="<?= htmlspecialchars($user->getSalaire()) ?>" required>
                    <div class="invalid-feedback"><?= $errors['salaire'] ?? 'Veuillez entrer un nombre valide' ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Rôle</label>
                    <input type="text" class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>" 
                           name="role" value="<?= htmlspecialchars($user->getRole()) ?>" 
                           pattern="[a-zA-ZÀ-ÿ\s]+" title="Seules les lettres sont autorisées" required>
                    <div class="invalid-feedback"><?= $errors['role'] ?? 'Seules les lettres sont autorisées' ?></div>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="../View/Backoffice/users/crud.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Enable Bootstrap validation
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();

            // Real-time validation
            $('input').on('input', function() {
                validateField($(this));
            });

            function validateField(field) {
                const value = field.val();
                const name = field.attr('name');
                const feedback = field.next('.invalid-feedback');
                
                // Reset state
                field.removeClass('is-invalid');
                feedback.hide();
                
                if (field.prop('required') && !value.trim()) {
                    showError(field, 'Ce champ est obligatoire');
                    return false;
                }
                
                // Field-specific validation
                switch(name) {
                    case 'nom':
                    case 'prenom':
                    case 'poste':
                    case 'role':
                        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value)) {
                            showError(field, 'Seules les lettres sont autorisées');
                            return false;
                        }
                        break;
                        
                    case 'email':
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                            showError(field, 'Veuillez entrer une adresse email valide');
                            return false;
                        }
                        break;
                        
                    case 'password':
                        if (value && value.length < 8) {
                            showError(field, 'Le mot de passe doit contenir au moins 8 caractères');
                            return false;
                        }
                        break;
                        
                    case 'telephone':
                        if (!/^\+?[0-9]+$/.test(value)) {
                            showError(field, 'Seuls les chiffres et le symbole + sont autorisés');
                            return false;
                        }
                        break;
                        
                    case 'date_naissance':
                    case 'date_embauche':
                        if (value && !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                            showError(field, 'Le format doit être YYYY-MM-DD');
                            return false;
                        }
                        break;
                        
                    case 'salaire':
                        if (isNaN(value) || parseFloat(value) <= 0) {
                            showError(field, 'Veuillez entrer un nombre valide');
                            return false;
                        }
                        break;
                }
                
                return true;
            }
            
            function showError(field, message) {
                const feedback = field.next('.invalid-feedback');
                field.addClass('is-invalid');
                feedback.text(message).show();
            }
            
            $('#userForm').submit(function(e) {
                let isValid = true;
                
                // Validate all fields
                $('input').each(function() {
                    if (!validateField($(this))) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                }
            });
        });
    </script>
</body>
</html>