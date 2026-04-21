<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Add Portfolio Item';
$active_menu = 'portfolio';
require_role('super_admin', 'admin', 'editor');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $title          = trim($_POST['title'] ?? '');
        $slug           = trim($_POST['slug'] ?? '');
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
        if (!$slug) $slug = slugify($title);

        // Handle image upload
        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            try {
                $featured_image = upload_file($_FILES['featured_image'], 'portfolio');
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (empty($errors)) {
            $pdo = get_db();
            // ensure unique slug
            $base = $slug; $i = 1;
            while ($pdo->prepare('SELECT id FROM portfolio_items WHERE slug = ?')->execute([$slug]) &&
                   $pdo->prepare('SELECT id FROM portfolio_items WHERE slug = ?')->execute([$slug]) &&
                   $pdo->query("SELECT id FROM portfolio_items WHERE slug = '$slug'")->fetchColumn()) {
                $slug = $base . '-' . $i++;
            }

            $stmt = $pdo->prepare('INSERT INTO portfolio_items
                (title,slug,description,content,featured_image,category,tags,client,project_url,completed_date,status,sort_order,created_by)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$title,$slug,$description,$content,$featured_image,$category,$tags,$client,$project_url,$completed_date,$status,$sort_order,$current_user['id']]);

            flash('success', 'Portfolio item created successfully.');
            header('Location: ' . APP_URL . '/admin/portfolio/index.php');
            exit;
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Add Portfolio Item</h1>
    <p>Create a new portfolio work or case study.</p>
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

    <!-- Main -->
    <div>
      <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h6>Item Details</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Title *</label>
            <input type="text" id="input-title" name="title" class="form-control" value="<?= e($_POST['title'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Slug (URL)</label>
            <input type="text" id="input-slug" name="slug" class="form-control" value="<?= e($_POST['slug'] ?? '') ?>">
            <div class="form-text">Leave blank to auto-generate from title.</div>
          </div>
          <div class="form-group">
            <label class="form-label">Short Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Brief description shown in portfolio grid..."><?= e($_POST['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Full Content</label>
            <div data-quill="content-hidden" style="min-height:200px"></div>
            <input type="hidden" id="content-hidden" name="content" value="<?= e($_POST['content'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div>
      <!-- Publish -->
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Publish</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control form-select">
              <option value="draft"     <?= ($_POST['status']??'draft')==='draft'     ?'selected':'' ?>>Draft</option>
              <option value="published" <?= ($_POST['status']??'')==='published'?'selected':'' ?>>Published</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($_POST['sort_order'] ?? '0') ?>" min="0">
          </div>
          <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center">
            <i class="fa-solid fa-floppy-disk"></i> Save Item
          </button>
        </div>
      </div>

      <!-- Featured Image -->
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Featured Image</h6></div>
        <div class="card-body">
          <label for="featured_image" style="cursor:pointer">
            <div class="img-preview-box" id="img-preview" style="max-width:100%">
              <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Click to upload image<span style="font-size:10px;opacity:.7">JPG, PNG, WebP — max 5MB</span></div>
            </div>
          </label>
          <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display:none" data-preview="img-preview">
        </div>
      </div>

      <!-- Meta -->
      <div class="card">
        <div class="card-header"><h6>Project Info</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" placeholder="e.g. Web Design" value="<?= e($_POST['category'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Tags</label>
            <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3" value="<?= e($_POST['tags'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Client Name</label>
            <input type="text" name="client" class="form-control" value="<?= e($_POST['client'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Project URL</label>
            <input type="url" name="project_url" class="form-control" placeholder="https://" value="<?= e($_POST['project_url'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Completion Date</label>
            <input type="date" name="completed_date" class="form-control" value="<?= e($_POST['completed_date'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>

  </div>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
