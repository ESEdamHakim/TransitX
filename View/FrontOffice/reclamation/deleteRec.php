<?php
require_once __DIR__ . '/../../../Controller/ReclamationController.php';

if (isset($_POST['id_rec'])) {
    $RecC = new ReclamationController();
    $RecC->deleteReclamation($_POST['id_rec']);
    header('Location: RecList.php');
    exit();
} else {
    echo "Reclamation ID not provided.";
}
?>