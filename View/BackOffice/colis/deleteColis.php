<?php
require_once '../../../Controller/ColisController.php';

if (isset($_POST['id_colis'])) {
    $ColisC = new ColisController();
    $ColisC->deleteColis($_POST['id_colis']);
    header('Location: ColisList.php');
    exit();
} else {
    echo "Colis ID not provided.";
}
?>
