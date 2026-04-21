<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Quote Requests';
$active_menu = 'quotes';
require_login();

$pdo    = get_db();
$status = $_GET['status'] ?? '';
$page   = max(1, (int) ($_GET['page'] ?? 1));
$limit  = 20;

$where = ['1=1']; $params = [];
if (in_array($status, ['new','read','replied'])) { $where[] = 'status = ?'; $params[] = $status; }
$ws = implode(' AND ', $where);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM quote_requests WHERE $ws");
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();
$pg    = paginate($total, $limit, $page);

$stmt = $pdo->prepare("SELECT * FROM quote_requests WHERE $ws ORDER BY created_at DESC LIMIT {$pg['per_page']} OFFSET {$pg['offset']}");
$stmt->execute($params);
$quotes = $stmt->fetchAll();

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div><h1>Quote Requests</h1><p>Manage all quote requests submitted via the website.</p></div>
</div>

<form method="GET" class="filter-bar">
  <select name="status" class="form-control form-select" style="max-width:180px">
    <option value="">All Status</option>
    <option value="new"     <?= $status==='new'    ?'selected':'' ?>>New</option>
    <option value="read"    <?= $status==='read'   ?'selected':'' ?>>Read</option>
    <option value="replied" <?= $status==='replied'?'selected':'' ?>>Replied</option>
  </select>
  <button type="submit" class="btn-primary-admin">Filter</button>
  <?php if ($status): ?><a href="<?= APP_URL ?>/admin/quotes/index.php" class="btn-secondary-admin">Clear</a><?php endif; ?>
</form>

<div class="card">
  <div class="card-header"><h6><i class="fa-solid fa-file-invoice"></i> Quote Requests (<?= $total ?>)</h6></div>
  <div style="overflow-x:auto">
    <?php if (empty($quotes)): ?>
      <div class="empty-state"><i class="fa-solid fa-file-invoice"></i><h5>No quote requests</h5></div>
    <?php else: ?>
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Service</th><th>Message</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
          <?php foreach ($quotes as $q): ?>
          <?php if ($q['status'] === 'new'): ?>
            <?php $pdo->prepare("UPDATE quote_requests SET status='read' WHERE id=? AND status='new'")->execute([$q['id']]); ?>
          <?php endif; ?>
          <tr>
            <td><strong><?= e($q['name']) ?></strong><br><small style="color:var(--admin-muted)"><?= e($q['location'] ?? '') ?></small></td>
            <td style="font-size:12px"><a href="mailto:<?= e($q['email']) ?>"><?= e($q['email']) ?></a></td>
            <td style="font-size:12px"><?= e($q['phone'] ?? '—') ?></td>
            <td style="font-size:12px"><?= e($q['service_option'] ?? '—') ?></td>
            <td style="font-size:12px;max-width:200px"><?= e(mb_strimwidth($q['message'] ?? '', 0, 80, '…')) ?></td>
            <td>
              <select onchange="updateStatus(<?= $q['id'] ?>, this.value)" style="font-size:11px;padding:3px 6px;border:1px solid var(--admin-border);border-radius:4px">
                <?php foreach (['new','read','replied'] as $s): ?>
                  <option value="<?= $s ?>" <?= $q['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= format_date($q['created_at'],'M j, Y') ?></td>
            <td>
              <a href="<?= APP_URL ?>/admin/quotes/delete.php?id=<?= $q['id'] ?>&csrf=<?= csrf_token() ?>"
                 class="btn-danger-admin" data-confirm="Delete this quote request?">
                <i class="fa-solid fa-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<script>
function updateStatus(id, status) {
  fetch('<?= APP_URL ?>/admin/quotes/update_status.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${id}&status=${status}&csrf=<?= csrf_token() ?>`
  });
}
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
