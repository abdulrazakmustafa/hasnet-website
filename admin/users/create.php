<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Add User';
$active_menu = 'users';
require_role('super_admin');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $name     = trim($_POST['name'] ?? '');
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $password2= $_POST['password2'] ?? '';
        $role     = $_POST['role'] ?? 'editor';
        $status   = $_POST['status'] ?? 'active';

        if (!$name)                                          $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $errors[] = 'Valid email required.';
        if (strlen($password) < 8)                          $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $password2)                       $errors[] = 'Passwords do not match.';
        if (!in_array($role,['super_admin','admin','editor'])) $errors[] = 'Invalid role.';

        if (empty($errors)) {
            $pdo  = get_db();
            $exists = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $exists->execute([$email]);
            if ($exists->fetchColumn()) {
                $errors[] = 'A user with this email already exists.';
            } else {
                $hash = password_hash($password, HASH_ALGO);
                $pdo->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?,?,?,?,?)')->execute([$name,$email,$hash,$role,$status]);
                flash('success', "User $name created successfully.");
                header('Location: ' . APP_URL . '/admin/users/index.php');
                exit;
            }
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Add User</h1><p>Create a new admin panel user.</p></div>
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
          <input type="text" name="name" class="form-control" value="<?= e($_POST['name']??'') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" class="form-control" value="<?= e($_POST['email']??'') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password *</label>
          <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password *</label>
          <input type="password" name="password2" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Role</label>
          <select name="role" class="form-control form-select">
            <option value="editor"      <?= ($_POST['role']??'editor')==='editor'     ?'selected':'' ?>>Editor</option>
            <option value="admin"       <?= ($_POST['role']??'')==='admin'            ?'selected':'' ?>>Admin</option>
            <option value="super_admin" <?= ($_POST['role']??'')==='super_admin'      ?'selected':'' ?>>Super Admin</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control form-select">
            <option value="active"   <?= ($_POST['status']??'active')==='active'  ?'selected':'' ?>>Active</option>
            <option value="inactive" <?= ($_POST['status']??'')==='inactive'      ?'selected':'' ?>>Inactive</option>
          </select>
        </div>
        <button type="submit" class="btn-primary-admin">
          <i class="fa-solid fa-user-plus"></i> Create User
        </button>
      </div>
    </div>
  </form>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
