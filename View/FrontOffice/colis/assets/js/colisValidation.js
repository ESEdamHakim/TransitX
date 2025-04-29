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

    // Date validation
    if (!dateColis) {
      showError("date_colis", "Veuillez entrer une date.");
    } else {
      const today = new Date();
      const selectedDate = new Date(dateColis);
      today.setHours(0, 0, 0, 0);
      selectedDate.setHours(0, 0, 0, 0);
    
      if (selectedDate < today) {
        showError("date_colis", "La date du colis ne peut pas être dans le passé.");
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

// Google Maps needs initMap to be global
function initMap() {
const defaultLocation = { lat: 36.8980431, lng: 10.1888733 }; // Tunis

map = new google.maps.Map(document.getElementById("gmap_canvas"), {
  center: defaultLocation,
  zoom: 13,
});

new google.maps.Marker({
  position: defaultLocation,
  map: map,
  title: "Default Location",
});

map.addListener("click", function (event) {
  const clickedLocation = event.latLng;
  const warningBox = document.getElementById("map-warning");

  if (clickStep === 0) {
    if (pickupMarker) pickupMarker.setMap(null);
    pickupMarker = new google.maps.Marker({
      position: clickedLocation,
      map: map,
      label: "A",
    });

    document.getElementById("latitude_ram").value = clickedLocation.lat();
    document.getElementById("longitude_ram").value = clickedLocation.lng();

    getCityFromCoordinates(clickedLocation.lat(), clickedLocation.lng(), "lieu_ram");

    clickStep = 1;
    warningBox.textContent = "📍 Pickup location set. Now click to choose the delivery location.";
    warningBox.classList.add("text-warning");
    warningBox.classList.remove("text-success");
  } else if (clickStep === 1) {
    if (deliveryMarker) deliveryMarker.setMap(null);
    deliveryMarker = new google.maps.Marker({
      position: clickedLocation,
      map: map,
      label: "B",
    });

    document.getElementById("latitude_dest").value = clickedLocation.lat();
    document.getElementById("longitude_dest").value = clickedLocation.lng();

    getCityFromCoordinates(clickedLocation.lat(), clickedLocation.lng(), "lieu_dest");

    clickStep = 0;
    warningBox.textContent = "✅ Delivery location set.";
    warningBox.classList.remove("text-warning");
    warningBox.classList.add("text-success");
  }
});
}

// New function replacing old geocoder
async function getCityFromCoordinates(lat, lon, targetFieldId) {
const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&accept-language=fr`;

try {
  const response = await fetch(url, {
    headers: {
      'User-Agent': 'TransitX/1.0 (hakimyessine72@gmail.com)' // update your email here
    }
  });

  if (!response.ok) {
    throw new Error('Nominatim API error: ' + response.statusText);
  }

  const data = await response.json();
  let city = data.address.city || data.address.town || data.address.village || data.address.hamlet || "Ville non trouvée";

  document.getElementById(targetFieldId).value = city;
} catch (error) {
  console.error('Erreur lors du géocodage inversé:', error);
  document.getElementById(targetFieldId).value = "Erreur géocodage";
}
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
