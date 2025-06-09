<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}header('Content-Type: application/json');

$pdo = new PDO('mysql:host=localhost;dbname=transitx;charset=utf8', 'root', ''); // Update credentials

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    // Get messages with sender info
    $stmt = $pdo->prepare("
        SELECT m.*, u.nom AS sender_name, u.image AS sender_image
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.sender_id = :uid OR m.receiver_id = :uid OR m.receiver_id = 0
        ORDER BY m.created_at ASC
    ");
    $stmt->execute(['uid' => $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
    exit;
}

if ($action === 'users') {
    // Get all users for the select
    $stmt = $pdo->query("SELECT id, nom, image FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Add "All Users" option
    array_unshift($users, ['id' => 0, 'nom' => 'Tous les utilisateurs', 'image' => '']);
    echo json_encode($users);
    exit;
}

if ($action === 'send') {
    $data = json_decode(file_get_contents('php://input'), true);
    $text = trim($data['text'] ?? '');
    $receiver_id = intval($data['receiver_id'] ?? 0);

    if (!$text) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing text']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, text) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $receiver_id, htmlspecialchars($text)]);
    echo json_encode(['status' => 'ok']);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
?>