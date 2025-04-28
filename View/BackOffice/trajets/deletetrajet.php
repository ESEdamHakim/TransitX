<?php
include("../../../Controller/trajetcontroller.php");

if (isset($_POST["id_trajet"])) {
    $trajetController = new TrajetController();
    $trajetController->deleteTrajet($_POST["id_trajet"]);
}

header("Location: crud.php");
exit();
?>
