<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';

if (isset($_POST['id_rec'])) {
    $RecC = new ReclamationController();
    $RecC->deleteReclamation($_POST['id_rec']);
    header('Location: crud.php');
    exit();
} else {
    echo "Reclamation ID not provided.";
}
?>