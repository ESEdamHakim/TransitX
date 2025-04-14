<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="mb-4">Ajouter un Utilisateur</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="post" id="userForm">
            <!-- User Type Selection -->
            <div class="mb-3">
                <label class="form-label field-required">Type d'utilisateur</label>
                <select class="form-select" name="type" id="userType" required>
                    <option value="">Sélectionner un type</option>
                    <option value="client" <?php echo isset($_POST['type']) && $_POST['type'] === 'client' ? 'selected' : ''; ?>>Client</option>
                    <option value="employe" <?php echo isset($_POST['type']) && $_POST['type'] === 'employe' ? 'selected' : ''; ?>>Employé</option>
                </select>
                <div class="invalid-feedback">Veuillez sélectionner un type d'utilisateur</div>
            </div>
            
            <!-- Common fields for both types -->
            <div class="mb-3">
                <label class="form-label field-required">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" 
                       value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
                <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Prénom</label>
                <input type="text" class="form-control" name="prenom" id="prenom" 
                       value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>">
                <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Email</label>
                <input type="text" class="form-control" name="email" id="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <div class="invalid-feedback">Veuillez entrer une adresse email valide</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label field-required">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password">
                <div class="invalid-feedback">Le mot de passe doit contenir au moins 8 caractères</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone" id="telephone" 
                       value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
                <div class="invalid-feedback">Seuls les chiffres et le symbole + sont autorisés</div>
            </div>
            
            <!-- Client specific fields -->
            <div id="clientFields" class="<?php echo (isset($_POST['type']) && $_POST['type'] === 'client' ? '' : 'hidden-field'); ?>">
                <div class="mb-3">
                    <label class="form-label field-required">Date de Naissance</label>
                    <input type="text" class="form-control" name="date_naissance" id="date_naissance" 
                           placeholder="AAAA-MM-JJ" value="<?php echo htmlspecialchars($_POST['date_naissance'] ?? ''); ?>">
                    <div class="invalid-feedback">Format requis: AAAA-MM-JJ</div>
                </div>
            </div>
            
            <!-- Employee specific fields -->
            <div id="employeeFields" class="<?php echo (isset($_POST['type']) && $_POST['type'] === 'employe' ? '' : 'hidden-field'); ?>">
                <div class="mb-3">
                    <label class="form-label field-required">Date d'Embauche</label>
                    <input type="text" class="form-control" name="date_embauche" id="date_embauche" 
                           placeholder="AAAA-MM-JJ" value="<?php echo htmlspecialchars($_POST['date_embauche'] ?? ''); ?>">
                    <div class="invalid-feedback">Format requis: AAAA-MM-JJ</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Poste</label>
                    <input type="text" class="form-control" name="poste" id="poste" 
                           value="<?php echo htmlspecialchars($_POST['poste'] ?? ''); ?>">
                    <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Salaire</label>
                    <input type="text" class="form-control" name="salaire" id="salaire" 
                           value="<?php echo htmlspecialchars($_POST['salaire'] ?? ''); ?>">
                    <div class="invalid-feedback">Seuls les chiffres sont autorisés</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label field-required">Rôle</label>
                    <input type="text" class="form-control" name="role" id="role" 
                           value="<?php echo htmlspecialchars($_POST['role'] ?? ''); ?>">
                    <div class="invalid-feedback">Seules les lettres alphabétiques sont autorisées</div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="../View/Backoffice/users/crud.php" class="btn btn-secondary">Annuler</a>
        </form>
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
        document.addEventListener('DOMContentLoaded', function() {
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
                    "Format requis: AAAA-MM-JJ"
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
                "Format requis: AAAA-MM-JJ"
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

        // Form submission handler
        document.getElementById('userForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            const requiredFields = document.querySelectorAll('.field-required');
            requiredFields.forEach(function(label) {
                const input = label.nextElementSibling || label.parentElement.nextElementSibling;
                if (input && input.value.trim() === '') {
                    isValid = false;
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback) {
                        feedback.textContent = "Ce champ est obligatoire";
                        feedback.style.display = 'block';
                    }
                }
            });
            
            // Validate field formats
            isValid = validateUserType() && isValid;
            isValid = validateNameFields() && isValid;
            isValid = validateEmail() && isValid;
            isValid = validatePassword() && isValid;
            isValid = validateTelephone() && isValid;
            
            // Validate type-specific fields
            const type = document.getElementById('userType').value;
            if (type === 'client') {
                isValid = validateClientFields() && isValid;
            } else if (type === 'employe') {
                isValid = validateEmployeeFields() && isValid;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first invalid field
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
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