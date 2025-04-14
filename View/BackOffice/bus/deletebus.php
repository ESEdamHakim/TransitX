<?php
include("../../../Controller/buscontroller.php");

if (isset($_GET["id_bus"])) {
    $busController = new BusController();
    $busController->deleteBus($_GET["id_bus"]);
}

header("Location: crud.php");
exit();
?>
