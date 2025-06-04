<?php
/**
 * Simple image serving script
 * This script will serve images from the uploads directory or serve a default image if not found
 */

// Set default content type
header('Content-Type: image/jpeg');

// Get the image filename from the query string
$filename = isset($_GET['file']) ? $_GET['file'] : 'default.png';

// Define the profiles directory
$profilesDir = __DIR__ . '/../View/assets/uploads/profiles/';
$defaultImage = __DIR__ . '/../View/assets/images/user-placeholder.png';

// Full path to the requested image
$imagePath = $profilesDir . $filename;

// If the image exists, output it
if (file_exists($imagePath)) {
    // Determine the correct content type based on the file extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if ($ext == 'jpg' || $ext == 'jpeg') {
        header('Content-Type: image/jpeg');
    } elseif ($ext == 'png') {
        header('Content-Type: image/png');
    } elseif ($ext == 'gif') {
        header('Content-Type: image/gif');
    }
    
    // Output the image content
    readfile($imagePath);
} else {
    // Serve the default image instead
    if (file_exists($defaultImage)) {
        readfile($defaultImage);
    } else {
        // If the default image doesn't exist, output an error
        header('Content-Type: text/plain');
        echo "Error: Image not found";
    }
}
?>
