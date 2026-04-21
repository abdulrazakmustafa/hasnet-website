<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

start_admin_session();

// Already logged in → redirect
if (is_logged_in()) {
    header('Location: ' . APP_URL . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Please enter your email and password.';
        } elseif (!login_user($email, $password)) {
            $error = 'Invalid email or password.';
            // small delay to slow brute-force
            sleep(1);
        } else {
            $redirect = $_GET['redirect'] ?? (APP_URL . '/admin/index.php');
            header('Location: ' . $redirect);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Hasnet ICT Solution</title>
  <link rel="icon" href="<?= APP_URL ?>/assets/img/favicon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/fonts/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/admin/assets/css/admin.css">
</head>
<body class="admin-body">

<div class="login-page">
  <div class="login-card">

    <div class="login-logo">
      <img src="<?= APP_URL ?>/assets/img/logo.png" alt="Hasnet Logo">
    </div>

    <h1 class="login-title">Welcome back</h1>
    <p class="login-subtitle">Sign in to Hasnet Admin Panel</p>

    <?php if ($error): ?>
      <div class="alert alert-danger">
        <i class="fa-solid fa-circle-xmark"></i> <?= e($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" for="email">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="you@example.com"
          value="<?= e($_POST['email'] ?? '') ?>"
          required
          autofocus
        >
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div style="position:relative">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            placeholder="••••••••"
            required
            style="padding-right:40px"
          >
          <button
            type="button"
            id="togglePwd"
            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--admin-muted)"
          >
            <i class="fa-solid fa-eye" id="togglePwdIcon"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center;padding:11px">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </button>
    </form>

    <p style="text-align:center;margin-top:24px;font-size:12px;color:var(--admin-muted)">
      <i class="fa-solid fa-lock"></i> Secure Admin Area — Authorised Personnel Only
    </p>
  </div>
</div>

<script>
  document.getElementById('togglePwd')?.addEventListener('click', function () {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('togglePwdIcon');
    if (pwd.type === 'password') {
      pwd.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      pwd.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
</script>
</body>
</html>
