let formReady = false;

const colisForm = document.getElementById("colisForm"); // Select form

colisForm.addEventListener("submit", function (e) {
  if (!formReady) e.preventDefault(); // block default submit if not ready yet
  clearAllErrors();

  const idClient = document.getElementById("id_client").value.trim();
  const idCovoit = document.getElementById("id_covoit")?.value || null;
  const dateColis = document.getElementById("date_colis").value;
  const statut = document.getElementById("statut").value;
  const longueur = parseFloat(document.getElementById("longueur").value);
  const largeur = parseFloat(document.getElementById("largeur").value);
  const hauteur = parseFloat(document.getElementById("hauteur").value);
  const poids = parseFloat(document.getElementById("poids").value);
  const latRam = parseFloat(document.getElementById("latitude_ram").value);
  const lonRam = parseFloat(document.getElementById("longitude_ram").value);
  const latDest = parseFloat(document.getElementById("latitude_dest").value);
  const lonDest = parseFloat(document.getElementById("longitude_dest").value);

  let hasError = false;

  if (!dateColis) {
    showError("date_colis", "Veuillez entrer une date.");
    hasError = true;
  }
  if (isNaN(longueur) || longueur < 1) {
    showError("longueur", "Longueur doit être un nombre supérieur à 0.");
    hasError = true;
  }
  if (isNaN(largeur) || largeur < 1) {
    showError("largeur", "Largeur doit être un nombre supérieur à 0.");
    hasError = true;
  }
  if (isNaN(hauteur) || hauteur < 1) {
    showError("hauteur", "Hauteur doit être un nombre supérieur à 0.");
    hasError = true;
  }
  if (isNaN(poids) || poids < 0.1) {
    showError("poids", "Poids doit être supérieur à 0.");
    hasError = true;
  }
  if (!latRam || !lonRam || !latDest || !lonDest) {
    const mapWarning = document.getElementById("map-warning");
    if (mapWarning) {
      mapWarning.textContent = "❗ Veuillez sélectionner les emplacements sur la carte (ramassage et livraison).";
      mapWarning.style.color = "red";
    }
    hasError = true;
  }

  if (hasError) {
    return; // stop here if any error
  }

  // ✅ No errors found: allow submit
  formReady = true;
  colisForm.submit();
});

// ⬇️ Correct helper functions outside the event listener:

function showError(fieldId, message) {
  const field = document.getElementById(fieldId);
  if (field) {
    // remove previous error message if any
    const existingError = field.parentNode.querySelector(".error-message");
    if (existingError) existingError.remove();

    const error = document.createElement('div');
    error.className = 'error-message';
    error.style.color = 'red';
    error.style.fontSize = '0.85em';
    error.textContent = message;

    field.parentNode.appendChild(error);

    field.classList.add('shake'); // optional if you have animation
  }
}

function clearAllErrors() {
  document.querySelectorAll(".error-message").forEach(el => el.remove());
  document.querySelectorAll(".shake").forEach(el => el.classList.remove("shake"));
}
