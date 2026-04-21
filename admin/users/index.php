<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Users';
$active_menu = 'users';
require_role('super_admin', 'admin');

$pdo   = get_db();
$users = $pdo->query('SELECT id, name, email, role, status, last_login, created_at FROM users ORDER BY created_at DESC')->fetchAll();

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Users</h1>
    <p>Manage admin panel users and their roles.</p>
  </div>
  <?php if (has_role('super_admin')): ?>
  <a href="<?= APP_URL ?>/admin/users/create.php" class="btn-primary-admin">
    <i class="fa-solid fa-user-plus"></i> Add User
  </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-header"><h6><i class="fa-solid fa-users"></i> All Users (<?= count($users) ?>)</h6></div>
  <div style="overflow-x:auto">
    <table class="admin-table">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last Login</th><th>Created</th><?php if(has_role('super_admin','admin')):?><th>Actions</th><?php endif;?></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:50%;background:var(--admin-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
              </div>
              <?= e($u['name']) ?>
              <?php if ((int)$u['id'] === (int)$current_user['id']): ?>
                <span style="font-size:10px;background:#f0fff4;color:#276749;padding:2px 6px;border-radius:4px;font-weight:700">You</span>
              <?php endif; ?>
            </div>
          </td>
          <td style="font-size:13px"><?= e($u['email']) ?></td>
          <td><span class="role-badge <?= e($u['role']) ?>"><?= str_replace('_', ' ', e($u['role'])) ?></span></td>
          <td><span class="badge-status <?= e($u['status']) ?>"><?= e($u['status']) ?></span></td>
          <td style="font-size:12px;color:var(--admin-muted)"><?= $u['last_login'] ? time_ago($u['last_login']) : 'Never' ?></td>
          <td style="font-size:12px;color:var(--admin-muted)"><?= format_date($u['created_at'],'M j, Y') ?></td>
          <?php if(has_role('super_admin','admin')): ?>
          <td>
            <div class="action-btns">
              <a href="<?= APP_URL ?>/admin/users/edit.php?id=<?= $u['id'] ?>" class="btn-edit-admin"><i class="fa-solid fa-pen"></i> Edit</a>
              <?php if ((int)$u['id'] !== (int)$current_user['id'] && has_role('super_admin')): ?>
              <a href="<?= APP_URL ?>/admin/users/delete.php?id=<?= $u['id'] ?>&csrf=<?= csrf_token() ?>"
                 class="btn-danger-admin"
                 data-confirm="Delete user &quot;<?= e($u['name']) ?>&quot;? This cannot be undone.">
                <i class="fa-solid fa-trash"></i>
              </a>
              <?php endif; ?>
            </div>
          </td>
          <?php endif; ?>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card" style="margin-top:20px">
  <div class="card-body">
    <h6 style="margin-bottom:12px;font-weight:700"><i class="fa-solid fa-circle-info"></i> Role Permissions</h6>
    <table class="admin-table">
      <thead><tr><th>Role</th><th>Portfolio</th><th>Blog</th><th>Subscribers</th><th>Quotes</th><th>Settings</th><th>Users</th></tr></thead>
      <tbody>
        <tr>
          <td><span class="role-badge super_admin">Super Admin</span></td>
          <td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>✅ Full</td>
        </tr>
        <tr>
          <td><span class="role-badge admin">Admin</span></td>
          <td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>✅ Full</td><td>⚠️ View only</td>
        </tr>
        <tr>
          <td><span class="role-badge editor">Editor</span></td>
          <td>✅ Create/Edit</td><td>✅ Create/Edit</td><td>👁️ View</td><td>👁️ View</td><td>❌ No access</td><td>❌ No access</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
