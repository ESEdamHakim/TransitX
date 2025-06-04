<?php
$city = $_GET['city'] ?? '';
$apiKey = "8aab6949191302a6a18a11e8f68d5acf";
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . urlencode($city) . "&appid=" . $apiKey;
$response = file_get_contents($apiUrl);
header('Content-Type: application/json');
echo $response;
?>