<?php $page_title='Suppliers'; require_once __DIR__.'/../includes/header.php'; require_perm($pdo,'suppliers','can_view'); $msg=''; $edit=null;
if(isset($_GET['delete']) && can($pdo,'suppliers','can_delete') && isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token']??'',$_GET['csrf'])){ $pdo->prepare("DELETE FROM suppliers WHERE id=?")->execute([(int)$_GET['delete']]); audit_log($pdo,'Delete','suppliers',"Deleted suppliers"); redirect('suppliers.php'); }
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $vals=[]; foreach(explode(',',"name,contact_person,phone,email,address") as $f) $vals[]=clean($_POST[$f]??''); $id=(int)($_POST['id']??0);
 if($id && can($pdo,'suppliers','can_edit')){ $set=implode('=?,',explode(',',"name,contact_person,phone,email,address")).'=?'; $pdo->prepare("UPDATE suppliers SET $set WHERE id=?")->execute([...$vals,$id]); $msg='Updated.'; }
 elseif(can($pdo,'suppliers','can_create')){ $pdo->prepare("INSERT INTO suppliers (name,contact_person,phone,email,address) VALUES (?,?,?,?,?)")->execute($vals); $msg='Created.'; }
}
$rows=$pdo->query("SELECT * FROM suppliers ORDER BY id DESC LIMIT 200")->fetchAll();
?>
<?php if($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<div class="row"><div class="col-md-4"><div class="card p-3"><h5>Add Suppliers</h5><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="0"><label class="form-label">Name</label><input name="name" class="form-control mb-2"><label class="form-label">Contact Person</label><input name="contact_person" class="form-control mb-2"><label class="form-label">Phone</label><input name="phone" class="form-control mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control mb-2"><label class="form-label">Address</label><textarea name="address" class="form-control mb-2"></textarea><button class="btn btn-primary">Create</button></form></div></div>
<div class="col-md-8"><div class="card p-3"><input id="tableSearch" class="form-control mb-3" placeholder="Search..."><div class="table-responsive"><table class="table"><thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['id']) ?></td><?php foreach(explode(',',"name,contact_person,phone,email,address") as $f): ?><td><?= e($r[$f]??'') ?></td><?php endforeach; ?><td><?php if(can($pdo,'suppliers','can_delete')): ?><a data-confirm href="?delete=<?= $r['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-danger">Delete</a><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div></div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
