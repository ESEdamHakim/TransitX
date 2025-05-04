// Select all "Voir Météo" buttons on route cards
const voirMeteoButtons = document.querySelectorAll(".voir-meteo-btn");

// Add event listeners to each button
voirMeteoButtons.forEach((button) => {
  button.addEventListener("click", () => {
    console.log("Voir Météo button clicked"); // Debugging log
    const city = button.getAttribute("data-city");
    const date = button.getAttribute("data-date");
    console.log(`City: ${city}, Date: ${date}`); // Debugging log

    // Open the weather modal
    const modal = document.getElementById("weatherModal");
    if (modal) {
      modal.style.display = "block";
    } else {
      console.error("Weather modal not found in the DOM.");
    }

    // Fetch and display weather data
    if (typeof checkWeather === "function") {
      checkWeather(city, date);
    } else {
      console.error("checkWeather function is not defined.");
    }
  });
});

// Close the modal when clicking the close button
const closeWeatherModal = document.querySelector("#weatherModal .close");
if (closeWeatherModal) {
  closeWeatherModal.addEventListener("click", () => {
    const modal = document.getElementById("weatherModal");
    if (modal) {
      modal.style.display = "none";
    }
  });
} else {
  console.error("Close button for the weather modal not found in the DOM.");
}

// Close the modal when clicking outside of it
window.addEventListener("click", (event) => {
  const modal = document.getElementById("weatherModal");
  if (event.target === modal) {
    modal.style.display = "none";
  }
});