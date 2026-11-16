<?php $page_title='Audit Logs'; require_once __DIR__.'/../includes/header.php'; require_perm($pdo,'audit_logs','can_view');
$rows=$pdo->query("SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 300")->fetchAll();
?>
<div class="card p-3"><input id="tableSearch" class="form-control mb-3" placeholder="Search logs..."><div class="table-responsive"><table class="table"><thead><tr><th>User</th><th>Action</th><th>Module</th><th>Details</th><th>IP</th><th>Date</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['username']??'System') ?></td><td><?= e($r['action']) ?></td><td><?= e($r['module']??'') ?></td><td><?= e($r['details']??'') ?></td><td><?= e($r['ip_address']??'') ?></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
