<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['user_id'])) {
    $_SESSION['user_id'] = $data['user_id'];
    // Set other session vars as needed
    http_response_code(200);
    exit;
}
http_response_code(401);
?>