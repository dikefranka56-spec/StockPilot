<?php require_once __DIR__.'/auth.php'; require_login(); $user=current_user(); $unread=0; try{ $unread=(int)$pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read=0")->fetchColumn(); }catch(Exception $e){} ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($page_title??'StockPilot') ?> - StockPilot</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/style.css"></head>
<body><div class="app"><aside class="sidebar"><div class="brand">📦 StockPilot</div><div class="userbox"><?= e($user['name']) ?><small><?= e($user['role']) ?></small></div><nav>
<a href="<?= e(APP_URL) ?>/modules/dashboard.php">🏠 Dashboard</a>
<?php if(can($pdo,'products')): ?><a href="<?= e(APP_URL) ?>/modules/products.php">📦 Products</a><?php endif; ?>
<?php if(can($pdo,'categories')): ?><a href="<?= e(APP_URL) ?>/modules/categories.php">🏷️ Categories</a><?php endif; ?>
<?php if(can($pdo,'warehouses')): ?><a href="<?= e(APP_URL) ?>/modules/warehouses.php">🏬 Warehouses</a><?php endif; ?>
<?php if(can($pdo,'stock')): ?><a href="<?= e(APP_URL) ?>/modules/stock.php">📊 Stock</a><?php endif; ?>
<?php if(can($pdo,'transfers')): ?><a href="<?= e(APP_URL) ?>/modules/transfers.php">🔄 Transfers</a><?php endif; ?>
<?php if(can($pdo,'suppliers')): ?><a href="<?= e(APP_URL) ?>/modules/suppliers.php">🚚 Suppliers</a><?php endif; ?>
<?php if(can($pdo,'purchases')): ?><a href="<?= e(APP_URL) ?>/modules/purchases.php">🧾 Purchases</a><?php endif; ?>
<?php if(can($pdo,'customers')): ?><a href="<?= e(APP_URL) ?>/modules/customers.php">👥 Customers</a><?php endif; ?>
<?php if(can($pdo,'sales')): ?><a href="<?= e(APP_URL) ?>/modules/sales.php">💰 Sales</a><?php endif; ?>
<?php if(can($pdo,'reports')): ?><a href="<?= e(APP_URL) ?>/modules/reports.php">📈 Reports</a><?php endif; ?>
<?php if(can($pdo,'notifications')): ?><a href="<?= e(APP_URL) ?>/modules/notifications.php">🔔 Notifications <?= $unread?'<span class="badge bg-danger">'.$unread.'</span>':'' ?></a><?php endif; ?>
<?php if(can($pdo,'users')): ?><a href="<?= e(APP_URL) ?>/modules/users.php">👤 Users</a><?php endif; ?>
<?php if(can($pdo,'audit_logs')): ?><a href="<?= e(APP_URL) ?>/modules/audit_logs.php">📋 Audit Logs</a><?php endif; ?>
<a href="<?= e(APP_URL) ?>/index.php?logout=1" class="logout">🚪 Logout</a></nav></aside><main class="content"><div class="topbar"><h1><?= e($page_title??'Dashboard') ?></h1><span class="pill"><?= e(date('d M Y')) ?></span></div>
