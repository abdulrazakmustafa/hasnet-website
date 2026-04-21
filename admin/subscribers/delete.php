<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_login();
$id   = (int) ($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if (!verify_csrf($csrf)) { flash('error', 'Invalid request.'); }
else {
    get_db()->prepare('DELETE FROM subscribers WHERE id = ?')->execute([$id]);
    flash('success', 'Subscriber removed.');
}
header('Location: ' . APP_URL . '/admin/subscribers/index.php');
exit;
