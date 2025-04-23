let formReady = false;

colisForm.addEventListener("submit", function (e) {
  if (!formReady) e.preventDefault(); // block default submit only the first time
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

  if (hasError) return;

  const url = idCovoit
    ? `../../../Model/Ajax/FKCheck.php?id_client=${idClient}&id_covoit=${idCovoit}`
    : `../../../Model/Ajax/FKCheck.php?id_client=${idClient}`;

    fetch(url)
    .then(response => response.json())
    .then(data => {
      clearError("id_client");
      clearError("id_covoit");
  
      if (!data.clientExists) {
        showError("id_client", data.errorMessage);
        return;
      }
  
      if (!data.covoitExists) {
        showError("id_covoit", data.errorMessage);
        return;
      }
  
      const distance = calculateDistance(latRam, lonRam, latDest, lonDest);
      const price = calculatePrice(poids, distance);
      document.getElementById("prix").value = price;
  
      colisForm.submit();
    })
    .catch(error => {
      console.error("Erreur serveur:", error);
    });
  

});
