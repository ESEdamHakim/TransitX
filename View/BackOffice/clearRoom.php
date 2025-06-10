<?php
require_once '../../config.php'; // adjust path as needed

$db = config::getConnexion();
$db->query("DELETE FROM jitsi_meeting");
echo json_encode(['success' => true]);