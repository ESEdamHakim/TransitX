let map;
let directionsRenderer;
let directionsService;

function initMap() {
  const defaultLocation = { lat: 36.897248, lng: 10.193377 };
  let center = defaultLocation;

  // Center on first colis if available
  if (colisMarkers.length > 0 && colisMarkers[0].latitude_ram && colisMarkers[0].longitude_ram) {
    center = {
      lat: parseFloat(colisMarkers[0].latitude_ram),
      lng: parseFloat(colisMarkers[0].longitude_ram)
    };
  }

  map = new google.maps.Map(document.getElementById("colis-map"), {
    center: center,
    zoom: 8,
  });

  directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
  directionsService = new google.maps.DirectionsService();
  directionsRenderer.setMap(map);

  const colisIcon = {
    url: "../../assets/images/parcels.png",
    scaledSize: new google.maps.Size(20, 20),
    anchor: new google.maps.Point(20, 20)
  };

  // Add a marker for each colis
  colisMarkers.forEach(colis => {
    if (colis.latitude_ram && colis.longitude_ram) {
      const pickup = {
        lat: parseFloat(colis.latitude_ram),
        lng: parseFloat(colis.longitude_ram)
      };
      const delivery = {
        lat: parseFloat(colis.latitude_dest),
        lng: parseFloat(colis.longitude_dest)
      };

      const marker = new google.maps.Marker({
        position: pickup,
        map: map,
        icon: colisIcon,
        title: `Colis #${colis.id_colis}: ${colis.lieu_ram} → ${colis.lieu_dest}`
      });

      const info = new google.maps.InfoWindow({
        content: `
          <strong>Colis #${colis.id_colis}</strong><br>
          <b>De:</b> ${colis.lieu_ram}<br>
          <b>À:</b> ${colis.lieu_dest}<br>
          <b>Prix:</b> ${colis.prix} DT
        `
      });

      marker.addListener('click', () => {
        info.open(map, marker);
        drawRoute(pickup, delivery);
      });
    }
  });
}

function drawRoute(pickup, delivery) {
  const defaultLocation = { lat: 36.897248, lng: 10.193377 }; // Your default location

  if (!pickup || !delivery) return;

  directionsService.route({
    origin: defaultLocation,
    destination: delivery,
    waypoints: [
      { location: pickup, stopover: true }
    ],
    travelMode: google.maps.TravelMode.DRIVING,
  }, (result, status) => {
    if (status === "OK") {
      directionsRenderer.setDirections(result);
    } else {
      directionsRenderer.setDirections({ routes: [] });
    }
  });
}