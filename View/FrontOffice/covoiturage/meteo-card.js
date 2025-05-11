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
    const apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";

    // Function to fetch and display weather data
    async function checkWeather(city) {
        try {
            console.log(`Fetching weather for city: ${city}`); // Debugging log

            // Fetch weather data from the API
            const response = await fetch(apiUrl + city + `&appid=${apiKey}`);
            if (response.status === 404) {
                // Show error if city is not found
                errorElement.style.display = "block";
                weatherElement.style.display = "none";
                return;
            }

            const data = await response.json();

            // Update weather details in the modal
            document.querySelector(".city").innerHTML = data.name;
            document.querySelector(".temp").innerHTML = Math.round(data.main.temp) + "°C";
            document.querySelector(".humidity").innerHTML = data.main.humidity + "%";
            document.querySelector(".speed").innerHTML = Math.round(data.wind.speed) + "km/h";

            // Update weather icon and background color based on weather conditions
            const weatherCondition = data.weather[0].main;
            updateWeatherAppearance(weatherCondition);

            // Hide error and show weather details
            errorElement.style.display = "none";
            weatherElement.style.display = "block";
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

    // Select all "Voir Météo" buttons on route cards
    const voirMeteoButtons = document.querySelectorAll(".weather-icon-btn");

    // Add event listeners to each button
    voirMeteoButtons.forEach((button) => {
        button.addEventListener("click", () => {
            console.log("Voir Météo button clicked"); // Debugging log
            const city = button.getAttribute("data-city");
            console.log(`City: ${city}`); // Debugging log

            // Open the weather modal
            const modal = document.getElementById("weatherModal");
            if (modal) {
                modal.style.display = "block";
            } else {
                console.error("Weather modal not found in the DOM.");
            }

            // Fetch and display weather data
            checkWeather(city);
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
});