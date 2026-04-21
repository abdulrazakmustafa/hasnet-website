<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_role('super_admin', 'admin', 'editor');

$id   = (int) ($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if (!verify_csrf($csrf)) {
    flash('error', 'Invalid request.');
    header('Location: ' . APP_URL . '/admin/portfolio/index.php');
    exit;
}

$pdo  = get_db();
$item = $pdo->prepare('SELECT * FROM portfolio_items WHERE id = ?');
$item->execute([$id]);
$item = $item->fetch();

if ($item) {
    if ($item['featured_image']) delete_upload($item['featured_image']);
    $pdo->prepare('DELETE FROM portfolio_items WHERE id = ?')->execute([$id]);
    flash('success', 'Portfolio item deleted.');
} else {
    flash('error', 'Item not found.');
}

header('Location: ' . APP_URL . '/admin/portfolio/index.php');
exit;
