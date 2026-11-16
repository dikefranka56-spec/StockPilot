<?php require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/functions.php';
$exists = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role_id=1")->fetchColumn();
$msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $name=clean($_POST['full_name']??''); $user=clean($_POST['username']??''); $email=clean($_POST['email']??''); $pass=$_POST['password']??'';
 if(!$name||!$user||!$email||strlen($pass)<6) $err='All fields required, password at least 6 characters.';
 else { try{ $st=$pdo->prepare("INSERT INTO users(full_name,username,email,password,role_id) VALUES(?,?,?,?,1)"); $st->execute([$name,$user,$email,password_hash($pass,PASSWORD_DEFAULT)]); $msg='Admin created! You can now <a href="index.php">login</a>. For security, delete or rename setup.php.'; }catch(Exception $e){ $err='Could not create admin: '.e($e->getMessage()); } }
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Setup - StockPilot</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/style.css"></head>
<body><div class="login-wrap"><div class="login-card"><h2>Setup StockPilot</h2><p class="text-muted">Create the first Administrator account.</p>
<?php if($exists): ?><div class="alert alert-warning">An admin already exists. Delete setup.php for security.</div><?php endif; ?>
<?php if($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
<?php if(!$msg): ?><form method="post"><?= csrf_field() ?><label class="form-label">Full Name</label><input name="full_name" class="form-control mb-2" required><label class="form-label">Username</label><input name="username" class="form-control mb-2" required><label class="form-label">Email</label><input type="email" name="email" class="form-control mb-2" required><label class="form-label">Password</label><input type="password" name="password" class="form-control mb-3" required><button class="btn btn-primary w-100">Create Admin</button></form><?php endif; ?>
<p class="mt-3 small"><a href="index.php">Back to Login</a> • After setup, delete or rename <code>setup.php</code>.</p></div></div></body></html>
