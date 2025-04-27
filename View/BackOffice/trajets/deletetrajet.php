<?php
include("../../../Controller/trajetcontroller.php");

if (isset($_GET["id_trajet"])) {
    $trajetController = new TrajetController();
    $trajetController->deleteTrajet($_GET["id_trajet"]);
}

header("Location: crud.php");
exit();
?>
