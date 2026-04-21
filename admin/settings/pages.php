<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Page Content';
$active_menu = 'pages';
require_role('super_admin', 'admin');

$section = $_GET['section'] ?? 'home';
$allowed = ['home','about','services','contact','footer'];
if (!in_array($section, $allowed)) $section = 'home';

// Keys per section
$section_keys = [
    'home' => [
        'home_hero_badge'       => 'Hero Badge Text',
        'home_stats_clients'    => 'Satisfied Clients count',
        'home_stats_projects'   => 'Projects Completed count',
        'home_stats_experience' => 'Years Experience count',
        'home_stats_team'       => 'Team Members count',
        'home_about_title'      => 'About Section Title',
        'home_about_text'       => 'About Section Text',
    ],
    'about' => [
        'about_title'       => 'Page Title',
        'about_mission'     => 'Mission Statement',
        'about_vision'      => 'Vision Statement',
        'about_values'      => 'Core Values',
    ],
    'contact' => [
        'contact_title'     => 'Page Title',
        'contact_subtitle'  => 'Page Subtitle',
        'contact_map_embed' => 'Google Maps Embed URL',
    ],
    'services' => [
        'services_title'    => 'Services Section Title',
        'services_subtitle' => 'Services Section Subtitle',
    ],
    'footer' => [
        'footer_about'      => 'Footer About Text',
        'footer_copyright'  => 'Copyright Text',
        'footer_col2_title' => 'Column 2 Heading',
        'footer_col3_title' => 'Column 3 Heading',
    ],
];

$keys = $section_keys[$section];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid request.');
    } else {
        foreach ($keys as $k => $label) {
            set_setting($k, trim($_POST[$k] ?? ''), 'pages_' . $section);
        }
        flash('success', ucfirst($section) . ' section content saved.');
    }
    header('Location: ' . APP_URL . "/admin/settings/pages.php?section={$section}");
    exit;
}

$vals = [];
foreach ($keys as $k => $label) $vals[$k] = get_setting($k);

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Page Content</h1><p>Edit the text content of each page section.</p></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
  <a href="<?= APP_URL ?>/admin/settings/index.php"      class="btn-secondary-admin"><i class="fa-solid fa-sliders"></i> General</a>
  <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="btn-secondary-admin"><i class="fa-solid fa-palette"></i> Appearance</a>
  <a href="<?= APP_URL ?>/admin/settings/slider.php"     class="btn-secondary-admin"><i class="fa-solid fa-images"></i> Hero Slider</a>
  <a href="<?= APP_URL ?>/admin/settings/pages.php"      class="btn-primary-admin"><i class="fa-solid fa-file-lines"></i> Page Content</a>
  <a href="<?= APP_URL ?>/admin/settings/popup.php"      class="btn-secondary-admin"><i class="fa-solid fa-bell"></i> Subscribe Popup</a>
</div>

<!-- Section Tabs -->
<div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:2px solid var(--admin-border);padding-bottom:0">
  <?php foreach ($allowed as $s): ?>
  <a href="?section=<?= $s ?>"
     style="padding:8px 16px;font-size:13px;font-weight:600;border-radius:6px 6px 0 0;<?= $s===$section ? 'background:var(--admin-primary);color:#fff' : 'color:var(--admin-muted)' ?>">
    <?= ucfirst($s) ?>
  </a>
  <?php endforeach; ?>
</div>

<div style="max-width:700px">
  <form method="POST">
    <?= csrf_field() ?>
    <div class="card">
      <div class="card-header"><h6><i class="fa-solid fa-file-pen"></i> <?= ucfirst($section) ?> Content</h6></div>
      <div class="card-body">
        <?php foreach ($keys as $k => $label): ?>
        <div class="form-group">
          <label class="form-label"><?= e($label) ?></label>
          <?php if (str_contains(strtolower($label),'text') || str_contains($k,'_text') || str_contains($k,'about') || str_contains($k,'mission') || str_contains($k,'vision') || str_contains($k,'values') || str_contains($k,'embed')): ?>
            <textarea name="<?= $k ?>" class="form-control" rows="3"><?= e($vals[$k]) ?></textarea>
          <?php else: ?>
            <input type="text" name="<?= $k ?>" class="form-control" value="<?= e($vals[$k]) ?>">
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Save <?= ucfirst($section) ?> Content</button>
      </div>
    </div>
  </form>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
