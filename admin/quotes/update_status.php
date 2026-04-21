<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_login();
header('Content-Type: application/json');

$id     = (int) ($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$csrf   = $_POST['csrf'] ?? '';

if (!verify_csrf($csrf) || !in_array($status, ['new','read','replied'])) {
    echo json_encode(['ok' => false]);
    exit;
}

get_db()->prepare('UPDATE quote_requests SET status = ? WHERE id = ?')->execute([$status, $id]);
echo json_encode(['ok' => true]);
