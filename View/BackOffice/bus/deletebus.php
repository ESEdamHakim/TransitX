<?php
include("../../../Controller/buscontroller.php");

if (isset($_POST["id_bus"])) {
    $busController = new BusController();
    $busController->deleteBus($_POST["id_bus"]);
}

header("Location: crud.php");
exit();
?>
