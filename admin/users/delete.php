<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_role('super_admin');

$id   = (int) ($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if ((int)$id === (int)$current_user['id']) {
    flash('error', 'You cannot delete your own account.');
} elseif (!verify_csrf($csrf)) {
    flash('error', 'Invalid request.');
} else {
    get_db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    flash('success', 'User deleted.');
}

header('Location: ' . APP_URL . '/admin/users/index.php');
exit;
