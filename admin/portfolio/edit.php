<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Edit Portfolio Item';
$active_menu = 'portfolio';
require_role('super_admin', 'admin', 'editor');

$pdo  = get_db();
$id   = (int) ($_GET['id'] ?? 0);
$item = $pdo->prepare('SELECT * FROM portfolio_items WHERE id = ?');
$item->execute([$id]);
$item = $item->fetch();

if (!$item) {
    flash('error', 'Portfolio item not found.');
    header('Location: ' . APP_URL . '/admin/portfolio/index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $title          = trim($_POST['title'] ?? '');
        $slug           = trim($_POST['slug'] ?? '') ?: slugify($title);
        $description    = trim($_POST['description'] ?? '');
        $content        = $_POST['content'] ?? '';
        $category       = trim($_POST['category'] ?? '');
        $tags           = trim($_POST['tags'] ?? '');
        $client         = trim($_POST['client'] ?? '');
        $project_url    = trim($_POST['project_url'] ?? '');
        $completed_date = $_POST['completed_date'] ?: null;
        $status         = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'draft';
        $sort_order     = (int) ($_POST['sort_order'] ?? 0);

        if (!$title) $errors[] = 'Title is required.';

        // Handle image
        $featured_image = $item['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            try {
                $new = upload_file($_FILES['featured_image'], 'portfolio');
                if ($featured_image) delete_upload($featured_image);
                $featured_image = $new;
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare('UPDATE portfolio_items SET
                title=?,slug=?,description=?,content=?,featured_image=?,category=?,tags=?,client=?,project_url=?,completed_date=?,status=?,sort_order=?,updated_at=NOW()
                WHERE id=?');
            $stmt->execute([$title,$slug,$description,$content,$featured_image,$category,$tags,$client,$project_url,$completed_date,$status,$sort_order,$id]);

            flash('success', 'Portfolio item updated successfully.');
            header('Location: ' . APP_URL . '/admin/portfolio/index.php');
            exit;
        }
    }
}

// Use POST data on error, or item data
$d = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $item;

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Edit Portfolio Item</h1>
    <p>Update: <?= e($item['title']) ?></p>
  </div>
  <a href="<?= APP_URL ?>/admin/portfolio/index.php" class="btn-secondary-admin">
    <i class="fa-solid fa-arrow-left"></i> Back
  </a>
</div>

<?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($err) ?></div>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

    <div>
      <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h6>Item Details</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Title *</label>
            <input type="text" id="input-title" name="title" class="form-control" value="<?= e($d['title']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Slug</label>
            <input type="text" id="input-slug" name="slug" class="form-control" value="<?= e($d['slug']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Short Description</label>
            <textarea name="description" class="form-control" rows="3"><?= e($d['description']) ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content</label>
            <div data-quill="content-hidden" style="min-height:200px"></div>
            <input type="hidden" id="content-hidden" name="content" value="<?= e($d['content']) ?>">
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Publish</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control form-select">
              <option value="draft"     <?= $d['status']==='draft'     ?'selected':'' ?>>Draft</option>
              <option value="published" <?= $d['status']==='published' ?'selected':'' ?>>Published</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($d['sort_order']) ?>" min="0">
          </div>
          <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center">
            <i class="fa-solid fa-floppy-disk"></i> Update Item
          </button>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Featured Image</h6></div>
        <div class="card-body">
          <label for="featured_image" style="cursor:pointer">
            <div class="img-preview-box" id="img-preview" style="max-width:100%">
              <?php if ($item['featured_image']): ?>
                <img src="<?= upload_url($item['featured_image']) ?>" style="width:100%;height:100%;object-fit:cover">
              <?php else: ?>
                <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Click to upload</div>
              <?php endif; ?>
            </div>
          </label>
          <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display:none" data-preview="img-preview">
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h6>Project Info</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= e($d['category']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Tags</label>
            <input type="text" name="tags" class="form-control" value="<?= e($d['tags']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Client Name</label>
            <input type="text" name="client" class="form-control" value="<?= e($d['client']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Project URL</label>
            <input type="url" name="project_url" class="form-control" value="<?= e($d['project_url']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Completion Date</label>
            <input type="date" name="completed_date" class="form-control" value="<?= e($d['completed_date'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

  </div>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
