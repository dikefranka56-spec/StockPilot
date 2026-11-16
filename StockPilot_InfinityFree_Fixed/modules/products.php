<?php $page_title='Products'; require_once __DIR__.'/../includes/header.php'; require_perm($pdo,'products','can_view'); $msg='';
$cats=$pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
if(isset($_GET['delete']) && can($pdo,'products','can_delete') && isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token']??'',$_GET['csrf'])){ $pdo->prepare("DELETE FROM products WHERE id=?")->execute([(int)$_GET['delete']]); audit_log($pdo,'Delete','products','Deleted product'); redirect('products.php'); }
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $sku=clean($_POST['sku']??''); $name=clean($_POST['name']??''); $cat=(int)($_POST['category_id']??0)?:null; $desc=clean($_POST['description']??''); $cost=(float)($_POST['cost_price']??0); $sell=(float)($_POST['selling_price']??0); $low=(int)($_POST['low_stock_threshold']??10); $id=(int)($_POST['id']??0);
 if($id && can($pdo,'products','can_edit')){ $pdo->prepare("UPDATE products SET sku=?,name=?,category_id=?,description=?,cost_price=?,selling_price=?,low_stock_threshold=? WHERE id=?")->execute([$sku,$name,$cat,$desc,$cost,$sell,$low,$id]); $msg='Updated.'; }
 elseif(can($pdo,'products','can_create')){ $pdo->prepare("INSERT INTO products(sku,name,category_id,description,cost_price,selling_price,low_stock_threshold) VALUES(?,?,?,?,?,?,?)")->execute([$sku,$name,$cat,$desc,$cost,$sell,$low]); $msg='Created.'; }
}
$edit=null; if(isset($_GET['edit'])){ $s=$pdo->prepare("SELECT * FROM products WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$q=clean($_GET['q']??''); $sql="SELECT p.*, c.name as cat, COALESCE(SUM(s.quantity),0) as stock FROM products p LEFT JOIN categories c ON c.id=p.category_id LEFT JOIN stock s ON s.product_id=p.id ".($q?"WHERE p.name LIKE ? OR p.sku LIKE ? ":"")." GROUP BY p.id ORDER BY p.id DESC"; $st=$pdo->prepare($sql); $st->execute($q?["%$q%","%$q%"]:[]); $rows=$st->fetchAll();
?>
<?php if($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<div class="row"><div class="col-md-4"><div class="card p-3"><h5><?= $edit?'Edit':'Add' ?> Product</h5><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<label class="form-label">SKU</label><input name="sku" class="form-control mb-2" value="<?= e($edit['sku']??'') ?>" required>
<label class="form-label">Name</label><input name="name" class="form-control mb-2" value="<?= e($edit['name']??'') ?>" required>
<label class="form-label">Category</label><select name="category_id" class="form-select mb-2"><option value="">-- None --</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= ($edit['category_id']??'')==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select>
<label class="form-label">Description</label><textarea name="description" class="form-control mb-2"><?= e($edit['description']??'') ?></textarea>
<label class="form-label">Cost Price</label><input type="number" step="0.01" name="cost_price" class="form-control mb-2" value="<?= e($edit['cost_price']??0) ?>">
<label class="form-label">Selling Price</label><input type="number" step="0.01" name="selling_price" class="form-control mb-2" value="<?= e($edit['selling_price']??0) ?>">
<label class="form-label">Low Stock Threshold</label><input type="number" name="low_stock_threshold" class="form-control mb-2" value="<?= e($edit['low_stock_threshold']??10) ?>">
<button class="btn btn-primary"><?= $edit?'Update':'Create' ?></button></form></div></div>
<div class="col-md-8"><div class="card p-3"><form class="d-flex gap-2 mb-3"><input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Search products..."><button class="btn btn-secondary">Search</button></form><div class="table-responsive"><table class="table"><thead><tr><th>SKU</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['sku']) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['cat']??'') ?></td><td><?= money($r['selling_price']) ?></td><td><?= (int)$r['stock'] ?> <?= $r['stock']<=$r['low_stock_threshold']?'<span class="badge bg-danger">Low</span>':'' ?></td><td><a href="?edit=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Edit</a> <?php if(can($pdo,'products','can_delete')): ?><a data-confirm href="?delete=<?= $r['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-danger">Delete</a><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></div></div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
