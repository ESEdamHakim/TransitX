document.addEventListener('DOMContentLoaded', function () {
  const colisForms = document.querySelectorAll(".colis-form"); // <-- added '.'

  colisForms.forEach(form => {
    form.addEventListener("submit", function (e) {
      clearAllErrors(); // Clear any previous errors

      const idClient = document.getElementById("id_client")?.value.trim();
      const idCovoit = document.getElementById("id_covoit")?.value || null;
      const dateColis = document.getElementById("date_colis")?.value;
      const statut = document.getElementById("statut")?.value;
      const longueur = parseFloat(document.getElementById("longueur")?.value);
      const largeur = parseFloat(document.getElementById("largeur")?.value);
      const hauteur = parseFloat(document.getElementById("hauteur")?.value);
      const poids = parseFloat(document.getElementById("poids")?.value);
      const latRam = parseFloat(document.getElementById("latitude_ram")?.value);
      const lonRam = parseFloat(document.getElementById("longitude_ram")?.value);
      const latDest = parseFloat(document.getElementById("latitude_dest")?.value);
      const lonDest = parseFloat(document.getElementById("longitude_dest")?.value);

      let hasError = false;

      // Validation logic
      if (!idClient || idClient === "--") {
        showError("id_client", "Veuillez sélectionner un client.");
        hasError = true;
      }
      if (!dateColis) {
        showError("date_colis", "Veuillez entrer une date.");
        hasError = true;
      }
      if (
        isNaN(longueur) || longueur < 1 ||
        isNaN(largeur) || largeur < 1 ||
        isNaN(hauteur) || hauteur < 1
      ) {
        const dimensionsError = document.getElementById("dimensions-error");
        dimensionsError.innerHTML = '<div class="error-message" style="color: red; font-size: 0.85em;">❗ Les dimensions sont incorrectes. Chaque dimension doit être un nombre supérieur à 0.</div>';
        hasError = true;
      }      
      
      if (isNaN(poids) || poids < 0.1) {
        showError("poids", "Poids doit être supérieur à 0.");
        hasError = true;
      }
      if (isNaN(latRam) || isNaN(lonRam) || isNaN(latDest) || isNaN(lonDest)) {
        const mapWarning = document.getElementById("map-warning");
        if (mapWarning) {
          mapWarning.textContent = "❗ Veuillez sélectionner les emplacements sur la carte (ramassage et livraison).";
          mapWarning.style.color = "red";
        }
        hasError = true;
      }

      // Prevent form submission if errors exist
      if (hasError) {
        e.preventDefault();
        return;
      }

      // No errors → calculate distance and price
      const distance = calculateDistance(latRam, lonRam, latDest, lonDest);
      const price = calculatePrice(poids, distance);
      console.log(`✅ Price calculated: ${price} DT`);

      document.getElementById("prix").value = price; // Set calculated price in the form
      // No need to manually submit, form submits normally
    });
  });

  // Helper function to calculate distance using Haversine formula
  function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371; // Radius of Earth in kilometers
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return earthRadius * c;
  }

  // Helper function to convert degrees to radians
  function deg2rad(deg) {
    return deg * (Math.PI / 180);
  }

  // Helper function to calculate price based on weight and distance
  function calculatePrice(weight, distance) {
    if (weight < 1) {
      if (distance < 10) return 5;
      if (distance <= 30) return 8;
      return 12;
    } else if (weight <= 5) {
      if (distance < 10) return 8;
      if (distance <= 30) return 12;
      return 18;
    } else if (weight <= 10) {
      if (distance < 10) return 12;
      if (distance <= 30) return 18;
      return 25;
    } else {
      if (distance < 10) return 15;
      if (distance <= 30) return 22;
      return 30;
    }
  }

  // Helper functions to show and clear errors
  function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (field) {
      const existingError = field.parentNode.querySelector(".error-message");
      if (existingError) existingError.remove();

      const error = document.createElement('div');
      error.className = 'error-message';
      error.style.color = 'red';
      error.style.fontSize = '0.85em';
      error.textContent = message;

      field.parentNode.appendChild(error);
      field.classList.add('shake'); // optional animation
    }
  }

  function clearAllErrors() {
    document.querySelectorAll(".error-message").forEach(el => el.remove());
    document.querySelectorAll(".shake").forEach(el => el.classList.remove("shake"));
    
    // Clear dimensions-specific error if exists
    const dimensionsError = document.getElementById("dimensions-error");
    if (dimensionsError) {
      dimensionsError.innerHTML = "";
    }
  }
  
});
