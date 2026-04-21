<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_login();

$pdo  = get_db();
$rows = $pdo->query('SELECT email, whatsapp, source, subscribed_at FROM subscribers ORDER BY subscribed_at DESC')->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Email', 'WhatsApp', 'Source', 'Subscribed At']);
foreach ($rows as $row) fputcsv($out, $row);
fclose($out);
exit;
