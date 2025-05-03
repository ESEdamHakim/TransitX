// Modal handling
const modal = document.getElementById("weatherModal");
const openModalBtn = document.getElementById("openWeatherModal");
const closeModalBtn = document.querySelector(".close");
function displayCurrentDate() {
    const dateElement = document.querySelector(".current-date");
    const now = new Date();
    const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
    const formattedDate = now.toLocaleDateString("fr-FR", options); // Format date in French
    dateElement.innerHTML = formattedDate;
  }
// Open the modal
openModalBtn.addEventListener("click", () => {
  modal.style.display = "block";
  displayCurrentDate();
});

// Close the modal
closeModalBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

// Close the modal when clicking outside of it
window.addEventListener("click", (event) => {
  if (event.target === modal) {
    modal.style.display = "none";
  }
});

// Weather API handling
const weatherIcon = document.querySelector(".weather-icon");
const bgColor = document.querySelector(".card");
const searchcity = document.querySelector(".search input");
const searchbtn = document.querySelector(".search button");
const apiKey = "8aab6949191302a6a18a11e8f68d5acf";
const apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";

async function checkWeather(city) {
  const response = await fetch(apiUrl + city + `&appid=${apiKey}`);
  if (response.status == 404) {
    document.querySelector(".error").style.display = "block";
    document.querySelector(".weather").style.display = "none";
  } else {
    const data = await response.json();

    document.querySelector(".city").innerHTML = data.name;
    document.querySelector(".temp").innerHTML = Math.round(data.main.temp) + "°C";
    document.querySelector(".humidity").innerHTML = data.main.humidity + "%";
    document.querySelector(".speed").innerHTML = Math.round(data.wind.speed) + "km/h";

    if (data.weather[0].main == "Clouds") {
      weatherIcon.src = "./weather-app-img/images/clouds.png";
      bgColor.style.background = "linear-gradient(135deg,#98dbf0,#00a9cc)";
    } else if (data.weather[0].main == "Clear") {
      weatherIcon.src = "./weather-app-img/images/clear.png";
      bgColor.style.background = "linear-gradient(135deg,#01d2fd,#00a9cc)";
    } else if (data.weather[0].main == "Rain") {
      weatherIcon.src = "./weather-app-img/images/rain.png";
      bgColor.style.background = "linear-gradient(135deg,005392,#00365d)";
    } else if (data.weather[0].main == "Drizzle") {
      weatherIcon.src = "./weather-app-img/images/drizzle.png";
      bgColor.style.background = "linear-gradient(135deg,#8fc4e0,#93b4c6)";
    } else if (data.weather[0].main == "Mist") {
      weatherIcon.src = "./weather-app-img/images/mist.png";
      bgColor.style.background = "linear-gradient(135deg,#c5ddea,#d0dfe8)";
    }
    document.querySelector(".error").style.display = "none";
    document.querySelector(".weather").style.display = "block";
  }
}

searchbtn.addEventListener("click", () => {
  checkWeather(searchcity.value);
});