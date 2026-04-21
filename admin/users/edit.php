<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Edit User';
$active_menu = 'users';
require_role('super_admin', 'admin');

$pdo  = get_db();
$id   = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    flash('error', 'User not found.');
    header('Location: ' . APP_URL . '/admin/users/index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $name     = trim($_POST['name'] ?? '');
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $password2= $_POST['password2'] ?? '';
        $status   = $_POST['status'] ?? 'active';
        $role     = $_POST['role'] ?? $user['role'];

        if (!$name)                                      $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
        if ($password && strlen($password) < 8)         $errors[] = 'Password must be at least 8 characters.';
        if ($password && $password !== $password2)      $errors[] = 'Passwords do not match.';

        // only super_admin can change roles
        if (!has_role('super_admin')) $role = $user['role'];

        if (empty($errors)) {
            // check email uniqueness
            $chk = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $chk->execute([$email, $id]);
            if ($chk->fetchColumn()) {
                $errors[] = 'Email already in use by another user.';
            } else {
                if ($password) {
                    $hash = password_hash($password, HASH_ALGO);
                    $pdo->prepare('UPDATE users SET name=?,email=?,password=?,role=?,status=?,updated_at=NOW() WHERE id=?')->execute([$name,$email,$hash,$role,$status,$id]);
                } else {
                    $pdo->prepare('UPDATE users SET name=?,email=?,role=?,status=?,updated_at=NOW() WHERE id=?')->execute([$name,$email,$role,$status,$id]);
                }
                flash('success', 'User updated successfully.');
                header('Location: ' . APP_URL . '/admin/users/index.php');
                exit;
            }
        }
    }
}

$d = $_SERVER['REQUEST_METHOD'] === 'POST' ? array_merge($user, $_POST) : $user;
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Edit User</h1><p><?= e($user['name']) ?></p></div>
  <a href="<?= APP_URL ?>/admin/users/index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Back</a>
</div>

<?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($err) ?></div>
<?php endforeach; ?>

<div style="max-width:560px">
  <form method="POST">
    <?= csrf_field() ?>
    <div class="card">
      <div class="card-header"><h6>User Details</h6></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?= e($d['name']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" value="<?= e($d['email']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        <div class="form-group">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="password2" class="form-control">
        </div>
        <?php if (has_role('super_admin')): ?>
        <div class="form-group">
          <label class="form-label">Role</label>
          <select name="role" class="form-control form-select">
            <option value="editor"      <?= $d['role']==='editor'     ?'selected':'' ?>>Editor</option>
            <option value="admin"       <?= $d['role']==='admin'      ?'selected':'' ?>>Admin</option>
            <option value="super_admin" <?= $d['role']==='super_admin'?'selected':'' ?>>Super Admin</option>
          </select>
        </div>
        <?php endif; ?>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control form-select" <?= ((int)$id===(int)$current_user['id'])?'disabled':'' ?>>
            <option value="active"   <?= $d['status']==='active'  ?'selected':'' ?>>Active</option>
            <option value="inactive" <?= $d['status']==='inactive'?'selected':'' ?>>Inactive</option>
          </select>
          <?php if ((int)$id===(int)$current_user['id']): ?>
            <div class="form-text">You cannot deactivate your own account.</div>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Update User</button>
      </div>
    </div>
  </form>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
