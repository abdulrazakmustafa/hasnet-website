<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Access Denied — Hasnet Admin</title>
  <link rel="stylesheet" href="<?= defined('APP_URL') ? APP_URL : '' ?>/admin/assets/css/admin.css">
</head>
<body class="admin-body">
  <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px">
    <div style="text-align:center;max-width:400px">
      <div style="font-size:64px;margin-bottom:16px">🔒</div>
      <h2 style="font-size:24px;font-weight:800;color:var(--admin-text)">Access Denied</h2>
      <p style="color:var(--admin-muted);margin-bottom:24px">You don't have permission to access this page.</p>
      <a href="<?= defined('APP_URL') ? APP_URL : '' ?>/admin/index.php" class="btn-primary-admin" style="display:inline-flex">
        ← Back to Dashboard
      </a>
    </div>
  </div>
</body>
</html>
