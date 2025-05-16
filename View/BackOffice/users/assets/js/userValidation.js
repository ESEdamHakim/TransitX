document.addEventListener('DOMContentLoaded', function () {
  const userForms = document.querySelectorAll(".user-form");

  const patterns = {
    alphabetsOnly: /^[a-zA-ZÀ-ÿ\s'-]+$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    telephone: /^\+?[0-9]+$/,
    date: /^\d{4}-\d{2}-\d{2}$/,
    numbersOnly: /^[0-9]+(?:\.[0-9]+)?$/
  };

  function toggleFields() {
    const type = document.getElementById('userType').value;

    document.getElementById('clientFields').style.display = type === 'client' ? 'block' : 'none';
    document.getElementById('employeeFields').style.display = type === 'employe' ? 'block' : 'none';

    document.getElementById('date_naissance').required = type === 'client';
    document.getElementById('date_embauche').required = type === 'employe';
    document.getElementById('poste').required = type === 'employe';
    document.getElementById('salaire').required = type === 'employe';
    document.getElementById('role').required = type === 'employe';
  }

  toggleFields();
  document.getElementById('userType').addEventListener('change', toggleFields);

  function clearAllErrors() {
  document.querySelectorAll('.is-invalid').forEach(field => {
    field.classList.remove('is-invalid');
  });
  document.querySelectorAll('.invalid-feedback').forEach(feedback => {
    feedback.style.display = 'none';
    feedback.textContent = '';
  });
}

  function validateField(input, pattern, errorMessage) {
    const isValid = pattern.test(input.value.trim());
    let feedback = input.nextElementSibling;

    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
      // Create feedback if it doesn't exist
      feedback = document.createElement('div');
      feedback.classList.add('invalid-feedback');
      input.parentNode.appendChild(feedback);
    }

    if (!isValid && input.value.trim() !== '') {
      input.classList.add('is-invalid');
      feedback.textContent = errorMessage;
      feedback.style.display = 'block';
      return false;
    } else {
      input.classList.remove('is-invalid');
      feedback.style.display = 'none';
      return true;
    }
  }

  function validateNameFields() {
    return validateField(document.getElementById('nom'), patterns.alphabetsOnly, "Seules les lettres sont autorisées") &
           validateField(document.getElementById('prenom'), patterns.alphabetsOnly, "Seules les lettres sont autorisées");
  }

  function validateEmail() {
    return validateField(document.getElementById('email'), patterns.email, "Adresse email invalide");
  }

  function validatePassword() {
    const password = document.getElementById('password');
    let feedback = password.nextElementSibling;

    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
      feedback = document.createElement('div');
      feedback.classList.add('invalid-feedback');
      password.parentNode.appendChild(feedback);
    }

    if (password.value && password.value.length < 8) {
      password.classList.add('is-invalid');
      feedback.textContent = "Le mot de passe doit contenir au moins 8 caractères";
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
    if (!telephone.value) return true;
    return validateField(telephone, patterns.telephone, "Seuls les chiffres et le symbole + sont autorisés");
  }

  function validateClientFields() {
    const dateNaissance = document.getElementById('date_naissance');
    if (dateNaissance.closest('#clientFields').style.display !== 'none' && dateNaissance.value) {
      return validateField(dateNaissance, patterns.date, "Format requis: YYYY-MM-DD");
    }
    return true;
  }

  function validateEmployeeFields() {
    const type = document.getElementById('userType').value;
    if (type !== 'employe') return true;

    const isValidDate = validateField(document.getElementById('date_embauche'), patterns.date, "Format requis: YYYY-MM-DD");
    const isValidPoste = validateField(document.getElementById('poste'), patterns.alphabetsOnly, "Seules les lettres sont autorisées");
    const isValidSalaire = validateField(document.getElementById('salaire'), patterns.numbersOnly, "Salaire invalide");
    const isValidRole = validateField(document.getElementById('role'), patterns.alphabetsOnly, "Seules les lettres sont autorisées");

    return isValidDate && isValidPoste && isValidSalaire && isValidRole;
  }

  function validateUserType() {
    const userType = document.getElementById('userType');
    if (userType.value === '') {
      userType.classList.add('is-invalid');
      let feedback = userType.nextElementSibling;
      if (!feedback || !feedback.classList.contains('invalid-feedback')) {
        feedback = document.createElement('div');
        feedback.classList.add('invalid-feedback');
        userType.parentNode.appendChild(feedback);
      }
      feedback.textContent = "Veuillez choisir un type d'utilisateur";
      feedback.style.display = 'block';
      return false;
    } else {
      userType.classList.remove('is-invalid');
      const feedback = userType.nextElementSibling;
      if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.style.display = 'none';
      }
      return true;
    }
  }

  colisForms.forEach(form => {
    form.addEventListener("submit", function (e) {
      clearAllErrors();

      const isValid =
        validateUserType() &&
        validateNameFields() &&
        validateEmail() &&
        validatePassword() &&
        validateTelephone() &&
        validateClientFields() &&
        validateEmployeeFields();

      if (!isValid) {
        e.preventDefault();
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
  });

  // Real-time validation on blur
  document.getElementById('userType').addEventListener('blur', validateUserType);
  document.getElementById('nom').addEventListener('blur', validateNameFields);
  document.getElementById('prenom').addEventListener('blur', validateNameFields);
  document.getElementById('email').addEventListener('blur', validateEmail);
  document.getElementById('password').addEventListener('blur', validatePassword);
  document.getElementById('telephone').addEventListener('blur', validateTelephone);
  document.getElementById('date_naissance').addEventListener('blur', validateClientFields);
  document.getElementById('date_embauche').addEventListener('blur', validateEmployeeFields);
  document.getElementById('poste').addEventListener('blur', validateEmployeeFields);
  document.getElementById('role').addEventListener('blur', validateEmployeeFields);
  document.getElementById('salaire').addEventListener('blur', validateEmployeeFields);
});

