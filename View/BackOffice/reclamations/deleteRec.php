<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';

// Ensure the request is a POST and 'id_rec' is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_rec'])) {
    $id_rec = (int)$_POST['id_rec']; // Cast to int for security

    $RecC = new ReclamationController();
    
    try {
        $RecC->deleteReclamation($id_rec);
        // After successful deletion, redirect
        header('Location: crud.php?success=1');
        exit();
    } catch (Exception $e) {
        // Handle errors gracefully
        header('Location: crud.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Invalid access or missing ID
    header('Location: crud.php?error=missing_id');
    exit();
}
?>
