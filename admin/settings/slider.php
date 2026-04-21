<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Hero Slider';
$active_menu = 'slider';
require_role('super_admin', 'admin');

// Slider is stored as JSON in settings
$slider_json = get_setting('hero_slides', '[]');
$slides      = json_decode($slider_json, true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid request.');
    } else {
        $new_slides = [];
        $titles     = $_POST['slide_title']   ?? [];
        $subtitles  = $_POST['slide_subtitle'] ?? [];
        $buttons    = $_POST['slide_button']   ?? [];
        $urls       = $_POST['slide_url']      ?? [];

        foreach ($titles as $i => $title) {
            if (!trim($title)) continue;
            $image = $slides[$i]['image'] ?? '';

            // Handle image upload for this slide
            if (!empty($_FILES["slide_image_{$i}"]['name'])) {
                try {
                    $image = upload_file($_FILES["slide_image_{$i}"], 'slider');
                } catch (RuntimeException $e) {
                    flash('error', 'Slide ' . ($i+1) . ': ' . $e->getMessage());
                }
            }

            $new_slides[] = [
                'title'    => trim($title),
                'subtitle' => trim($subtitles[$i] ?? ''),
                'button'   => trim($buttons[$i] ?? 'Explore More'),
                'url'      => trim($urls[$i] ?? '#'),
                'image'    => $image,
            ];
        }

        set_setting('hero_slides', json_encode($new_slides), 'slider');
        flash('success', 'Hero slider updated.');
    }
    header('Location: ' . APP_URL . '/admin/settings/slider.php');
    exit;
}

// Ensure at least 3 empty slots
while (count($slides) < 3) $slides[] = ['title'=>'','subtitle'=>'','button'=>'Explore More','url'=>'#','image'=>''];

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Hero Slider</h1><p>Manage homepage slider slides (title, subtitle, button, background image).</p></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
  <a href="<?= APP_URL ?>/admin/settings/index.php"      class="btn-secondary-admin"><i class="fa-solid fa-sliders"></i> General</a>
  <a href="<?= APP_URL ?>/admin/settings/appearance.php" class="btn-secondary-admin"><i class="fa-solid fa-palette"></i> Appearance</a>
  <a href="<?= APP_URL ?>/admin/settings/slider.php"     class="btn-primary-admin"><i class="fa-solid fa-images"></i> Hero Slider</a>
  <a href="<?= APP_URL ?>/admin/settings/pages.php"      class="btn-secondary-admin"><i class="fa-solid fa-file-lines"></i> Page Content</a>
  <a href="<?= APP_URL ?>/admin/settings/popup.php"      class="btn-secondary-admin"><i class="fa-solid fa-bell"></i> Subscribe Popup</a>
</div>

<div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Changes here update the database. The homepage slider will reflect these changes after clearing any page cache.</div>

<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <?php foreach ($slides as $i => $slide): ?>
  <div class="card" style="margin-bottom:20px">
    <div class="card-header">
      <h6><i class="fa-solid fa-image"></i> Slide <?= $i+1 ?></h6>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:200px 1fr;gap:20px;align-items:start">
        <!-- Image -->
        <div>
          <label class="form-label">Background Image</label>
          <label for="slide_img_<?= $i ?>" style="cursor:pointer">
            <div class="img-preview-box" id="slide-preview-<?= $i ?>" style="width:200px;height:130px">
              <?php if ($slide['image']): ?>
                <img src="<?= upload_url($slide['image']) ?>" style="width:100%;height:100%;object-fit:cover">
              <?php else: ?>
                <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Upload image</div>
              <?php endif; ?>
            </div>
          </label>
          <input type="file" id="slide_img_<?= $i ?>" name="slide_image_<?= $i ?>" accept="image/*" style="display:none" data-preview="slide-preview-<?= $i ?>">
        </div>
        <!-- Text -->
        <div>
          <div class="form-group">
            <label class="form-label">Slide Title *</label>
            <input type="text" name="slide_title[]" class="form-control" value="<?= e($slide['title']) ?>" placeholder="e.g. Networking & Digital Security">
          </div>
          <div class="form-group">
            <label class="form-label">Subtitle / Description</label>
            <textarea name="slide_subtitle[]" class="form-control" rows="2" placeholder="Short description..."><?= e($slide['subtitle']) ?></textarea>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="form-group">
              <label class="form-label">Button Text</label>
              <input type="text" name="slide_button[]" class="form-control" value="<?= e($slide['button'] ?: 'Explore More') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Button URL</label>
              <input type="text" name="slide_url[]" class="form-control" value="<?= e($slide['url'] ?: '#') ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-floppy-disk"></i> Save Slider</button>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
