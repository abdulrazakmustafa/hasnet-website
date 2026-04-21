<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title  = 'My Profile';
$active_menu = '';
require_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $name      = trim($_POST['name'] ?? '');
        $email     = strtolower(trim($_POST['email'] ?? ''));
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (!$name)                                      $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
        if ($password && strlen($password) < 8)         $errors[] = 'Password must be at least 8 characters.';
        if ($password && $password !== $password2)      $errors[] = 'Passwords do not match.';

        if (empty($errors)) {
            $pdo = get_db();
            if ($password) {
                $pdo->prepare('UPDATE users SET name=?,email=?,password=?,updated_at=NOW() WHERE id=?')
                    ->execute([$name,$email,password_hash($password,HASH_ALGO),$current_user['id']]);
            } else {
                $pdo->prepare('UPDATE users SET name=?,email=?,updated_at=NOW() WHERE id=?')
                    ->execute([$name,$email,$current_user['id']]);
            }
            flash('success', 'Profile updated successfully.');
            header('Location: ' . APP_URL . '/admin/profile.php');
            exit;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="page-header"><div><h1>My Profile</h1><p>Update your account details.</p></div></div>
<?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($err) ?></div>
<?php endforeach; ?>
<div style="max-width:480px">
  <form method="POST">
    <?= csrf_field() ?>
    <div class="card">
      <div class="card-body">
        <div style="text-align:center;margin-bottom:20px">
          <div style="width:72px;height:72px;border-radius:50%;background:var(--admin-primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:24px">
            <?= strtoupper(substr($current_user['name'],0,1)) ?>
          </div>
          <div style="margin-top:8px">
            <span class="role-badge <?= e($current_user['role']) ?>"><?= str_replace('_',' ',e($current_user['role'])) ?></span>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? $current_user['name']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? $current_user['email']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password2" class="form-control">
        </div>
        <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Update Profile</button>
      </div>
    </div>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
