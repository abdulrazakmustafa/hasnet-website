<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_login();

$current_user  = current_user();
$page_title    = $page_title ?? 'Dashboard';
$active_menu   = $active_menu ?? '';
$new_quotes    = 0;
try {
    $pdo        = get_db();
    $new_quotes = (int) $pdo->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'new'")->fetchColumn();
} catch (Throwable) {}

$initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $current_user['name']), 0, 2)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?> — Hasnet Admin</title>
  <link rel="icon" href="<?= APP_URL ?>/assets/img/favicon.png" type="image/png">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/fonts/fontawesome/css/all.min.css">
  <!-- Admin CSS -->
  <link rel="stylesheet" href="<?= APP_URL ?>/admin/assets/css/admin.css">
  <!-- Quill Editor (rich text) -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <?= $extra_head ?? '' ?>
</head>
<body class="admin-body">

<div class="admin-layout">

  <!-- Sidebar Overlay (mobile) -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ── Sidebar ── -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <img src="<?= APP_URL ?>/assets/img/logo-white-2.png" alt="Hasnet Logo" onerror="this.style.display='none'">
      <div>
        <span>Hasnet Admin</span>
        <small>ICT Solution</small>
      </div>
    </div>

    <nav class="sidebar-nav">

      <div class="sidebar-section">Main</div>
      <a href="<?= APP_URL ?>/admin/index.php" class="<?= $active_menu==='dashboard' ? 'active' : '' ?>">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
      </a>

      <div class="sidebar-section">Content</div>
      <a href="<?= APP_URL ?>/admin/portfolio/index.php" class="<?= $active_menu==='portfolio' ? 'active' : '' ?>">
        <i class="fa-solid fa-briefcase"></i> Portfolio
      </a>
      <a href="<?= APP_URL ?>/admin/blog/index.php" class="<?= $active_menu==='blog' ? 'active' : '' ?>">
        <i class="fa-solid fa-newspaper"></i> Blog Posts
      </a>

      <div class="sidebar-section">Audience</div>
      <a href="<?= APP_URL ?>/admin/subscribers/index.php" class="<?= $active_menu==='subscribers' ? 'active' : '' ?>">
        <i class="fa-solid fa-envelope-open-text"></i> Subscribers
      </a>
      <a href="<?= APP_URL ?>/admin/quotes/index.php" class="<?= $active_menu==='quotes' ? 'active' : '' ?>">
        <i class="fa-solid fa-file-invoice"></i> Quote Requests
        <?php if ($new_quotes > 0): ?>
          <span class="badge-count"><?= $new_quotes ?></span>
        <?php endif; ?>
      </a>

      <div class="sidebar-section">Website</div>
      <a href="<?= APP_URL ?>/admin/settings/index.php" class="<?= $active_menu==='settings' ? 'active' : '' ?>">
        <i class="fa-solid fa-sliders"></i> General Settings
      </a>
      <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="<?= $active_menu==='appearance' ? 'active' : '' ?>">
        <i class="fa-solid fa-palette"></i> Appearance
      </a>
      <a href="<?= APP_URL ?>/admin/settings/pages.php" class="<?= $active_menu==='pages' ? 'active' : '' ?>">
        <i class="fa-solid fa-file-lines"></i> Page Content
      </a>
      <a href="<?= APP_URL ?>/admin/settings/slider.php" class="<?= $active_menu==='slider' ? 'active' : '' ?>">
        <i class="fa-solid fa-images"></i> Hero Slider
      </a>
      <a href="<?= APP_URL ?>/admin/settings/popup.php" class="<?= $active_menu==='popup' ? 'active' : '' ?>">
        <i class="fa-solid fa-bell"></i> Subscribe Popup
      </a>
      <a href="<?= APP_URL ?>/admin/media/index.php" class="<?= $active_menu==='media' ? 'active' : '' ?>">
        <i class="fa-solid fa-photo-film"></i> Media Library
      </a>

      <?php if (has_role('super_admin', 'admin')): ?>
      <div class="sidebar-section">Admin</div>
      <a href="<?= APP_URL ?>/admin/users/index.php" class="<?= $active_menu==='users' ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i> Users
      </a>
      <?php endif; ?>

      <div class="sidebar-section">Site</div>
      <a href="<?= APP_URL ?>/" target="_blank">
        <i class="fa-solid fa-globe"></i> View Website
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="<?= APP_URL ?>/admin/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Sign Out
      </a>
    </div>
  </aside>

  <!-- ── Topbar ── -->
  <header class="admin-topbar">
    <button class="sidebar-toggle-btn" id="sidebarToggle">
      <i class="fa-solid fa-bars"></i>
    </button>
    <span class="topbar-title"><?= e($page_title) ?></span>
    <div class="topbar-actions">
      <a href="<?= APP_URL ?>/" target="_blank" title="View Site" class="topbar-btn">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </a>
      <div style="position:relative">
        <div class="topbar-avatar" id="userMenuBtn" title="<?= e($current_user['name']) ?>">
          <?= e($initials) ?>
        </div>
        <div class="dropdown-menu-admin" id="userMenu">
          <div style="padding:10px 14px 8px">
            <div style="font-weight:700;font-size:13px"><?= e($current_user['name']) ?></div>
            <div style="font-size:11px;color:var(--admin-muted)"><?= e($current_user['email']) ?></div>
          </div>
          <div class="divider"></div>
          <a href="<?= APP_URL ?>/admin/profile.php"><i class="fa-solid fa-user fa-fw"></i> My Profile</a>
          <div class="divider"></div>
          <a href="<?= APP_URL ?>/admin/logout.php"><i class="fa-solid fa-right-from-bracket fa-fw"></i> Sign Out</a>
        </div>
      </div>
    </div>
  </header>

  <!-- ── Main Content ── -->
  <main class="admin-main">
<?php
// Flash messages
$flash_success = get_flash('success');
$flash_error   = get_flash('error');
if ($flash_success): ?>
  <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= e($flash_success) ?></div>
<?php endif;
if ($flash_error): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($flash_error) ?></div>
<?php endif; ?>
