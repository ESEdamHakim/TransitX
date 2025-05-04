// map-setup.js
let map;
let pickupMarker = null;
let deliveryMarker = null;
let clickStep = 0;

document.addEventListener('DOMContentLoaded', function () {
  const colisForms = document.querySelectorAll(".colis-form");

  colisForms.forEach(form => {
    form.addEventListener("submit", function (e) {
      clearAllErrors(); // Clear previous errors

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
        if (dimensionsError) {
          dimensionsError.innerHTML = '<div class="error-message" style="color: red; font-size: 0.85em;">❗ Les dimensions sont incorrectes. Chaque dimension doit être un nombre supérieur à 0.</div>';
        }
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

      if (hasError) {
        e.preventDefault(); // Stop form submission
        return;
      }

      // No errors → calculate distance and price
      const distance = calculateDistance(latRam, lonRam, latDest, lonDest);
      const price = calculatePrice(poids, distance);
      console.log(`✅ Price calculated: ${price} DT`);

      document.getElementById("prix").value = price;
    });
  });

});

function initMap() {
  // Fixed "current location" (you are treating this as the always-current point)
  const currentLocation = { lat: 36.8980431, lng: 10.1888733 };

  geocoder = new google.maps.Geocoder();
  directionsRenderer = new google.maps.DirectionsRenderer();

  // Initialize map with the fixed location
  map = new google.maps.Map(document.getElementById("gmap_canvas"), {
    center: currentLocation,
    zoom: 13,
  });

  directionsRenderer.setMap(map);

  // Red marker at the fixed location (treated as "current location")
  new google.maps.Marker({
    position: currentLocation,
    map: map,
    icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
    title: "Votre position par défaut"
  });

  // Automatically trigger a random click around the fixed location
  triggerRandomClick();

  function triggerRandomClick() {
    // Generate a random point within ~1km of the fixed location
    const randomLat = (Math.random() - 0.5) * 0.02 + currentLocation.lat;
    const randomLng = (Math.random() - 0.5) * 0.02 + currentLocation.lng;

    const randomLocation = new google.maps.LatLng(randomLat, randomLng);
    clickStep = -1;
    handleMapClick(randomLocation);
  }

  function handleMapClick(location) {
    const warningBox = document.getElementById("map-warning");

    if (clickStep === -1) {
      document.getElementById("latitude_ram").value = location.lat();
      document.getElementById("longitude_ram").value = location.lng();
      getCityFromCoordinates(location.lat(), location.lng(), "lieu_ram");
      clickStep = 0;
      return;
    }

    if (clickStep === 0) {
      if (pickupMarker) pickupMarker.setMap(null);
      pickupMarker = new google.maps.Marker({
        position: location,
        map: map,
        label: "A",
        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
      });

      document.getElementById("latitude_ram").value = location.lat();
      document.getElementById("longitude_ram").value = location.lng();
      getCityFromCoordinates(location.lat(), location.lng(), "lieu_ram");

      clickStep = 1;
      warningBox.textContent = "📍 Pickup set. Cliquez pour définir la livraison.";
      warningBox.style.color = "#333333";
      warningBox.classList.add("text-warning");
      warningBox.classList.remove("text-success");
    } else if (clickStep === 1) {
      if (deliveryMarker) deliveryMarker.setMap(null);
      deliveryMarker = new google.maps.Marker({
        position: location,
        map: map,
        label: "B",
        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
      });

      document.getElementById("latitude_dest").value = location.lat();
      document.getElementById("longitude_dest").value = location.lng();
      getCityFromCoordinates(location.lat(), location.lng(), "lieu_dest");

      clickStep = 0;
      warningBox.classList.remove("text-warning");
      warningBox.classList.remove("text-success");
      warningBox.textContent = "";
      drawRoute();
    }
  }
  // Listener for user clicks to set the delivery location
  map.addListener("click", function (event) {
    const clickedLocation = event.latLng;
    handleMapClick(clickedLocation);
  });
}

console.log("📦 lieu_ram:", lieuRam);
function getCityFromCoordinates(lat, lng, targetFieldId) {
  const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
  geocoder.geocode({ location: latlng }, (results, status) => {
    console.log("Geocoder status:", status);
    console.log("Geocoder results:", results);
  
    if (status === "OK" && results[0]) {
      let city = null;
      for (const comp of results[0].address_components) {
        if (
          comp.types.includes("locality") ||
          comp.types.includes("administrative_area_level_1") ||
          comp.types.includes("administrative_area_level_2")
        ) {
          city = comp.long_name;
          break;
        }
      }
  
      const field = document.getElementById(targetFieldId);
      if (field) {
        field.value = city || results[0].formatted_address;
        console.log(`✅ Set ${targetFieldId} to:`, field.value);
      } else {
        console.warn("⚠️ Target field not found:", targetFieldId);
      }
    } else {
      document.getElementById(targetFieldId).value = "Adresse non trouvée";
      console.error("Geocoder failed due to:", status);
    }
  });
  
}

function drawRoute() {
  if (!pickupMarker || !deliveryMarker) return;

  const directionsService = new google.maps.DirectionsService();
  const request = {
    origin: pickupMarker.getPosition(),
    destination: deliveryMarker.getPosition(),
    travelMode: google.maps.TravelMode.DRIVING,
  };

  directionsService.route(request, (result, status) => {
    if (status === "OK") {
      directionsRenderer.setDirections(result);
    } else {
      console.error("Erreur de direction: " + status);
    }
  });
}


function calculateDistance(lat1, lon1, lat2, lon2) {
  const earthRadius = 6371;
  const dLat = deg2rad(lat2 - lat1);
  const dLon = deg2rad(lon2 - lon1);
  const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return earthRadius * c;
}

function deg2rad(deg) {
  return deg * (Math.PI / 180);
}

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
    field.classList.add('shake');
  }
}

function clearAllErrors() {
  document.querySelectorAll(".error-message").forEach(el => el.remove());
  document.querySelectorAll(".shake").forEach(el => el.classList.remove("shake"));

  const dimensionsError = document.getElementById("dimensions-error");
  if (dimensionsError) {
    dimensionsError.innerHTML = "";
  }
  const mapWarning = document.getElementById("map-warning");
  if (mapWarning) {
    mapWarning.textContent = "";
  }
}
