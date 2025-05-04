let map;
let pickupMarker = null;
let deliveryMarker = null;
let directionsRenderer = null;
let clickStep = 0;
let geocoder;

document.addEventListener('DOMContentLoaded', function () {
  const colisForms = document.querySelectorAll(".colis-form");

  colisForms.forEach(form => {
    form.addEventListener("submit", function (e) {
      clearAllErrors();

      const dateColis = document.getElementById("date_colis").value;
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
      } else {
        const today = new Date();
        const selectedDate = new Date(dateColis);
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
          showError("date_colis", "La date du colis ne peut pas être dans le passé.");
          hasError = true;
        }
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
          mapWarning.textContent = "❗ Veuillez sélectionner les emplacements sur la carte.";
          mapWarning.style.color = "red";
        }
        hasError = true;
      }

      if (hasError) {
        e.preventDefault();
        return;
      }

      const distance = calculateDistance(latRam, lonRam, latDest, lonDest);
      const price = calculatePrice(poids, distance);
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

      const leg = result.routes[0].legs[0];
      const duration = leg.duration.text;
      const distance = leg.distance.text;

      const routeInfoBox = document.getElementById("route-info");
      const timeSpan = document.getElementById("estimated-time");
      const distanceSpan = document.getElementById("estimated-distance");

      if (routeInfoBox && timeSpan && distanceSpan) {
        timeSpan.textContent = duration;
        distanceSpan.textContent = distance;
        routeInfoBox.style.display = "block";
      }
    } else {
      console.error("Erreur de direction: " + status);
    }
  });
}

function calculateDistance(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = deg2rad(lat2 - lat1);
  const dLon = deg2rad(lon2 - lon1);
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
    Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

function deg2rad(deg) {
  return deg * (Math.PI / 180);
}

function calculatePrice(weight, distance) {
  if (weight < 1) return distance < 10 ? 5 : distance <= 30 ? 8 : 12;
  if (weight <= 5) return distance < 10 ? 8 : distance <= 30 ? 12 : 18;
  if (weight <= 10) return distance < 10 ? 12 : distance <= 30 ? 18 : 25;
  return distance < 10 ? 15 : distance <= 30 ? 22 : 30;
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
    error.style.textAlign = 'left'; 
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
  // Setup Delete Button click to open the confirmation modal
document.querySelectorAll('.open-delete-modal').forEach(button => {
  button.addEventListener('click', function () {
    const colisId = this.dataset.id;  // only id_colis available

    // Set hidden input for delete form
    const deleteFormIdInput = document.getElementById('delete-id');
    if (deleteFormIdInput) {
      deleteFormIdInput.value = colisId;
    }

    // Update modal text
    const modalBodyText = document.querySelector('#delete-modal .modal-body p');
    if (modalBodyText) {
      modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.`;
    }

    // Show modal
    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) {
      deleteModal.classList.add('active');
    }
  });
});

// Close modal when clicking on close button or cancel
document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
  button.addEventListener('click', function () {
    const modal = this.closest('.modal');
    if (modal) {
      modal.classList.remove('active');
    }
  });
});

// Confirm delete = Submit the hidden form
document.getElementById('confirm-delete-btn').addEventListener('click', function () {
  this.disabled = true; // disable to avoid double-click
  const deleteForm = document.getElementById('delete-form');
  if (deleteForm) {
    deleteForm.submit();
  }
});
