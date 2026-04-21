<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'New Blog Post';
$active_menu = 'blog';
require_role('super_admin', 'admin', 'editor');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $title    = trim($_POST['title'] ?? '');
        $slug     = trim($_POST['slug'] ?? '') ?: slugify($title);
        $excerpt  = trim($_POST['excerpt'] ?? '');
        $content  = $_POST['content'] ?? '';
        $category = trim($_POST['category'] ?? '');
        $tags     = trim($_POST['tags'] ?? '');
        $status   = in_array($_POST['status']??'',['published','draft']) ? $_POST['status'] : 'draft';

        if (!$title) $errors[] = 'Title is required.';

        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            try { $featured_image = upload_file($_FILES['featured_image'], 'blog'); }
            catch (RuntimeException $e) { $errors[] = $e->getMessage(); }
        }

        if (empty($errors)) {
            $pdo = get_db();
            $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;
            $stmt = $pdo->prepare('INSERT INTO blog_posts (title,slug,excerpt,content,featured_image,category,tags,status,created_by,published_at) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$title,$slug,$excerpt,$content,$featured_image,$category,$tags,$status,$current_user['id'],$published_at]);
            flash('success', 'Blog post created successfully.');
            header('Location: ' . APP_URL . '/admin/blog/index.php');
            exit;
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>
<div class="page-header">
  <div><h1>New Blog Post</h1></div>
  <a href="<?= APP_URL ?>/admin/blog/index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Back</a>
</div>
<?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($err) ?></div>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div>
      <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h6>Post Content</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Title *</label>
            <input type="text" id="input-title" name="title" class="form-control" value="<?= e($_POST['title']??'') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Slug</label>
            <input type="text" id="input-slug" name="slug" class="form-control" value="<?= e($_POST['slug']??'') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Excerpt / Summary</label>
            <textarea name="excerpt" class="form-control" rows="3"><?= e($_POST['excerpt']??'') ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Content</label>
            <div data-quill="content-hidden" style="min-height:280px"></div>
            <input type="hidden" id="content-hidden" name="content" value="<?= e($_POST['content']??'') ?>">
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
              <option value="draft" <?= ($_POST['status']??'draft')==='draft'?'selected':'' ?>>Draft</option>
              <option value="published" <?= ($_POST['status']??'')==='published'?'selected':'' ?>>Published</option>
            </select>
          </div>
          <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center"><i class="fa-solid fa-floppy-disk"></i> Save Post</button>
        </div>
      </div>
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Featured Image</h6></div>
        <div class="card-body">
          <label for="featured_image" style="cursor:pointer">
            <div class="img-preview-box" id="img-preview" style="max-width:100%">
              <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Click to upload</div>
            </div>
          </label>
          <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display:none" data-preview="img-preview">
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h6>Categorization</h6></div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= e($_POST['category']??'') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Tags</label>
            <input type="text" name="tags" class="form-control" placeholder="tag1, tag2" value="<?= e($_POST['tags']??'') ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
