<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'General Settings';
$active_menu = 'settings';
require_role('super_admin', 'admin');

$groups = ['general', 'social', 'footer'];
$all_keys = [
    'site_name','site_tagline','site_email','site_phone','site_phone2',
    'site_address','site_address2','site_whatsapp',
    'footer_about',
    'social_facebook','social_instagram','social_twitter','social_linkedin','social_youtube','social_whatsapp',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid request.');
    } else {
        $pdo = get_db();
        foreach ($all_keys as $k) {
            $group = str_starts_with($k, 'social_') ? 'social' : (str_starts_with($k, 'footer_') ? 'footer' : 'general');
            set_setting($k, trim($_POST[$k] ?? ''), $group);
        }
        flash('success', 'Settings saved successfully.');
    }
    header('Location: ' . APP_URL . '/admin/settings/index.php');
    exit;
}

// Load current values
$vals = [];
foreach ($all_keys as $k) $vals[$k] = get_setting($k);

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>General Settings</h1><p>Manage core website information, contact details and social links.</p></div>
</div>

<!-- Settings Nav -->
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
  <a href="<?= APP_URL ?>/admin/settings/index.php"      class="btn-primary-admin"><i class="fa-solid fa-sliders"></i> General</a>
  <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="btn-secondary-admin"><i class="fa-solid fa-palette"></i> Appearance</a>
  <a href="<?= APP_URL ?>/admin/settings/slider.php"     class="btn-secondary-admin"><i class="fa-solid fa-images"></i> Hero Slider</a>
  <a href="<?= APP_URL ?>/admin/settings/pages.php"      class="btn-secondary-admin"><i class="fa-solid fa-file-lines"></i> Page Content</a>
  <a href="<?= APP_URL ?>/admin/settings/popup.php"      class="btn-secondary-admin"><i class="fa-solid fa-bell"></i> Subscribe Popup</a>
</div>

<form method="POST">
  <?= csrf_field() ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <!-- Site Info -->
    <div class="card">
      <div class="card-header"><h6><i class="fa-solid fa-globe"></i> Site Information</h6></div>
      <div class="card-body">
        <?php foreach (['site_name'=>'Site Name','site_tagline'=>'Site Tagline','site_email'=>'Contact Email','site_phone'=>'Phone Number','site_phone2'=>'Phone Number 2','site_address'=>'Address Line 1','site_address2'=>'Address Line 2','site_whatsapp'=>'WhatsApp Number'] as $k => $label): ?>
        <div class="form-group">
          <label class="form-label"><?= $label ?></label>
          <input type="text" name="<?= $k ?>" class="form-control" value="<?= e($vals[$k]) ?>">
        </div>
        <?php endforeach; ?>
        <div class="form-group">
          <label class="form-label">Footer About Text</label>
          <textarea name="footer_about" class="form-control" rows="3"><?= e($vals['footer_about']) ?></textarea>
        </div>
      </div>
    </div>

    <!-- Social Media -->
    <div class="card">
      <div class="card-header"><h6><i class="fa-solid fa-share-nodes"></i> Social Media Links</h6></div>
      <div class="card-body">
        <?php $socials = [
          'social_facebook'  => ['fa-brands fa-facebook-f',  'Facebook'],
          'social_instagram' => ['fa-brands fa-instagram',   'Instagram'],
          'social_twitter'   => ['fa-brands fa-twitter',     'Twitter / X'],
          'social_linkedin'  => ['fa-brands fa-linkedin-in', 'LinkedIn'],
          'social_youtube'   => ['fa-brands fa-youtube',     'YouTube'],
          'social_whatsapp'  => ['fa-brands fa-whatsapp',    'WhatsApp Link'],
        ]; ?>
        <?php foreach ($socials as $k => [$icon, $label]): ?>
        <div class="form-group">
          <label class="form-label"><i class="<?= $icon ?>"></i> <?= $label ?></label>
          <input type="url" name="<?= $k ?>" class="form-control" value="<?= e($vals[$k]) ?>" placeholder="https://">
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <div style="margin-top:20px">
    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Save All Settings</button>
  </div>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
