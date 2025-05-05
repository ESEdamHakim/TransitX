<?php
session_start(); // Start the session

// Simulate a logged-in user by hardcoding the user ID
if (!isset($_SESSION['id_user'])) {
    $_SESSION['id_user'] = 1; // Replace 1 with the desired user ID
}
?>