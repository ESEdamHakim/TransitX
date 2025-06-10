<?php
header('Content-Type: application/json');
$host = 'localhost';
$db   = 'transitx'; // Change to your DB name
$user = 'root';     // Change if needed
$pass = '';         // Change if needed

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$result = $conn->query("SELECT username, message, image, timestamp FROM messages ORDER BY timestamp ASC");
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);
$conn->close();