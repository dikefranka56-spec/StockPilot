<?php require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/functions.php';
if(isset($_GET['logout'])){ require_once __DIR__.'/includes/auth.php'; logout_user($pdo); redirect('index.php'); }
if(!empty($_SESSION['user_id'])) redirect('modules/dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf(); $u=clean($_POST['username']??''); $p=$_POST['password']??''; require_once __DIR__.'/includes/auth.php'; if(login_user($pdo,$u,$p)) redirect('modules/dashboard.php'); else $error='Invalid username or password.'; }
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login - StockPilot</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/style.css"></head>
<body><div class="login-wrap"><div class="login-card"><h2>📦 StockPilot</h2><p class="text-muted">Inventory & Business Management</p>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?><label class="form-label">Username</label><input name="username" class="form-control mb-3" required>
<label class="form-label">Password</label><input type="password" name="password" class="form-control mb-3" required>
<button class="btn btn-primary w-100">Login</button></form>
<p class="mt-3 small text-muted">First time? Run <a href="setup.php">setup.php</a> to create an admin account. Default DB: stockpilot / root / (blank).</p></div></div></body></html>
