<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require_once __DIR__ . '/../../../Controller/UserC.php';
require_once __DIR__ . '/../../../Model/client.php';
require_once __DIR__ . '/../../../Model/employe.php';

// Initialize variables
$error = '';
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get common user data
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
        $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : null;

        // Basic validation
        if (empty($type) || empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $error = 'Veuillez remplir tous les champs obligatoires';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format d\'email invalide';
        } else {
            // Create appropriate user object based on type
            $userC = new UserC();

            if ($type === 'client') {
                $date_naissance_str = isset($_POST['date_naissance']) ? $_POST['date_naissance'] : '';
                if (empty($date_naissance_str)) {
                    $error = 'Date de naissance requise pour un client';
                } else {
                    try {
                        $date_naissance = new DateTime($date_naissance_str);
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        $user = new Client($nom, $prenom, $email, $hashedPassword, $telephone, $date_naissance);

                        // Insert into database
                        if ($userC->addUser($user)) {
                            $success = true;
                            // Redirect after successful submission
                            header('Location: crud.php?success=1');
                            exit;
                        } else {
                            $error = 'Erreur lors de l\'ajout du client dans la base de données';
                        }
                    } catch (Exception $e) {
                        $error = 'Date de naissance invalide. Format attendu: AAAA-MM-JJ';
                    }
                }
            } elseif ($type === 'employe') {
                $date_embauche_str = isset($_POST['date_embauche']) ? $_POST['date_embauche'] : '';
                $poste = isset($_POST['poste']) ? $_POST['poste'] : '';
                $salaire_str = isset($_POST['salaire']) ? $_POST['salaire'] : '';
                $role = isset($_POST['role']) ? $_POST['role'] : '';

                if (empty($date_embauche_str) || empty($poste) || empty($salaire_str) || empty($role)) {
                    $error = 'Tous les champs sont requis pour un employé';
                } else {
                    try {
                        $date_embauche = new DateTime($date_embauche_str);
                        $salaire = (float) $salaire_str;
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        $user = new Employe($nom, $prenom, $email, $hashedPassword, $date_embauche, $poste, $salaire, $role, $telephone);

                        // Insert into database
                        if ($userC->addUser($user)) {
                            $success = true;
                            // Redirect after successful submission
                            header('Location: crud.php?success=1');
                            exit;
                        } else {
                            $error = 'Erreur lors de l\'ajout de l\'employé dans la base de données';
                        }
                    } catch (Exception $e) {
                        $error = 'Date d\'embauche invalide ou données invalides: ' . $e->getMessage();
                    }
                }
            } else {
                $error = 'Type d\'utilisateur non reconnu';
            }
        }
    } catch (Exception $e) {
        $error = 'Erreur système: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

</head>

<body>
    <div class="dashboard">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <section>
                <div class="container">
                    <div class="section-header">
                        <h2>Ajouter un Utilisateur
                            <p>Remplissez le formulaire ci-dessous</p>
                        </h2>
                    </div>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">Utilisateur ajouté avec succès!</div>
                    <?php endif; ?>
                    <div class="user-form-container">
                        <!-- Form with direct POST to same page -->
                        <form class="user-form" method="post" action="" id="userForm">
                            <!-- User Type Selection -->
                            <div class="form-group">
                                <label class="form-label field-required">Type d'utilisateur</label>
                                <select class="form-select" name="type" id="userType" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="client" <?php echo isset($_POST['type']) && $_POST['type'] === 'client' ? 'selected' : ''; ?>>Client</option>
                                    <option value="employe" <?php echo isset($_POST['type']) && $_POST['type'] === 'employe' ? 'selected' : ''; ?>>Employé</option>
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner un type d'utilisateur</div>
                            </div>

                            <!-- Common fields for both types -->
                            <div class="form-group">
                                <label class="form-label field-required">Nom</label>
                                <input type="text" class="form-control" name="nom" id="nom"
                                    value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
                                <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Prénom</label>
                                <input type="text" class="form-control" name="prenom" id="prenom"
                                    value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>">
                                <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Email</label>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                <div class="invalid-feedback">Veuillez entrer une adresse email valide</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label field-required">Mot de passe</label>
                                <input type="password" class="form-control" name="password" id="password">
                                <div class="invalid-feedback">Le mot de passe doit contenir au moins 8 caractères
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" name="telephone" id="telephone"
                                    value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
                                <div class="invalid-feedback">Seuls les chiffres et le symbole + sont autorisés
                                </div>
                            </div>

                            <!-- Client specific fields -->
                            <div id="clientFields"
                                class="<?php echo (isset($_POST['type']) && $_POST['type'] === 'client' ? '' : 'hidden-field'); ?>">
                                <div class="form-group">
                                    <label class="form-label field-required">Date de Naissance</label>
                                    <input type="date" class="form-control" name="date_naissance" id="date_naissance"
                                        value="<?php echo htmlspecialchars($_POST['date_naissance'] ?? ''); ?>">
                                </div>
                            </div>

                            <!-- Employee specific fields -->
                            <div id="employeeFields"
                                class="<?php echo (isset($_POST['type']) && $_POST['type'] === 'employe' ? '' : 'hidden-field'); ?>">
                                <div class="form-group">
                                    <label class="form-label field-required">Date d'Embauche</label>
                                    <input type="date" class="form-control" name="date_embauche" id="date_embauche"
                                        value="<?php echo htmlspecialchars($_POST['date_embauche'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Poste</label>
                                    <input type="text" class="form-control" name="poste" id="poste"
                                        value="<?php echo htmlspecialchars($_POST['poste'] ?? ''); ?>">
                                    <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Salaire</label>
                                    <input type="text" class="form-control" name="salaire" id="salaire"
                                        value="<?php echo htmlspecialchars($_POST['salaire'] ?? ''); ?>">
                                    <div class="invalid-feedback">Seuls les chiffres sont autorisés</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label field-required">Rôle</label>
                                    <input type="text" class="form-control" name="role" id="role"
                                        value="<?php echo htmlspecialchars($_POST['role'] ?? ''); ?>">
                                    <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions text-center">
                                <a href="crud.php" class="btn secondary">
                                    Annuler
                                    <i class="fas fa-times"></i>
                                </a>
                                <button type="submit" class="btn primary">Ajouter
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>


    <script>
        // Validation patterns
        const patterns = {
            alphabetsOnly: /^[a-zA-ZÀ-ÿ\s'-]+$/,
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            telephone: /^\+?[0-9]+$/,
            date: /^\d{4}-\d{2}-\d{2}$/,
            numbersOnly: /^[0-9]+(?:\.[0-9]+)?$/
        };

        // Toggle fields based on user type
        function toggleFields() {
            const type = document.getElementById('userType').value;

            // Common fields are always visible
            // Client fields
            document.getElementById('clientFields').style.display = type === 'client' ? 'block' : 'none';
            // Employee fields
            document.getElementById('employeeFields').style.display = type === 'employe' ? 'block' : 'none';

            // Set required attribute based on visibility
            document.getElementById('date_naissance').required = type === 'client';
            document.getElementById('date_embauche').required = type === 'employe';
            document.getElementById('poste').required = type === 'employe';
            document.getElementById('salaire').required = type === 'employe';
            document.getElementById('role').required = type === 'employe';
        }

        // Initialize fields visibility on page load
        document.addEventListener('DOMContentLoaded', function () {
            toggleFields();

            // Add change event listener for user type
            document.getElementById('userType').addEventListener('change', toggleFields);
        });

        // [Rest of your validation functions...]
        // Validate field based on pattern
        function validateField(input, pattern, errorMessage) {
            const isValid = pattern.test(input.value);
            const feedback = input.nextElementSibling;

            if (!isValid && input.value) {
                input.classList.add('is-invalid');
                feedback.style.display = 'block';
                return false;
            } else {
                input.classList.remove('is-invalid');
                feedback.style.display = 'none';
                return true;
            }
        }

        // Field validations
        function validateNameFields() {
            const isValidNom = validateField(
                document.getElementById('nom'),
                patterns.alphabetsOnly,
                "Seules les lettres alphabétiques sont autorisées"
            );

            const isValidPrenom = validateField(
                document.getElementById('prenom'),
                patterns.alphabetsOnly,
                "Seules les lettres alphabétiques sont autorisées"
            );

            return isValidNom && isValidPrenom;
        }

        function validateEmail() {
            return validateField(
                document.getElementById('email'),
                patterns.email,
                "Veuillez entrer une adresse email valide"
            );
        }

        function validatePassword() {
            const password = document.getElementById('password');
            const isValid = password.value.length >= 8;
            const feedback = password.nextElementSibling;

            if (!isValid && password.value) {
                password.classList.add('is-invalid');
                feedback.style.display = 'block';
                return false;
            } else {
                password.classList.remove('is-invalid');
                feedback.style.display = 'none';
                return true;
            }
        }

        function validateTelephone() {
            const telephone = document.getElementById('telephone');
            // Telephone is not required, only validate if not empty
            if (telephone.value) {
                return validateField(
                    telephone,
                    patterns.telephone,
                    "Seuls les chiffres et le symbole + sont autorisés"
                );
            }
            return true;
        }

        function validateClientFields() {
            const dateNaissance = document.getElementById('date_naissance');
            // Only validate if field is visible and not empty
            if (dateNaissance.closest('#clientFields').style.display !== 'none' && dateNaissance.value) {
                return validateField(
                    dateNaissance,
                    patterns.date,
                    "Format requis: MM-JJ-AAAA"
                );
            }
            return true;
        }

        function validateEmployeeFields() {
            const type = document.getElementById('userType').value;
            if (type !== 'employe') return true;

            let isValid = true;

            // Date Embauche
            isValid = validateField(
                document.getElementById('date_embauche'),
                patterns.date,
                "Format requis: MM-JJ-AAAA"
            ) && isValid;

            // Poste
            isValid = validateField(
                document.getElementById('poste'),
                patterns.alphabetsOnly,
                "Seules les lettres alphabétiques sont autorisées"
            ) && isValid;

            // Salaire
            isValid = validateField(
                document.getElementById('salaire'),
                patterns.numbersOnly,
                "Seuls les chiffres sont autorisés"
            ) && isValid;

            // Role
            isValid = validateField(
                document.getElementById('role'),
                patterns.alphabetsOnly,
                "Seules les lettres alphabétiques sont autorisées"
            ) && isValid;

            return isValid;
        }

        // Validate User Type selection
        function validateUserType() {
            const userType = document.getElementById('userType');
            const isValid = userType.value !== '';

            if (!isValid) {
                userType.classList.add('is-invalid');
                userType.nextElementSibling.style.display = 'block';
                return false;
            } else {
                userType.classList.remove('is-invalid');
                userType.nextElementSibling.style.display = 'none';
                return true;
            }
        }

        // Form submission handler - ALLOW SERVER-SIDE VALIDATION ONLY
        document.getElementById('userForm').addEventListener('submit', function (e) {
            console.log('Form submission triggered');
            // Allow the form to submit directly to the server without client-side validation
            // The server will handle all validation
            console.log('Submitting form to server...');
            return true;
        });

        // Real-time validation on field blur
        document.getElementById('userType').addEventListener('blur', validateUserType);
        document.querySelectorAll('#nom, #prenom').forEach(field => {
            field.addEventListener('blur', validateNameFields);
        });
        document.getElementById('email').addEventListener('blur', validateEmail);
        document.getElementById('password').addEventListener('blur', validatePassword);
        document.getElementById('telephone').addEventListener('blur', validateTelephone);
        document.getElementById('date_naissance').addEventListener('blur', validateClientFields);

        // Employee fields
        document.querySelectorAll('#poste, #role').forEach(field => {
            field.addEventListener('blur', validateEmployeeFields);
        });
        document.getElementById('salaire').addEventListener('blur', validateEmployeeFields);
        document.getElementById('date_embauche').addEventListener('blur', validateEmployeeFields);
    </script>
</body>

</html>