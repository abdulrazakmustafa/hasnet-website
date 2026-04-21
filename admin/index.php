<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title  = 'Dashboard';
$active_menu = 'dashboard';

require_login();

$pdo = get_db();

// Stats
$stats = [
    'portfolio'   => (int) $pdo->query("SELECT COUNT(*) FROM portfolio_items WHERE status='published'")->fetchColumn(),
    'blog'        => (int) $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status='published'")->fetchColumn(),
    'subscribers' => (int) $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn(),
    'quotes'      => (int) $pdo->query("SELECT COUNT(*) FROM quote_requests WHERE status='new'")->fetchColumn(),
];

// Recent portfolio items
$recent_portfolio = $pdo->query("SELECT id, title, status, created_at FROM portfolio_items ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Recent subscribers
$recent_subs = $pdo->query("SELECT email, whatsapp, subscribed_at FROM subscribers ORDER BY subscribed_at DESC LIMIT 5")->fetchAll();

// Recent quote requests
$recent_quotes = $pdo->query("SELECT id, name, email, service_option, status, created_at FROM quote_requests ORDER BY created_at DESC LIMIT 5")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Dashboard</h1>
    <p>Welcome back, <?= e($current_user['name']) ?>! Here's what's happening.</p>
  </div>
  <div style="font-size:12px;color:var(--admin-muted)">
    <i class="fa-regular fa-clock"></i> <?= date('l, F j, Y') ?>
  </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:28px">

  <a href="<?= APP_URL ?>/admin/portfolio/index.php" class="stat-card stat-purple" style="display:flex;align-items:center;gap:16px;padding:24px 20px;border-radius:14px;border:none;color:#fff;text-decoration:none;background:linear-gradient(135deg,#27235f,#4a3fbf);box-shadow:0 4px 18px rgba(0,0,0,.15);position:relative;overflow:hidden">
    <div class="stat-icon" style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="fa-solid fa-briefcase"></i></div>
    <div style="flex:1">
      <div style="font-size:32px;font-weight:800;line-height:1;color:#fff"><?= $stats['portfolio'] ?></div>
      <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.9);margin-top:3px">Portfolio Items</div>
      <div style="font-size:11px;color:rgba(255,255,255,.6)">Published works</div>
    </div>
    <i class="fa-solid fa-arrow-right" style="opacity:.5;font-size:14px"></i>
  </a>

  <a href="<?= APP_URL ?>/admin/blog/index.php" class="stat-card stat-blue" style="display:flex;align-items:center;gap:16px;padding:24px 20px;border-radius:14px;border:none;color:#fff;text-decoration:none;background:linear-gradient(135deg,#1a6fc4,#2d9cdb);box-shadow:0 4px 18px rgba(0,0,0,.15);position:relative;overflow:hidden">
    <div class="stat-icon" style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="fa-solid fa-newspaper"></i></div>
    <div style="flex:1">
      <div style="font-size:32px;font-weight:800;line-height:1;color:#fff"><?= $stats['blog'] ?></div>
      <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.9);margin-top:3px">Blog Posts</div>
      <div style="font-size:11px;color:rgba(255,255,255,.6)">Published posts</div>
    </div>
    <i class="fa-solid fa-arrow-right" style="opacity:.5;font-size:14px"></i>
  </a>

  <a href="<?= APP_URL ?>/admin/subscribers/index.php" class="stat-card stat-green" style="display:flex;align-items:center;gap:16px;padding:24px 20px;border-radius:14px;border:none;color:#fff;text-decoration:none;background:linear-gradient(135deg,#1a8a50,#27ae60);box-shadow:0 4px 18px rgba(0,0,0,.15);position:relative;overflow:hidden">
    <div class="stat-icon" style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="fa-solid fa-envelope-open-text"></i></div>
    <div style="flex:1">
      <div style="font-size:32px;font-weight:800;line-height:1;color:#fff"><?= $stats['subscribers'] ?></div>
      <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.9);margin-top:3px">Subscribers</div>
      <div style="font-size:11px;color:rgba(255,255,255,.6)">Total subscribers</div>
    </div>
    <i class="fa-solid fa-arrow-right" style="opacity:.5;font-size:14px"></i>
  </a>

  <a href="<?= APP_URL ?>/admin/quotes/index.php" class="stat-card stat-orange" style="display:flex;align-items:center;gap:16px;padding:24px 20px;border-radius:14px;border:none;color:#fff;text-decoration:none;background:linear-gradient(135deg,#c0380f,#f04f23);box-shadow:0 4px 18px rgba(0,0,0,.15);position:relative;overflow:hidden">
    <div class="stat-icon" style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="fa-solid fa-file-invoice"></i></div>
    <div style="flex:1">
      <div style="font-size:32px;font-weight:800;line-height:1;color:#fff"><?= $stats['quotes'] ?></div>
      <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.9);margin-top:3px">New Quotes</div>
      <div style="font-size:11px;color:rgba(255,255,255,.6)">Unread requests</div>
    </div>
    <i class="fa-solid fa-arrow-right" style="opacity:.5;font-size:14px"></i>
  </a>

</div>

<!-- Recent Activity Row -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">

  <!-- Recent Portfolio -->
  <div class="card">
    <div class="card-header">
      <h6><i class="fa-solid fa-briefcase"></i> Recent Portfolio</h6>
      <a href="<?= APP_URL ?>/admin/portfolio/index.php" class="btn-secondary-admin" style="padding:5px 12px;font-size:12px">View all</a>
    </div>
    <div style="overflow-x:auto">
      <?php if (empty($recent_portfolio)): ?>
        <div class="empty-state" style="padding:30px 20px">
          <i class="fa-solid fa-briefcase"></i>
          <p>No portfolio items yet.</p>
          <a href="<?= APP_URL ?>/admin/portfolio/create.php" class="btn-primary-admin">Add First Item</a>
        </div>
      <?php else: ?>
        <table class="admin-table">
          <thead><tr><th>Title</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($recent_portfolio as $item): ?>
            <tr>
              <td><?= e($item['title']) ?></td>
              <td><span class="badge-status <?= e($item['status']) ?>"><?= e($item['status']) ?></span></td>
              <td style="color:var(--admin-muted);font-size:12px"><?= time_ago($item['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent Subscribers -->
  <div class="card">
    <div class="card-header">
      <h6><i class="fa-solid fa-envelope-open-text"></i> Recent Subscribers</h6>
      <a href="<?= APP_URL ?>/admin/subscribers/index.php" class="btn-secondary-admin" style="padding:5px 12px;font-size:12px">View all</a>
    </div>
    <div style="overflow-x:auto">
      <?php if (empty($recent_subs)): ?>
        <div class="empty-state" style="padding:30px 20px">
          <i class="fa-solid fa-envelope"></i>
          <p>No subscribers yet.</p>
        </div>
      <?php else: ?>
        <table class="admin-table">
          <thead><tr><th>Email / WhatsApp</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($recent_subs as $sub): ?>
            <tr>
              <td>
                <?php if ($sub['email']): ?>
                  <div style="font-size:13px"><?= e($sub['email']) ?></div>
                <?php endif; ?>
                <?php if ($sub['whatsapp']): ?>
                  <div style="font-size:12px;color:var(--admin-muted)"><i class="fa-brands fa-whatsapp"></i> <?= e($sub['whatsapp']) ?></div>
                <?php endif; ?>
              </td>
              <td style="color:var(--admin-muted);font-size:12px"><?= time_ago($sub['subscribed_at']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- Quote Requests -->
<div class="card">
  <div class="card-header">
    <h6><i class="fa-solid fa-file-invoice"></i> Latest Quote Requests</h6>
    <a href="<?= APP_URL ?>/admin/quotes/index.php" class="btn-secondary-admin" style="padding:5px 12px;font-size:12px">View all</a>
  </div>
  <div style="overflow-x:auto">
    <?php if (empty($recent_quotes)): ?>
      <div class="empty-state" style="padding:30px 20px">
        <i class="fa-solid fa-file-invoice"></i>
        <p>No quote requests yet.</p>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Service</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($recent_quotes as $q): ?>
          <tr>
            <td><?= e($q['name']) ?></td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= e($q['email']) ?></td>
            <td style="font-size:12px"><?= e($q['service_option'] ?? '—') ?></td>
            <td><span class="badge-status <?= $q['status'] === 'new' ? 'published' : 'draft' ?>"><?= e($q['status']) ?></span></td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= time_ago($q['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
