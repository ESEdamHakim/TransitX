<?php
session_start();
if (isset($_GET['room'])) {
    $_SESSION['active_meeting_room'] = $_GET['room'];
}
?>