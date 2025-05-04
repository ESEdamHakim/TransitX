document.addEventListener("DOMContentLoaded", () => {
  // Select DOM elements
  const weatherIcon = document.querySelector(".weather-icon");
  const bgColor = document.querySelector(".card");
  const errorElement = document.querySelector(".error");
  const weatherElement = document.querySelector(".weather");

  // Check if elements exist
  if (!weatherIcon) console.error("Weather icon element not found in the DOM.");
  if (!bgColor) console.error("Card element not found in the DOM.");
  if (!errorElement) console.error("Error element not found in the DOM.");
  if (!weatherElement) console.error("Weather element not found in the DOM.");

  // API configuration
  const apiKey = "8aab6949191302a6a18a11e8f68d5acf";
  const forecastApiUrl = "https://api.openweathermap.org/data/2.5/forecast?units=metric&q=";

  // Function to fetch and display weather data
  async function checkWeather(city, selectedDate) {
    try {
      console.log(`Fetching weather for city: ${city}, date: ${selectedDate}`); // Debugging log

      // Fetch forecast data from the API
      const response = await fetch(forecastApiUrl + city + `&appid=${apiKey}`);
      if (response.status === 404) {
        showError("Ville introuvable. Veuillez entrer une ville valide.");
        return;
      }

      const data = await response.json();

      // Filter forecast data for the selected date
      const forecast = data.list.filter((item) => {
        const forecastDate = new Date(item.dt_txt).toISOString().split("T")[0];
        return forecastDate === selectedDate;
      });

      if (forecast.length > 0) {
        // Use the first forecast entry for the selected date
        const weatherData = forecast[0];

        // Update weather details in the modal
        if (weatherElement) {
          document.querySelector(".city").innerHTML = data.city.name;
          document.querySelector(".temp").innerHTML = Math.round(weatherData.main.temp) + "°C";
          document.querySelector(".humidity").innerHTML = weatherData.main.humidity + "%";
          document.querySelector(".speed").innerHTML = Math.round(weatherData.wind.speed) + "km/h";

          // Update weather icon and background color based on weather conditions
          const weatherCondition = weatherData.weather[0].main;
          updateWeatherAppearance(weatherCondition);

          // Hide error and show weather details
          errorElement.style.display = "none";
          weatherElement.style.display = "block";
        }
      } else {
        // No forecast data available for the selected date
        showError("Aucune donnée météo disponible pour la date sélectionnée.");
      }
    } catch (error) {
      console.error("Error fetching weather data:", error);
      showError("An error occurred while fetching weather data. Please try again.");
    }
  }

  // Function to update weather appearance (icon and background)
  function updateWeatherAppearance(condition) {
    if (!weatherIcon || !bgColor) return;

    if (condition === "Clouds") {
      weatherIcon.src = "./weather-app-img/images/clouds.png";
      bgColor.style.background = "linear-gradient(135deg,#98dbf0,#00a9cc)";
    } else if (condition === "Clear") {
      weatherIcon.src = "./weather-app-img/images/clear.png";
      bgColor.style.background = "linear-gradient(135deg,#01d2fd,#00a9cc)";
    } else if (condition === "Rain") {
      weatherIcon.src = "./weather-app-img/images/rain.png";
      bgColor.style.background = "linear-gradient(135deg,#005392,#00365d)";
    } else if (condition === "Drizzle") {
      weatherIcon.src = "./weather-app-img/images/drizzle.png";
      bgColor.style.background = "linear-gradient(135deg,#8fc4e0,#93b4c6)";
    } else if (condition === "Mist") {
      weatherIcon.src = "./weather-app-img/images/mist.png";
      bgColor.style.background = "linear-gradient(135deg,#c5ddea,#d0dfe8)";
    } else {
      weatherIcon.src = "./weather-app-img/images/default.png";
      bgColor.style.background = "linear-gradient(135deg,#d3d3d3,#a9a9a9)";
    }
  }

  // Function to show error messages
  function showError(message) {
    if (errorElement) {
      errorElement.style.display = "block";
      errorElement.textContent = message;
    } else {
      console.error("Error element not found in the DOM.");
    }

    if (weatherElement) {
      weatherElement.style.display = "none";
    }
  }

  // Attach checkWeather to the global window object
  window.checkWeather = checkWeather;
});