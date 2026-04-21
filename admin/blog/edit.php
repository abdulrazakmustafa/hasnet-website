<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Edit Blog Post';
$active_menu = 'blog';
require_role('super_admin', 'admin', 'editor');

$pdo  = get_db();
$id   = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    flash('error', 'Post not found.');
    header('Location: ' . APP_URL . '/admin/blog/index.php');
    exit;
}

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

        $featured_image = $post['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            try {
                $new = upload_file($_FILES['featured_image'], 'blog');
                if ($featured_image) delete_upload($featured_image);
                $featured_image = $new;
            } catch (RuntimeException $e) { $errors[] = $e->getMessage(); }
        }

        if (empty($errors)) {
            $published_at = ($status === 'published' && !$post['published_at']) ? date('Y-m-d H:i:s') : $post['published_at'];
            $stmt = $pdo->prepare('UPDATE blog_posts SET title=?,slug=?,excerpt=?,content=?,featured_image=?,category=?,tags=?,status=?,published_at=?,updated_at=NOW() WHERE id=?');
            $stmt->execute([$title,$slug,$excerpt,$content,$featured_image,$category,$tags,$status,$published_at,$id]);
            flash('success', 'Post updated.');
            header('Location: ' . APP_URL . '/admin/blog/index.php');
            exit;
        }
    }
}

$d = $_SERVER['REQUEST_METHOD'] === 'POST' ? array_merge($post, $_POST) : $post;
include dirname(__DIR__) . '/includes/header.php';
?>
<div class="page-header">
  <div><h1>Edit Blog Post</h1><p><?= e($post['title']) ?></p></div>
  <a href="<?= APP_URL ?>/admin/blog/index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Back</a>
</div>
<?php foreach ($errors as $err): ?>
  <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= e($err) ?></div>
<?php endforeach; ?>
<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div>
      <div class="card">
        <div class="card-header"><h6>Post Content</h6></div>
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
            <label class="form-label">Excerpt</label>
            <textarea name="excerpt" class="form-control" rows="3"><?= e($d['excerpt']) ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Content</label>
            <div data-quill="content-hidden" style="min-height:280px"></div>
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
              <option value="draft" <?= $d['status']==='draft'?'selected':'' ?>>Draft</option>
              <option value="published" <?= $d['status']==='published'?'selected':'' ?>>Published</option>
            </select>
          </div>
          <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center"><i class="fa-solid fa-floppy-disk"></i> Update Post</button>
        </div>
      </div>
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><h6>Featured Image</h6></div>
        <div class="card-body">
          <label for="featured_image" style="cursor:pointer">
            <div class="img-preview-box" id="img-preview" style="max-width:100%">
              <?php if ($post['featured_image']): ?>
                <img src="<?= upload_url($post['featured_image']) ?>" style="width:100%;height:100%;object-fit:cover">
              <?php else: ?>
                <div class="placeholder"><i class="fa-solid fa-cloud-arrow-up"></i>Click to upload</div>
              <?php endif; ?>
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
            <input type="text" name="category" class="form-control" value="<?= e($d['category']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Tags</label>
            <input type="text" name="tags" class="form-control" value="<?= e($d['tags']) ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
