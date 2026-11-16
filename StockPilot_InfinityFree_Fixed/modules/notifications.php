<?php $page_title='Notifications'; require_once __DIR__.'/../includes/header.php'; require_perm($pdo,'notifications','can_view');
if(isset($_GET['read'])){ $pdo->prepare("UPDATE notifications SET is_read=1 WHERE id=?")->execute([(int)$_GET['read']]); redirect('notifications.php'); }
if(isset($_GET['read_all'])){ $pdo->query("UPDATE notifications SET is_read=1"); redirect('notifications.php'); }
$rows=$pdo->query("SELECT * FROM notifications ORDER BY is_read ASC, id DESC LIMIT 200")->fetchAll();
?>
<div class="card p-3"><div class="d-flex justify-content-between mb-3"><h5>Notifications</h5><a href="?read_all=1" class="btn btn-sm btn-secondary">Mark All Read</a></div><ul class="list-group"><?php foreach($rows as $r): ?><li class="list-group-item d-flex justify-content-between <?= $r['is_read']?'':'fw-bold' ?>"><span><?= e($r['title']) ?> - <?= e($r['message']) ?> <small class="text-muted"><?= e($r['created_at']) ?></small></span><?php if(!$r['is_read']): ?><a href="?read=<?= $r['id'] ?>" class="btn btn-sm btn-primary">Mark Read</a><?php endif; ?></li><?php endforeach; ?><?php if(!$rows): ?><li class="list-group-item">No notifications.</li><?php endif; ?></ul></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
