<?php
require_once __DIR__ . '/db.php';

function start_admin_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/',
            'secure'   => (APP_ENV === 'production'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function is_logged_in(): bool {
    start_admin_session();
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    static $user = null;
    if ($user === null) {
        $pdo  = get_db();
        $stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE id = ? AND status = "active"');
        $stmt->execute([$_SESSION['admin_id']]);
        $user = $stmt->fetch() ?: null;
    }
    return $user;
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: ' . admin_url('login.php'));
        exit;
    }
}

function require_role(string ...$roles): void {
    require_login();
    $user = current_user();
    if (!$user || !in_array($user['role'], $roles, true)) {
        http_response_code(403);
        include ADMIN_PATH . '/403.php';
        exit;
    }
}

function has_role(string ...$roles): bool {
    $user = current_user();
    return $user && in_array($user['role'], $roles, true);
}

function login_user(string $email, string $password): bool {
    $pdo  = get_db();
    $stmt = $pdo->prepare('SELECT id, password, role, status FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);
    $row = $stmt->fetch();

    if (!$row || $row['status'] !== 'active') return false;
    if (!password_verify($password, $row['password']))  return false;

    start_admin_session();
    session_regenerate_id(true);
    $_SESSION['admin_id']   = $row['id'];
    $_SESSION['admin_role'] = $row['role'];

    // update last_login
    $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$row['id']]);
    return true;
}

function logout_user(): void {
    start_admin_session();
    $_SESSION = [];
    session_destroy();
}

function admin_url(string $path = ''): string {
    return APP_URL . '/admin/' . ltrim($path, '/');
}

function csrf_token(): string {
    start_admin_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    start_admin_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}
