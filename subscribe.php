<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$email    = strtolower(trim($_POST['email']    ?? ''));
$whatsapp = trim($_POST['whatsapp'] ?? '');
$source   = trim($_POST['source']   ?? 'popup');
$ip       = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

// Validate
if (empty($email) && empty($whatsapp)) {
    echo json_encode(['ok' => false, 'message' => 'Please enter at least an email or WhatsApp number.']);
    exit;
}
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $pdo = get_db();

    // Check for duplicate email
    if ($email) {
        $check = $pdo->prepare('SELECT id FROM subscribers WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetchColumn()) {
            echo json_encode(['ok' => true, 'message' => 'You\'re already subscribed! Thank you.']);
            exit;
        }
    }

    $stmt = $pdo->prepare('INSERT INTO subscribers (email, whatsapp, source, ip_address) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $email    ?: null,
        $whatsapp ?: null,
        $source,
        $ip,
    ]);

    echo json_encode(['ok' => true, 'message' => 'Thank you for subscribing! You\'ll receive our latest offers and updates.']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'message' => 'Something went wrong. Please try again.']);
}
