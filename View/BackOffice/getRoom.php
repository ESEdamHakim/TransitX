<?php
require_once '../../config.php'; // adjust path as needed

$db = config::getConnexion();
$stmt = $db->query("SELECT room_name FROM jitsi_meeting ORDER BY created_at DESC LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(['roomName' => $row ? $row['room_name'] : null]);
