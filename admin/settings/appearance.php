<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Appearance Settings';
$active_menu = 'appearance';
require_role('super_admin', 'admin');

$keys = ['primary_color','secondary_color','logo','logo_white','favicon'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid request.');
    } else {
        // Handle logo upload
        foreach (['logo'=>'logos','logo_white'=>'logos','favicon'=>'logos'] as $field => $folder) {
            if (!empty($_FILES[$field]['name'])) {
                try {
                    $path = upload_file($_FILES[$field], $folder);
                    set_setting($field, 'uploads/' . $path, 'appearance');
                } catch (RuntimeException $e) {
                    flash('error', $e->getMessage());
                }
            } elseif (!empty($_POST[$field])) {
                set_setting($field, trim($_POST[$field]), 'appearance');
            }
        }
        foreach (['primary_color','secondary_color'] as $k) {
            if (!empty($_POST[$k])) set_setting($k, $_POST[$k], 'appearance');
        }
        flash('success', 'Appearance settings saved.');
    }
    header('Location: ' . APP_URL . '/admin/settings/appearance.php');
    exit;
}

$vals = [];
foreach ($keys as $k) $vals[$k] = get_setting($k);

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Appearance Settings</h1><p>Manage logo, favicon, and brand colours.</p></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
  <a href="<?= APP_URL ?>/admin/settings/index.php"      class="btn-secondary-admin"><i class="fa-solid fa-sliders"></i> General</a>
  <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="btn-primary-admin"><i class="fa-solid fa-palette"></i> Appearance</a>
  <a href="<?= APP_URL ?>/admin/settings/slider.php"     class="btn-secondary-admin"><i class="fa-solid fa-images"></i> Hero Slider</a>
  <a href="<?= APP_URL ?>/admin/settings/pages.php"      class="btn-secondary-admin"><i class="fa-solid fa-file-lines"></i> Page Content</a>
  <a href="<?= APP_URL ?>/admin/settings/popup.php"      class="btn-secondary-admin"><i class="fa-solid fa-bell"></i> Subscribe Popup</a>
</div>

<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <!-- Brand Colours -->
    <div class="card">
      <div class="card-header"><h6><i class="fa-solid fa-fill-drip"></i> Brand Colours</h6></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Primary Colour</label>
          <div class="color-input-group">
            <input type="color" data-color-text="primary_color_text" value="<?= e($vals['primary_color'] ?: '#27235f') ?>">
            <input type="text" id="primary_color_text" name="primary_color" class="form-control" value="<?= e($vals['primary_color'] ?: '#27235f') ?>" placeholder="#27235f">
          </div>
          <div class="form-text">Main navbar and button colour.</div>
        </div>
        <div class="form-group">
          <label class="form-label">Secondary / Accent Colour</label>
          <div class="color-input-group">
            <input type="color" data-color-text="secondary_color_text" value="<?= e($vals['secondary_color'] ?: '#f04f23') ?>">
            <input type="text" id="secondary_color_text" name="secondary_color" class="form-control" value="<?= e($vals['secondary_color'] ?: '#f04f23') ?>" placeholder="#f04f23">
          </div>
          <div class="form-text">Accent buttons and highlights.</div>
        </div>
        <div class="alert alert-info">
          <i class="fa-solid fa-circle-info"></i>
          Colour changes will take effect after regenerating the CSS. Currently stored as settings for use in dynamic templates.
        </div>
      </div>
    </div>

    <!-- Logo & Favicon -->
    <div>
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6><i class="fa-solid fa-image"></i> Logo (Dark Background)</h6></div>
        <div class="card-body">
          <?php if ($vals['logo']): ?>
            <img src="<?= e(APP_URL . '/' . ltrim($vals['logo'],'/')) ?>" style="height:48px;margin-bottom:12px;display:block" alt="Logo">
          <?php endif; ?>
          <label for="logo_file" style="cursor:pointer">
            <div class="img-preview-box" id="logo-preview" style="max-width:100%;height:80px">
              <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Upload new logo</div>
            </div>
          </label>
          <input type="file" id="logo_file" name="logo" accept="image/*" style="display:none" data-preview="logo-preview">
        </div>
      </div>

      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6><i class="fa-solid fa-image"></i> Logo (Light/White Version)</h6></div>
        <div class="card-body">
          <?php if ($vals['logo_white']): ?>
            <img src="<?= e(APP_URL . '/' . ltrim($vals['logo_white'],'/')) ?>" style="height:48px;margin-bottom:12px;display:block;background:#27235f;padding:4px;border-radius:4px" alt="White Logo">
          <?php endif; ?>
          <label for="logo_white_file" style="cursor:pointer">
            <div class="img-preview-box" id="logo-white-preview" style="max-width:100%;height:80px;background:#27235f">
              <div class="placeholder" style="color:#fff"><i class="fa-solid fa-cloud-arrow-up"></i>Upload white logo</div>
            </div>
          </label>
          <input type="file" id="logo_white_file" name="logo_white" accept="image/*" style="display:none" data-preview="logo-white-preview">
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h6><i class="fa-solid fa-star"></i> Favicon</h6></div>
        <div class="card-body">
          <?php if ($vals['favicon']): ?>
            <img src="<?= e(APP_URL . '/' . ltrim($vals['favicon'],'/')) ?>" style="width:32px;height:32px;margin-bottom:12px;display:block" alt="Favicon">
          <?php endif; ?>
          <label for="favicon_file" style="cursor:pointer">
            <div class="img-preview-box" id="favicon-preview" style="max-width:100%;height:80px">
              <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Upload favicon (32×32 PNG)</div>
            </div>
          </label>
          <input type="file" id="favicon_file" name="favicon" accept="image/png,image/x-icon,image/ico" style="display:none" data-preview="favicon-preview">
        </div>
      </div>
    </div>

  </div>

  <div style="margin-top:20px">
    <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Save Appearance</button>
  </div>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
