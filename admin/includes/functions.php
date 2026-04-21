<?php

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function slugify(string $text): string {
    $text = mb_strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return trim($text, '-');
}

function time_ago(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return $diff . 's ago';
    if ($diff < 3600)   return floor($diff / 60) . 'm ago';
    if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j, Y', strtotime($datetime));
}

function format_date(string $datetime, string $format = 'M j, Y g:i A'): string {
    return date($format, strtotime($datetime));
}

function upload_file(array $file, string $subfolder = 'general'): string {
    $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error: ' . $file['error']);
    }
    if ($file['size'] > $max_size) {
        throw new RuntimeException('File too large (max 5 MB).');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        throw new RuntimeException('File type not allowed: ' . $ext);
    }

    $dir = UPLOADS_PATH . '/' . $subfolder;
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = uniqid('', true) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
        throw new RuntimeException('Failed to save file.');
    }

    return $subfolder . '/' . $filename;
}

function delete_upload(string $path): void {
    $full = UPLOADS_PATH . '/' . ltrim($path, '/');
    if (is_file($full)) unlink($full);
}

function upload_url(string $path): string {
    return UPLOADS_URL . '/' . ltrim($path, '/');
}

function flash(string $key, string $msg): void {
    start_admin_session();
    $_SESSION['flash'][$key] = $msg;
}

function get_flash(string $key): ?string {
    start_admin_session();
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function paginate(int $total, int $per_page, int $page): array {
    $pages = max(1, (int) ceil($total / $per_page));
    $page  = max(1, min($page, $pages));
    return [
        'total'    => $total,
        'per_page' => $per_page,
        'page'     => $page,
        'pages'    => $pages,
        'offset'   => ($page - 1) * $per_page,
    ];
}

function get_setting(string $key, string $default = ''): string {
    static $cache = [];
    if (isset($cache[$key])) return $cache[$key];
    try {
        $pdo  = get_db();
        $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $val  = $stmt->fetchColumn();
        $cache[$key] = ($val !== false) ? $val : $default;
    } catch (Throwable $e) {
        $cache[$key] = $default;
    }
    return $cache[$key];
}

function set_setting(string $key, string $value, string $group = 'general'): void {
    $pdo = get_db();
    $pdo->prepare('INSERT INTO settings (setting_key, setting_value, setting_group)
                   VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_group = VALUES(setting_group)')
        ->execute([$key, $value, $group]);
}
