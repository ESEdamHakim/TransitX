<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="weatherstyles.css">
    <title>Weather</title>
</head>
<body>
<div class="card">
    <div class="search">
        <input type="text" placeholder="enter city name" spellcheck="false">
        <button><img src="./weather-app-img/images/search.png"></button>
    </div>
    <div class="error">
        <p>Invalid City Name</p>
    </div>
    <div class="weather">
        <img src="./weather-app-img/images/clear.png" class="weather-icon">
        <h1 class="temp">22°C</h1>
        <h2 class="city">Sydney</h2>
        <div class="detail">
            <div class="col">
                <img src="./weather-app-img/images/humidity.png">
                <div>
                <p class="humidity">15%</p>
                <p>Humidity</p>
                </div>
            </div>
            <div class="col">
                <img src="./weather-app-img/images/wind.png">
                <div>
                <p class="speed">10km/h</p>
                <p>Wind Speed</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const weatherIcon=document.querySelector(".weather-icon");
    const bgColor=document.querySelector(".card");
    const searchcity=document.querySelector(".search input");
    const searchbtn=document.querySelector(".search button");
    const apiKey="8aab6949191302a6a18a11e8f68d5acf";
    const apiUrl="https://api.openweathermap.org/data/2.5/weather?units=metric&q=";
    async function checkWeather(city){
        const response=await fetch(apiUrl+city+`&appid=${apiKey}`);
        if (response.status==404){
            document.querySelector(".error").style.display="block";
            document.querySelector(".weather").style.display="none";
        }
        else{
        var data=await response.json();
        
        
        document.querySelector(".city").innerHTML=data.name;
        document.querySelector(".temp").innerHTML=Math.round(data.main.temp)+"°C";
        document.querySelector(".humidity").innerHTML=data.main.humidity+"%";
        document.querySelector(".speed").innerHTML=Math.round(data.wind.speed)+"km/h";

        if (data.weather[0].main=="Clouds"){
            weatherIcon.src="./weather-app-img/images/clouds.png";
            bgColor.style.background="linear-gradient(135deg,#98dbf0,#00a9cc)";
        }
        else if(data.weather[0].main=="Clear"){
            weatherIcon.src="./weather-app-img/images/clear.png";
            bgColor.style.background="linear-gradient(135deg,#01d2fd,#00a9cc)";
        }
        else if(data.weather[0].main=="Rain"){
            weatherIcon.src="./weather-app-img/images/rain.png";
            bgColor.style.background="linear-gradient(135deg,005392,#00365d)";
        }
        else if(data.weather[0].main=="Drizzle"){
            weatherIcon.src="./weather-app-img/images/drizzle.png";
            bgColor.style.background="linear-gradient(135deg,#8fc4e0,#93b4c6)";
        }
        else if(data.weather[0].main=="Mist"){
            weatherIcon.src="./weather-app-img/images/mist.png";
            bgColor.style.background="linear-gradient(135deg,#c5ddea,#d0dfe8)";
        }
        document.querySelector(".error").style.display="none";
        document.querySelector(".weather").style.display="block";
    }
}
    searchbtn.addEventListener("click",()=>{
        checkWeather(searchcity.value);
    })
</script>  
</body>
</html>