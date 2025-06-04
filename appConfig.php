<?php


session_start(); // Start the session

// Check if the user is logged in by verifying if 'user_id' exists in the session
if (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id']; 
    $user_type=$_SESSION['user_type'];
    // Set $id_user to the logged-in user's ID
} else {
    $id_user = null; // Set $id_user to null if no user is logged in
}
?>