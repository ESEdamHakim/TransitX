<?php

session_start(); // Start the session

// Always set the hardcoded user ID for testing purposes
$_SESSION['id'] = 1; // Replace 1 with the desired user ID

// Set $id_user if the session ID exists
if (isset($_SESSION['id'])) {
    $id_user = $_SESSION['id'];
}
?>