document.addEventListener("DOMContentLoaded", () => {
  const colisForm = document.querySelector(".colis-form");

  colisForm.addEventListener("submit", function (e) {
      const idClient = document.getElementById("id_client").value;
      const idCovoit = document.getElementById("id_covoit").value || null; // Allow NULL value
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

      if (!dateColis || isNaN(longueur) || isNaN(largeur) || isNaN(hauteur) || isNaN(poids)) {
          alert("Veuillez remplir tous les champs de dimensions et le poids.");
          e.preventDefault();
          return;
      }

      if (!latRam || !lonRam || !latDest || !lonDest) {
          alert("Veuillez sélectionner les emplacements sur la carte.");
          e.preventDefault();
          return;
      }

      if (longueur < 1 || largeur < 1 || hauteur < 1 || poids < 0.1) {
          alert("Dimensions et poids doivent être supérieurs à zéro.");
          e.preventDefault();
          return;
      }

      // Send an AJAX request to FKCheck.php to verify client and covoiturage (if id_covoit is not NULL)
      const url = idCovoit ? `path_to_FKCheck.php?id_client=${idClient}&id_covoit=${idCovoit}` : `path_to_FKCheck.php?id_client=${idClient}`;

      fetch(url)
          .then(response => response.json())
          .then(data => {
              if (data.errorMessage) {
                  alert(data.errorMessage);  // Show the error message as an alert
                  e.preventDefault();  // Prevent the form submission
              } else {
                  // Continue with the form submission if everything is okay
                  // Calculate distance and price
                  const distance = calculateDistance(latRam, lonRam, latDest, lonDest);
                  const price = calculatePrice(poids, distance);
                  console.log(`Price: ${price}`);

                  // Update the hidden input field for price
                  document.getElementById("prix").value = price; // Set the value to the calculated price
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
              // Calculate distance
    const distance = calculateDistance(latRam, lonRam, latDest, lonDest);

    // Calculate price based on distance and weight
    const price = calculatePrice(poids, distance);
    console.log(`Price: ${price}`);

    // Update the hidden input field for price
    document.getElementById("prix").value = price; // Set the value to the calculated price
  });
  

  // Function to calculate distance
  function calculateDistance(lat1, lon1, lat2, lon2) {
      const earthRadius = 6371; // in km
      const dLat = deg2rad(lat2 - lat1);
      const dLon = deg2rad(lon2 - lon1);
      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      return earthRadius * c;
  }

  // Function to convert degrees to radians
  function deg2rad(deg) {
      return deg * (Math.PI / 180);
  }

  // Function to calculate price based on weight and distance
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
});
