document.addEventListener("DOMContentLoaded", () => {
  const colisForm = document.querySelector(".colis-form");

  colisForm.addEventListener("submit", function (e) {
    const dateColis = document.getElementById("date_colis").value;
    const longueur = parseFloat(document.getElementById("longueur").value);
    const largeur = parseFloat(document.getElementById("largeur").value);
    const hauteur = parseFloat(document.getElementById("hauteur").value);
    const poids = parseFloat(document.getElementById("poids").value);
    const latRam = parseFloat(document.getElementById("latitude_ram").value);
    const lonRam = parseFloat(document.getElementById("longitude_ram").value);
    const latDest = parseFloat(document.getElementById("latitude_dest").value);
    const lonDest = parseFloat(document.getElementById("longitude_dest").value);

    const errors = [];

    // Date validation
    if (!dateColis) {
      errors.push("La date du colis est requise.");
    } else {
      const today = new Date();
      const selectedDate = new Date(dateColis);
      today.setHours(0, 0, 0, 0);
      selectedDate.setHours(0, 0, 0, 0);

      if (selectedDate < today) {
        errors.push("La date du colis ne peut pas être dans le passé.");
      }
    }

    // Dimension and weight validation
    if (isNaN(longueur) || isNaN(largeur) || isNaN(hauteur) || isNaN(poids)) {
      errors.push("Veuillez remplir tous les champs de dimensions et le poids.");
    }

    if (longueur < 1 || largeur < 1 || hauteur < 1 || poids < 0.1) {
      errors.push("Dimensions et poids doivent être supérieurs à zéro.");
    }

    //  Map coordinates validation
    if (isNaN(latRam) || isNaN(lonRam) || isNaN(latDest) || isNaN(lonDest)) {
      errors.push("Veuillez sélectionner les emplacements sur la carte.");
    }

    //  Show errors if any
    if (errors.length > 0) {
      alert(errors.join("\n"));
      e.preventDefault();
      return;
    }

    // Calculate distance
    const distance = calculateDistance(latRam, lonRam, latDest, lonDest);

    // Calculate price based on distance and weight
    const price = calculatePrice(poids, distance);
    console.log(`Prix calculé: ${price} TND`);

    // Set hidden input field value for price
    document.getElementById("prix").value = price;
  });

  // Function to calculate distance using Haversine formula
  function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371; // in kilometers
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return earthRadius * c;
  }

  // Convert degrees to radians
  function deg2rad(deg) {
    return deg * (Math.PI / 180);
  }

  // Price calculation logic
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