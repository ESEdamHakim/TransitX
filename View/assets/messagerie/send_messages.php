<?php
header('Content-Type: application/json');
session_start();
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

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$message = trim($data['message'] ?? '');
$image = trim($data['image'] ?? '../assets/images/user-placeholder.png');

if ($username === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Username and message required']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (username, message, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $message, $image);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Insert failed']);
}
$stmt->close();
$conn->close();