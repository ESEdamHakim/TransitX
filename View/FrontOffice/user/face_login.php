<?php
require_once __DIR__ . '/../../../Controller/UserC.php';

// Get the base64 image from POST
if (isset($_POST['face_image'])) {
    $data = $_POST['face_image'];
    $data = str_replace('data:image/png;base64,', '', $data);
    $data = base64_decode($data);

    // Save the uploaded image temporarily
    $tempImagePath = __DIR__ . '/temp_face.png';
    file_put_contents($tempImagePath, $data);

    // Now, you need to compare this image with the user's stored image
    // For demo: fetch all users, compare with their stored image (you need a face recognition library here)
    // Example: using a Python script or external API for face comparison

    // Pseudo-code:
    // foreach ($users as $user) {
    //     $storedImagePath = __DIR__ . '/../../../uploads/' . $user->getImage();
    //     if (face_compare($tempImagePath, $storedImagePath)) {
    //         // Login success
    //         $_SESSION['user_id'] = $user->getId();
    //         header('Location: dashboard.php');
    //         exit;
    //     }
    // }
    // echo "Face not recognized.";

    // Clean up
    unlink($tempImagePath);
}
?>