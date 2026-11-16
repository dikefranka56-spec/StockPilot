<?php $page_title='Categories'; require_once __DIR__.'/../includes/header.php'; require_perm($pdo,'categories','can_view'); $msg=''; $edit=null;
if(isset($_GET['delete']) && can($pdo,'categories','can_delete') && isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token']??'',$_GET['csrf'])){ $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$_GET['delete']]); audit_log($pdo,'Delete','categories',"Deleted categories"); redirect('categories.php'); }
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $vals=[]; foreach(explode(',',"name,description") as $f) $vals[]=clean($_POST[$f]??''); $id=(int)($_POST['id']??0);
 if($id && can($pdo,'categories','can_edit')){ $set=implode('=?,',explode(',',"name,description")).'=?'; $pdo->prepare("UPDATE categories SET $set WHERE id=?")->execute([...$vals,$id]); $msg='Updated.'; }
 elseif(can($pdo,'categories','can_create')){ $pdo->prepare("INSERT INTO categories (name,description) VALUES (?,?)")->execute($vals); $msg='Created.'; }
}
$rows=$pdo->query("SELECT * FROM categories ORDER BY id DESC LIMIT 200")->fetchAll();
?>
<?php if($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<div class="row"><div class="col-md-4"><div class="card p-3"><h5>Add Categories</h5><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="0"><label class="form-label">Name</label><input name="name" class="form-control mb-2"><label class="form-label">Description</label><textarea name="description" class="form-control mb-2"></textarea><button class="btn btn-primary">Create</button></form></div></div>
<div class="col-md-8"><div class="card p-3"><input id="tableSearch" class="form-control mb-3" placeholder="Search..."><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['id']) ?></td><?php foreach(explode(',',"name,description") as $f): ?><td><?= e($r[$f]??'') ?></td><?php endforeach; ?><td><?php if(can($pdo,'categories','can_delete')): ?><a data-confirm href="?delete=<?= $r['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-danger">Delete</a><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div></div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
