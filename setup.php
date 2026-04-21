<?php
/**
 * Hasnet ICT Solution – One-Time Setup Script
 * ============================================
 * Run this ONCE after uploading to server.
 * DELETE or RENAME this file after setup is complete.
 *
 * Access: https://yourdomain.com/setup.php
 */

define('SETUP_TOKEN', 'hasnet_setup_2025'); // change this before deploying

// Security check
if (($_GET['token'] ?? '') !== SETUP_TOKEN) {
    die('<h2 style="font-family:monospace;padding:20px">Access denied. Provide the setup token: ?token=' . SETUP_TOKEN . '</h2>');
}

require_once __DIR__ . '/config.php';

$log = [];
$errors = [];

function log_ok(string $msg): void { global $log; $log[] = ['ok', $msg]; }
function log_err(string $msg): void { global $log, $errors; $log[] = ['err', $msg]; $errors[] = $msg; }

// ── 1. Database Connection ───────────────────────────────────
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET,
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    log_ok('Database connection successful.');
} catch (PDOException $e) {
    log_err('Database connection failed: ' . $e->getMessage());
    showResult($log);
    exit;
}

// ── 2. Create Database ───────────────────────────────────────
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    log_ok('Database "' . DB_NAME . '" ready.');
} catch (PDOException $e) {
    log_err('Failed to create database: ' . $e->getMessage());
}

// ── 3. Run Schema ────────────────────────────────────────────
$schema_file = __DIR__ . '/database/schema.sql';
if (file_exists($schema_file)) {
    $sql = file_get_contents($schema_file);

    // Strip all comment lines (-- ...) before splitting on ;
    $lines    = explode("\n", $sql);
    $stripped = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (str_starts_with($trimmed, '--') || $trimmed === '') continue;
        $stripped[] = $line;
    }
    $clean_sql = implode("\n", $stripped);

    $statements = array_filter(array_map('trim', explode(';', $clean_sql)));
    $ok = 0; $fail = 0;
    foreach ($statements as $stmt) {
        if (empty($stmt)) continue;
        try {
            $pdo->exec($stmt);
            $ok++;
        } catch (PDOException $e) {
            // ignore "already exists" and duplicate errors (re-running setup)
            if (!str_contains($e->getMessage(), 'already exists') && !str_contains($e->getMessage(), 'Duplicate entry')) {
                $fail++;
                log_err('SQL error: ' . $e->getMessage());
            }
        }
    }
    log_ok("Schema executed: {$ok} statements OK, {$fail} errors.");
} else {
    log_err('Schema file not found: database/schema.sql');
}

// ── 4. Set Super Admin Password ──────────────────────────────
$admin_email    = 'abdulrazak.jmus@gmail.com';
$admin_password = '@Dulleycubic1';
$admin_name     = 'Abdulrazak Mustafa';

try {
    $hash = password_hash($admin_password, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?,?,?,?,?)
                           ON DUPLICATE KEY UPDATE password = VALUES(password), name = VALUES(name), role = "super_admin", status = "active"');
    $stmt->execute([$admin_name, $admin_email, $hash, 'super_admin', 'active']);
    log_ok('Super admin account created/updated: ' . $admin_email);
} catch (PDOException $e) {
    log_err('Failed to create admin: ' . $e->getMessage());
}

// ── 5. Create Upload Directories ────────────────────────────
$dirs = ['uploads', 'uploads/portfolio', 'uploads/blog', 'uploads/slider', 'uploads/logos', 'uploads/general'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            log_ok("Created directory: {$dir}");
        } else {
            log_err("Failed to create directory: {$dir}");
        }
    } else {
        log_ok("Directory exists: {$dir}");
    }
    // Create .htaccess to block PHP execution in uploads
    $htaccess = $path . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Options -ExecCGI\nAddHandler cgi-script .php .php3 .phtml .pl .py .jsp .asp .htm .shtml .sh .cgi\n");
    }
}

// ── 6. Check PHP Extensions ──────────────────────────────────
$required = ['pdo', 'pdo_mysql', 'mbstring', 'fileinfo', 'gd'];
foreach ($required as $ext) {
    if (extension_loaded($ext)) log_ok("PHP extension loaded: {$ext}");
    else log_err("Missing PHP extension: {$ext}");
}

showResult($log);

function showResult(array $log): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hasnet Setup</title>
  <style>
    body { font-family: monospace; padding: 30px; background: #f4f6fb; max-width: 700px; margin: 0 auto; }
    h1 { color: #27235f; }
    .ok  { color: #276749; }
    .err { color: #e53e3e; }
    li { padding: 4px 0; }
    .done { background: #f0fff4; border: 1px solid #c6f6d5; padding: 16px; border-radius: 8px; margin-top: 20px; }
    .warn { background: #fff5f5; border: 1px solid #fed7d7; padding: 16px; border-radius: 8px; margin-top: 20px; }
  </style>
</head>
<body>
  <h1>🛠 Hasnet Setup</h1>
  <ul>
    <?php foreach ($log as [$type, $msg]): ?>
      <li class="<?= $type ?>">
        <?= $type === 'ok' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php global $errors; if (empty($errors)): ?>
    <div class="done">
      <strong>✅ Setup complete!</strong><br><br>
      <strong>Login:</strong> <a href="admin/login.php">admin/login.php</a><br>
      <strong>Email:</strong> abdulrazak.jmus@gmail.com<br>
      <strong>Password:</strong> @Dulleycubic1<br><br>
      <strong>⚠️ IMPORTANT: Delete this setup.php file from your server immediately!</strong>
    </div>
  <?php else: ?>
    <div class="warn">
      <strong>❌ Setup completed with errors.</strong> Check the errors above and fix them before using the admin panel.
    </div>
  <?php endif; ?>
</body>
</html>
<?php }
