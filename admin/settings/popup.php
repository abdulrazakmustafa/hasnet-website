<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Subscribe Popup';
$active_menu = 'popup';
require_role('super_admin', 'admin');

$keys = ['subscribe_popup_enabled','subscribe_popup_delay','subscribe_popup_title','subscribe_popup_text'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid request.');
    } else {
        foreach ($keys as $k) {
            set_setting($k, trim($_POST[$k] ?? ''), 'popups');
        }
        flash('success', 'Popup settings saved.');
    }
    header('Location: ' . APP_URL . '/admin/settings/popup.php');
    exit;
}

$vals = [];
foreach ($keys as $k) $vals[$k] = get_setting($k);

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Subscribe Popup Settings</h1><p>Control the subscriber popup that appears on the website.</p></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
  <a href="<?= APP_URL ?>/admin/settings/index.php"      class="btn-secondary-admin"><i class="fa-solid fa-sliders"></i> General</a>
  <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="btn-secondary-admin"><i class="fa-solid fa-palette"></i> Appearance</a>
  <a href="<?= APP_URL ?>/admin/settings/slider.php"     class="btn-secondary-admin"><i class="fa-solid fa-images"></i> Hero Slider</a>
  <a href="<?= APP_URL ?>/admin/settings/pages.php"      class="btn-secondary-admin"><i class="fa-solid fa-file-lines"></i> Page Content</a>
  <a href="<?= APP_URL ?>/admin/settings/popup.php"      class="btn-primary-admin"><i class="fa-solid fa-bell"></i> Subscribe Popup</a>
</div>

<div style="max-width:600px">
  <form method="POST">
    <?= csrf_field() ?>
    <div class="card">
      <div class="card-header"><h6><i class="fa-solid fa-bell"></i> Popup Configuration</h6></div>
      <div class="card-body">

        <div class="form-group">
          <label class="form-label">Enable Popup</label>
          <div style="display:flex;align-items:center;gap:12px">
            <label class="toggle-switch">
              <input type="checkbox" name="subscribe_popup_enabled" value="1" <?= $vals['subscribe_popup_enabled'] ? 'checked' : '' ?>>
              <span class="slider"></span>
            </label>
            <span style="font-size:13px;color:var(--admin-muted)">Show popup on website</span>
          </div>
          <input type="hidden" name="subscribe_popup_enabled" value="0">
        </div>

        <div class="form-group">
          <label class="form-label">Delay (seconds)</label>
          <input type="number" name="subscribe_popup_delay" class="form-control" value="<?= e($vals['subscribe_popup_delay'] ?: '15') ?>" min="1" max="120">
          <div class="form-text">How many seconds after page load before the popup appears.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Popup Title</label>
          <input type="text" name="subscribe_popup_title" class="form-control" value="<?= e($vals['subscribe_popup_title'] ?: 'Stay Updated with Hasnet!') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Popup Message</label>
          <textarea name="subscribe_popup_text" class="form-control" rows="4"><?= e($vals['subscribe_popup_text'] ?: 'Subscribe to get updates about our seasonal discounts and daily offers from Hasnet ICT Solution.') ?></textarea>
        </div>

        <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Save Settings</button>
      </div>
    </div>
  </form>

  <div class="card" style="margin-top:20px">
    <div class="card-header"><h6><i class="fa-solid fa-eye"></i> Popup Preview</h6></div>
    <div class="card-body">
      <div style="border:1px solid var(--admin-border);border-radius:12px;padding:24px;max-width:380px;text-align:center;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.1)">
        <div style="font-size:32px;margin-bottom:12px">🔔</div>
        <h3 style="font-size:18px;font-weight:800;margin:0 0 8px;color:#27235f" id="preview-title"><?= e($vals['subscribe_popup_title'] ?: 'Stay Updated with Hasnet!') ?></h3>
        <p style="font-size:13px;color:#718096;margin:0 0 16px" id="preview-text"><?= e($vals['subscribe_popup_text'] ?: 'Subscribe to get updates...') ?></p>
        <input type="email" placeholder="Your Email Address" style="width:100%;padding:9px 12px;border:1.5px solid #e8ecf0;border-radius:7px;font-size:13px;margin-bottom:8px">
        <input type="tel" placeholder="WhatsApp Number (optional)" style="width:100%;padding:9px 12px;border:1.5px solid #e8ecf0;border-radius:7px;font-size:13px;margin-bottom:12px">
        <button style="width:100%;background:#27235f;color:#fff;border:none;padding:10px;border-radius:7px;font-weight:700;font-size:14px;cursor:pointer">Subscribe Now</button>
      </div>
    </div>
  </div>
</div>

<script>
// Live preview
document.querySelector('[name="subscribe_popup_title"]')?.addEventListener('input', function() {
  document.getElementById('preview-title').textContent = this.value;
});
document.querySelector('[name="subscribe_popup_text"]')?.addEventListener('input', function() {
  document.getElementById('preview-text').textContent = this.value;
});
// Fix checkbox - make sure enabled value wins
document.querySelector('form')?.addEventListener('submit', function() {
  const cb = this.querySelector('input[type="checkbox"][name="subscribe_popup_enabled"]');
  const hidden = this.querySelector('input[type="hidden"][name="subscribe_popup_enabled"]');
  if (cb?.checked) hidden.disabled = true;
});
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
