<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Portfolio';
$active_menu = 'portfolio';
require_login();

$pdo    = get_db();
$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$page   = max(1, (int) ($_GET['page'] ?? 1));
$limit  = 15;

$where  = ['1=1'];
$params = [];
if ($search) { $where[] = 'title LIKE ?'; $params[] = "%$search%"; }
if (in_array($status, ['published','draft'])) { $where[] = 'status = ?'; $params[] = $status; }
$where_sql = implode(' AND ', $where);

$total = (int) $pdo->prepare("SELECT COUNT(*) FROM portfolio_items WHERE $where_sql")->execute($params) ? $pdo->prepare("SELECT COUNT(*) FROM portfolio_items WHERE $where_sql")->execute($params) : 0;
$stmt  = $pdo->prepare("SELECT COUNT(*) FROM portfolio_items WHERE $where_sql");
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();

$pg     = paginate($total, $limit, $page);
$stmt   = $pdo->prepare("SELECT * FROM portfolio_items WHERE $where_sql ORDER BY sort_order ASC, created_at DESC LIMIT {$pg['per_page']} OFFSET {$pg['offset']}");
$stmt->execute($params);
$items  = $stmt->fetchAll();

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Portfolio</h1>
    <p>Manage your portfolio works and case studies.</p>
  </div>
  <?php if (has_role('super_admin','admin','editor')): ?>
  <a href="<?= APP_URL ?>/admin/portfolio/create.php" class="btn-primary-admin">
    <i class="fa-solid fa-plus"></i> Add Item
  </a>
  <?php endif; ?>
</div>

<!-- Filter Bar -->
<form method="GET" class="filter-bar">
  <input type="text" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search portfolio...">
  <select name="status" class="form-control form-select" style="max-width:160px">
    <option value="">All Status</option>
    <option value="published" <?= $status==='published'?'selected':'' ?>>Published</option>
    <option value="draft"     <?= $status==='draft'    ?'selected':'' ?>>Draft</option>
  </select>
  <button type="submit" class="btn-primary-admin"><i class="fa-solid fa-search"></i> Search</button>
  <?php if ($search || $status): ?>
    <a href="<?= APP_URL ?>/admin/portfolio/index.php" class="btn-secondary-admin">Clear</a>
  <?php endif; ?>
</form>

<div class="card">
  <div class="card-header">
    <h6><i class="fa-solid fa-briefcase"></i> Portfolio Items (<?= $total ?>)</h6>
  </div>
  <div style="overflow-x:auto">
    <?php if (empty($items)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-briefcase"></i>
        <h5>No portfolio items found</h5>
        <p>Start by adding your first portfolio work.</p>
        <a href="<?= APP_URL ?>/admin/portfolio/create.php" class="btn-primary-admin">
          <i class="fa-solid fa-plus"></i> Add Item
        </a>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width:50px"></th>
            <th>Title</th>
            <th>Category</th>
            <th>Client</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
          <tr>
            <td>
              <?php if ($item['featured_image']): ?>
                <img src="<?= upload_url($item['featured_image']) ?>" class="thumb-sm" alt="">
              <?php else: ?>
                <div class="thumb-sm" style="background:var(--admin-bg);display:flex;align-items:center;justify-content:center;color:var(--admin-muted)">
                  <i class="fa-solid fa-image"></i>
                </div>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= e($item['title']) ?></strong>
              <?php if ($item['description']): ?>
                <div style="font-size:12px;color:var(--admin-muted)"><?= e(mb_strimwidth($item['description'], 0, 60, '…')) ?></div>
              <?php endif; ?>
            </td>
            <td style="font-size:12px"><?= e($item['category'] ?? '—') ?></td>
            <td style="font-size:12px"><?= e($item['client'] ?? '—') ?></td>
            <td><span class="badge-status <?= e($item['status']) ?>"><?= e($item['status']) ?></span></td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= format_date($item['created_at'], 'M j, Y') ?></td>
            <td>
              <div class="action-btns">
                <a href="<?= APP_URL ?>/admin/portfolio/edit.php?id=<?= $item['id'] ?>" class="btn-edit-admin">
                  <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="<?= APP_URL ?>/admin/portfolio/delete.php?id=<?= $item['id'] ?>&csrf=<?= csrf_token() ?>"
                   class="btn-danger-admin"
                   data-confirm="Delete &quot;<?= e($item['title']) ?>&quot;? This cannot be undone.">
                  <i class="fa-solid fa-trash"></i> Delete
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
    <span style="font-size:12px;color:var(--admin-muted)">
      Showing <?= $pg['offset']+1 ?>–<?= min($pg['offset']+$pg['per_page'],$total) ?> of <?= $total ?>
    </span>
    <ul class="pagination">
      <li class="<?= $pg['page']<=1 ? 'disabled':'' ?>">
        <a href="?page=<?= $pg['page']-1 ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">&laquo;</a>
      </li>
      <?php for ($i=1;$i<=$pg['pages'];$i++): ?>
      <li class="<?= $i===$pg['page']?'active':'' ?>">
        <?php if ($i===$pg['page']): ?><span><?= $i ?></span>
        <?php else: ?><a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
        <?php endif; ?>
      </li>
      <?php endfor; ?>
      <li class="<?= $pg['page']>=$pg['pages'] ? 'disabled':'' ?>">
        <a href="?page=<?= $pg['page']+1 ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">&raquo;</a>
      </li>
    </ul>
  </div>
  <?php endif; ?>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
