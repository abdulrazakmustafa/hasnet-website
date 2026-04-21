<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_role('super_admin', 'admin', 'editor');

$id   = (int) ($_GET['id'] ?? 0);
$csrf = $_GET['csrf'] ?? '';

if (!verify_csrf($csrf)) { flash('error', 'Invalid request.'); }
else {
    $pdo  = get_db();
    $stmt = $pdo->prepare('SELECT featured_image FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if ($post) {
        if ($post['featured_image']) delete_upload($post['featured_image']);
        $pdo->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
        flash('success', 'Post deleted.');
    } else {
        flash('error', 'Post not found.');
    }
}

header('Location: ' . APP_URL . '/admin/blog/index.php');
exit;
