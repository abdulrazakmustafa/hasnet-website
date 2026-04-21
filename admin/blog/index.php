<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Blog Posts';
$active_menu = 'blog';
require_login();

$pdo    = get_db();
$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$page   = max(1, (int) ($_GET['page'] ?? 1));
$limit  = 15;

$where = ['1=1']; $params = [];
if ($search) { $where[] = 'title LIKE ?'; $params[] = "%$search%"; }
if (in_array($status, ['published','draft'])) { $where[] = 'status = ?'; $params[] = $status; }
$ws = implode(' AND ', $where);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE $ws");
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();
$pg    = paginate($total, $limit, $page);

$stmt = $pdo->prepare("SELECT bp.*, u.name AS author FROM blog_posts bp LEFT JOIN users u ON u.id = bp.created_by WHERE $ws ORDER BY bp.created_at DESC LIMIT {$pg['per_page']} OFFSET {$pg['offset']}");
$stmt->execute($params);
$posts = $stmt->fetchAll();

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Blog Posts</h1>
    <p>Manage all blog posts and articles.</p>
  </div>
  <?php if (has_role('super_admin','admin','editor')): ?>
  <a href="<?= APP_URL ?>/admin/blog/create.php" class="btn-primary-admin">
    <i class="fa-solid fa-plus"></i> New Post
  </a>
  <?php endif; ?>
</div>

<form method="GET" class="filter-bar">
  <input type="text" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search posts...">
  <select name="status" class="form-control form-select" style="max-width:160px">
    <option value="">All Status</option>
    <option value="published" <?= $status==='published'?'selected':'' ?>>Published</option>
    <option value="draft"     <?= $status==='draft'    ?'selected':'' ?>>Draft</option>
  </select>
  <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-search"></i> Search</button>
  <?php if ($search || $status): ?><a href="<?= APP_URL ?>/admin/blog/index.php" class="btn-secondary-admin">Clear</a><?php endif; ?>
</form>

<div class="card">
  <div class="card-header"><h6><i class="fa-solid fa-newspaper"></i> Posts (<?= $total ?>)</h6></div>
  <div style="overflow-x:auto">
    <?php if (empty($posts)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-newspaper"></i>
        <h5>No posts found</h5>
        <p>Start writing your first blog post.</p>
        <a href="<?= APP_URL ?>/admin/blog/create.php" class="btn-primary-admin"><i class="fa-solid fa-plus"></i> New Post</a>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead><tr><th style="width:50px"></th><th>Title</th><th>Category</th><th>Author</th><th>Views</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($posts as $post): ?>
          <tr>
            <td>
              <?php if ($post['featured_image']): ?>
                <img src="<?= upload_url($post['featured_image']) ?>" class="thumb-sm" alt="">
              <?php else: ?>
                <div class="thumb-sm" style="background:var(--admin-bg);display:flex;align-items:center;justify-content:center;color:var(--admin-muted)"><i class="fa-solid fa-image"></i></div>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= e($post['title']) ?></strong>
              <?php if ($post['excerpt']): ?>
                <div style="font-size:12px;color:var(--admin-muted)"><?= e(mb_strimwidth($post['excerpt'],0,60,'…')) ?></div>
              <?php endif; ?>
            </td>
            <td style="font-size:12px"><?= e($post['category'] ?? '—') ?></td>
            <td style="font-size:12px"><?= e($post['author'] ?? '—') ?></td>
            <td style="font-size:12px"><?= number_format($post['views']) ?></td>
            <td><span class="badge-status <?= e($post['status']) ?>"><?= e($post['status']) ?></span></td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= format_date($post['created_at'],'M j, Y') ?></td>
            <td>
              <div class="action-btns">
                <a href="<?= APP_URL ?>/admin/blog/edit.php?id=<?= $post['id'] ?>" class="btn-edit-admin"><i class="fa-solid fa-pen"></i> Edit</a>
                <a href="<?= APP_URL ?>/admin/blog/delete.php?id=<?= $post['id'] ?>&csrf=<?= csrf_token() ?>"
                   class="btn-danger-admin"
                   data-confirm="Delete this post? This cannot be undone.">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <?php if ($pg['pages'] > 1): ?>
  <div class="card-footer" style="display:flex;justify-content:space-between;align-items:center">
    <span style="font-size:12px;color:var(--admin-muted)">Showing <?= $pg['offset']+1 ?>–<?= min($pg['offset']+$pg['per_page'],$total) ?> of <?= $total ?></span>
    <ul class="pagination">
      <li class="<?= $pg['page']<=1?'disabled':'' ?>"><a href="?page=<?= $pg['page']-1 ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">&laquo;</a></li>
      <?php for($i=1;$i<=$pg['pages'];$i++): ?>
      <li class="<?= $i===$pg['page']?'active':'' ?>"><?php if($i===$pg['page']):?><span><?=$i?></span><?php else:?><a href="?page=<?=$i?>&q=<?=urlencode($search)?>&status=<?=urlencode($status)?>"><?=$i?></a><?php endif;?></li>
      <?php endfor; ?>
      <li class="<?= $pg['page']>=$pg['pages']?'disabled':'' ?>"><a href="?page=<?= $pg['page']+1 ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">&raquo;</a></li>
    </ul>
  </div>
  <?php endif; ?>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
