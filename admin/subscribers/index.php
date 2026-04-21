<?php
require_once dirname(__DIR__, 2) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title  = 'Subscribers';
$active_menu = 'subscribers';
require_login();

$pdo  = get_db();
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = 20;

$stmt = $pdo->query('SELECT COUNT(*) FROM subscribers');
$total = (int) $stmt->fetchColumn();
$pg    = paginate($total, $limit, $page);

$subscribers = $pdo->query("SELECT * FROM subscribers ORDER BY subscribed_at DESC LIMIT {$pg['per_page']} OFFSET {$pg['offset']}")->fetchAll();

// All emails for copy
$all_emails    = $pdo->query("SELECT email FROM subscribers WHERE email IS NOT NULL AND email != '' ORDER BY subscribed_at DESC")->fetchAll(PDO::FETCH_COLUMN);
$all_whatsapps = $pdo->query("SELECT whatsapp FROM subscribers WHERE whatsapp IS NOT NULL AND whatsapp != '' ORDER BY subscribed_at DESC")->fetchAll(PDO::FETCH_COLUMN);

include dirname(__DIR__) . '/includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Subscribers</h1>
    <p><?= $total ?> total subscribers collected via subscribe popup.</p>
  </div>
</div>

<!-- Copy Tools -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">

  <div class="card">
    <div class="card-header">
      <h6><i class="fa-solid fa-envelope"></i> All Emails (<?= count($all_emails) ?>)</h6>
      <button class="btn-primary-admin" style="padding:5px 12px;font-size:12px" data-copy="all-emails-box">
        <i class="fa-solid fa-copy"></i> Copy All
      </button>
    </div>
    <div class="card-body">
      <textarea id="all-emails-box" class="form-control" rows="5" readonly style="font-size:12px;font-family:monospace"><?= e(implode("\n", $all_emails)) ?></textarea>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h6><i class="fa-brands fa-whatsapp"></i> All WhatsApp (<?= count($all_whatsapps) ?>)</h6>
      <button class="btn-primary-admin" style="padding:5px 12px;font-size:12px" data-copy="all-wa-box">
        <i class="fa-solid fa-copy"></i> Copy All
      </button>
    </div>
    <div class="card-body">
      <textarea id="all-wa-box" class="form-control" rows="5" readonly style="font-size:12px;font-family:monospace"><?= e(implode("\n", $all_whatsapps)) ?></textarea>
    </div>
  </div>

</div>

<!-- Subscribers Table -->
<div class="card">
  <div class="card-header">
    <h6><i class="fa-solid fa-list"></i> Subscriber List</h6>
    <a href="<?= APP_URL ?>/admin/subscribers/export.php" class="btn-secondary-admin" style="padding:5px 12px;font-size:12px">
      <i class="fa-solid fa-file-csv"></i> Export CSV
    </a>
  </div>
  <div style="overflow-x:auto">
    <?php if (empty($subscribers)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-envelope-open-text"></i>
        <h5>No subscribers yet</h5>
        <p>Subscribers collected via the popup will appear here.</p>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead><tr><th>#</th><th>Email</th><th>WhatsApp</th><th>Source</th><th>Subscribed</th><th>Action</th></tr></thead>
        <tbody>
          <?php foreach ($subscribers as $i => $sub): ?>
          <tr>
            <td style="color:var(--admin-muted);font-size:12px"><?= $pg['offset'] + $i + 1 ?></td>
            <td><?= $sub['email'] ? e($sub['email']) : '<span style="color:var(--admin-muted)">—</span>' ?></td>
            <td>
              <?php if ($sub['whatsapp']): ?>
                <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','',$sub['whatsapp'])) ?>" target="_blank" style="color:#25d366">
                  <i class="fa-brands fa-whatsapp"></i> <?= e($sub['whatsapp']) ?>
                </a>
              <?php else: ?><span style="color:var(--admin-muted)">—</span><?php endif; ?>
            </td>
            <td style="font-size:12px"><?= e($sub['source'] ?? 'popup') ?></td>
            <td style="font-size:12px;color:var(--admin-muted)"><?= format_date($sub['subscribed_at'], 'M j, Y g:i A') ?></td>
            <td>
              <a href="<?= APP_URL ?>/admin/subscribers/delete.php?id=<?= $sub['id'] ?>&csrf=<?= csrf_token() ?>"
                 class="btn-danger-admin"
                 data-confirm="Remove this subscriber?">
                <i class="fa-solid fa-trash"></i>
              </a>
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
      <li class="<?= $pg['page']<=1?'disabled':'' ?>"><a href="?page=<?= $pg['page']-1 ?>">&laquo;</a></li>
      <?php for($i=1;$i<=$pg['pages'];$i++): ?>
        <li class="<?= $i===$pg['page']?'active':'' ?>"><?php if($i===$pg['page']):?><span><?=$i?></span><?php else:?><a href="?page=<?=$i?>"><?=$i?></a><?php endif;?></li>
      <?php endfor; ?>
      <li class="<?= $pg['page']>=$pg['pages']?'disabled':'' ?>"><a href="?page=<?= $pg['page']+1 ?>">&raquo;</a></li>
    </ul>
  </div>
  <?php endif; ?>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
