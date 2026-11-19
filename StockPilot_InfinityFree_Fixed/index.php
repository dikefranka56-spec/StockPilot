<?php
require_once __DIR__ . '/config.php';

if (isset($_GET['logout'])) {
    require_once __DIR__ . '/includes/db.php';
    require_once __DIR__ . '/includes/functions.php';
    require_once __DIR__ . '/includes/auth.php';

    logout_user($pdo);
    redirect('index.php');
}

$loggedIn = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>StockPilot | Smart Inventory Management</title>
<meta name="description" content="Manage inventory, sales, purchases, warehouses and business operations with StockPilot.">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --navy:#10233f;
    --blue:#2864e8;
    --light:#f4f7fc;
    --muted:#68778d;
    --green:#19a879;
}
*{box-sizing:border-box}
body{margin:0;font-family:'DM Sans',sans-serif;color:var(--navy);background:#fff}
a{text-decoration:none}
.navbar{padding:18px 0;background:#fff;border-bottom:1px solid #edf0f5}
.brand{font-family:'Manrope',sans-serif;font-weight:800;font-size:25px;color:var(--navy)}
.brand-icon{display:inline-flex;width:39px;height:39px;border-radius:12px;background:var(--blue);color:#fff;align-items:center;justify-content:center;margin-right:9px}
.nav-link{font-weight:600;color:#53627a!important;margin:0 10px}
.btn-main{background:var(--blue);color:#fff;padding:12px 23px;border-radius:9px;font-weight:700;border:0}
.btn-main:hover{background:#174ec8;color:#fff}
.btn-outline{border:1px solid #d9e0eb;color:var(--navy);padding:11px 22px;border-radius:9px;font-weight:700}
.btn-outline:hover{background:#f0f4fc}
.hero{background:linear-gradient(115deg,#f3f7ff 0%,#fff 60%,#eef8ff 100%);padding:90px 0 85px;overflow:hidden}
.eyebrow{display:inline-block;background:#e5edff;color:var(--blue);padding:8px 13px;border-radius:30px;font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase}
.hero h1{font-family:'Manrope',sans-serif;font-size:clamp(39px,5vw,61px);font-weight:800;line-height:1.12;letter-spacing:-2px;margin:22px 0}
.hero h1 span{color:var(--blue)}
.hero p{font-size:17px;line-height:1.8;color:var(--muted);max-width:540px}
.hero-actions{display:flex;gap:13px;flex-wrap:wrap;margin-top:29px}
.hero-note{font-size:13px!important;margin-top:19px}
.dashboard-preview{background:#fff;border:1px solid #e4eaf3;border-radius:17px;padding:20px;box-shadow:0 25px 65px #18355b1a;transform:rotate(1deg)}
.preview-top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #edf0f5;padding-bottom:15px}
.preview-title{font-weight:800}
.preview-tag{font-size:11px;color:var(--green);background:#e7f8f1;padding:6px 9px;border-radius:20px}
.metric{background:#f7f9fd;border-radius:12px;padding:15px;margin-top:14px}
.metric small{color:var(--muted);font-size:11px}
.metric strong{display:block;font-size:22px;margin-top:5px}
.preview-bars{display:flex;align-items:end;gap:9px;height:95px;padding:12px 5px 0}
.preview-bars span{flex:1;background:linear-gradient(#5e91ff,#2864e8);border-radius:5px 5px 0 0}
.section{padding:82px 0}
.section-heading{text-align:center;max-width:660px;margin:0 auto 45px}
.section-heading h2{font-family:'Manrope',sans-serif;font-weight:800;font-size:35px;letter-spacing:-1px}
.section-heading p{color:var(--muted);line-height:1.8}
.feature-card{height:100%;padding:27px;border:1px solid #e9edf4;border-radius:15px;background:#fff;transition:.2s}
.feature-card:hover{transform:translateY(-5px);box-shadow:0 15px 35px #10233f12}
.feature-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:#eaf0ff;border-radius:13px;font-size:23px;margin-bottom:20px}
.feature-card h3{font-size:18px;font-weight:800}
.feature-card p{font-size:14px;color:var(--muted);line-height:1.8;margin:0}
.workflow{background:var(--light)}
.workflow-item{padding:22px;background:#fff;border-radius:13px;height:100%;border:1px solid #e8edf5}
.workflow-item strong{display:block;margin:12px 0 7px}
.workflow-item p{color:var(--muted);font-size:14px;line-height:1.7;margin:0}
.cta{background:linear-gradient(120deg,#174fc4,#3479f1);color:#fff;border-radius:20px;padding:55px 35px;text-align:center}
.cta p{color:#e1eaff}
footer{padding:28px 0;border-top:1px solid #edf0f5;color:var(--muted);font-size:13px}
@media(max-width:767px){
.hero{padding:65px 0}
.hero h1{letter-spacing:-1.5px}
.dashboard-preview{margin-top:40px;transform:none}
.section{padding:62px 0}
.section-heading h2{font-size:29px}
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
<div class="container">
<a class="brand" href="index.php"><span class="brand-icon">S</span>StockPilot</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="mainNav">
<div class="navbar-nav ms-auto align-items-lg-center">
<a class="nav-link" href="#features">Features</a>
<a class="nav-link" href="#workflow">How It Works</a>
<a class="nav-link" href="#about">About</a>
<?php if ($loggedIn): ?>
<a class="btn btn-main ms-lg-3 mt-2 mt-lg-0" href="modules/dashboard.php">Open Dashboard</a>
<a class="nav-link mt-2 mt-lg-0" href="index.php?logout=1">Logout</a>
<?php else: ?>
<a class="nav-link mt-2 mt-lg-0" href="login.php">Login</a>
<a class="btn btn-main ms-lg-2 mt-2 mt-lg-0" href="register.php">Create Account</a>
<?php endif; ?>
</div>
</div>
</div>
</nav>

<section class="hero">
<div class="container">
<div class="row align-items-center g-5">
<div class="col-lg-6">
<span class="eyebrow">Inventory management, made smarter</span>
<h1>Take control of your inventory. <span>Grow your business.</span></h1>
<p>StockPilot brings your products, stock levels, purchases, sales and business operations together in one organised platform. Make better decisions with a clearer view of your business.</p>
<div class="hero-actions">
<?php if ($loggedIn): ?>
<a href="modules/dashboard.php" class="btn btn-main">Go to Dashboard →</a>
<?php else: ?>
<a href="register.php" class="btn btn-main">Get Started →</a>
<a href="login.php" class="btn btn-outline">Sign In</a>
<?php endif; ?>
</div>
<p class="hero-note">One platform. Better visibility. Smarter operations.</p>
</div>
<div class="col-lg-6">
<div class="dashboard-preview">
<div class="preview-top">
<div class="preview-title">Business Overview</div>
<span class="preview-tag">● Management overview</span>
</div>
<div class="row g-2">
<div class="col-6"><div class="metric"><small>Inventory Value</small><strong>₦2.45M</strong></div></div>
<div class="col-6"><div class="metric"><small>Total Products</small><strong>1,280</strong></div></div>
<div class="col-6"><div class="metric"><small>Sales Overview</small><strong>₦845K</strong></div></div>
<div class="col-6"><div class="metric"><small>Stock Alerts</small><strong>08</strong></div></div>
</div>
<div class="d-flex justify-content-between mt-4">
<strong style="font-size:13px">Activity overview</strong>
<small class="text-secondary">Illustrative preview</small>
</div>
<div class="preview-bars">
<span style="height:40%"></span><span style="height:65%"></span>
<span style="height:50%"></span><span style="height:85%"></span>
<span style="height:60%"></span><span style="height:95%"></span>
<span style="height:73%"></span><span style="height:100%"></span>
</div>
</div>
</div>
</div>
</div>
</section>

<section class="section" id="features">
<div class="container">
<div class="section-heading">
<span class="eyebrow">Platform capabilities</span>
<h2>Everything you need to manage your stock</h2>
<p>Keep everyday inventory operations organised with tools designed to give your team better visibility and control.</p>
</div>
<div class="row g-4">
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">📦</div><h3>Product Management</h3><p>Organise products, categories, stock information and product records in one place.</p></div></div>
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🏬</div><h3>Warehouse Tracking</h3><p>Monitor inventory across warehouses and keep stock information organised.</p></div></div>
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🛒</div><h3>Purchases & Suppliers</h3><p>Manage purchasing activities and maintain useful supplier records.</p></div></div>
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">💳</div><h3>Sales & Customers</h3><p>Record sales activity and organise customer information for easier management.</p></div></div>
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">📊</div><h3>Reports & Insights</h3><p>Access business reports that help you understand inventory and operational activity.</p></div></div>
<div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🔔</div><h3>Stock Alerts</h3><p>Receive low-stock notifications and keep track of products that need attention.</p></div></div>
</div>
</div>
</section>

<section class="section workflow" id="workflow">
<div class="container">
<div class="section-heading">
<span class="eyebrow">Simple workflow</span>
<h2>A clearer way to run your operations</h2>
<p>Bring key inventory activities into one organised workspace.</p>
</div>
<div class="row g-4">
<div class="col-md-4"><div class="workflow-item"><span class="eyebrow">01</span><strong>Set up your workspace</strong><p>Create your account and organise your business products, categories and warehouses.</p></div></div>
<div class="col-md-4"><div class="workflow-item"><span class="eyebrow">02</span><strong>Manage daily activities</strong><p>Record purchases, sales, stock changes and other inventory operations.</p></div></div>
<div class="col-md-4"><div class="workflow-item"><span class="eyebrow">03</span><strong>Monitor your business</strong><p>Review reports, stock levels and notifications to stay informed.</p></div></div>
</div>
</div>
</section>

<section class="section" id="about">
<div class="container">
<div class="cta">
<h2 class="fw-bold mb-3">Bring your inventory under control</h2>
<p class="mb-4">Start organising your products and business operations with StockPilot.</p>
<?php if ($loggedIn): ?>
<a href="modules/dashboard.php" class="btn btn-light fw-bold px-4 py-3">Open Your Dashboard</a>
<?php else: ?>
<a href="register.php" class="btn btn-light fw-bold px-4 py-3">Create Your Account</a>
<a href="login.php" class="btn btn-outline-light fw-bold px-4 py-3 ms-2">Login</a>
<?php endif; ?>
</div>
</div>
</section>

<footer>
<div class="container d-flex flex-wrap justify-content-between gap-2">
<span>© <?= date('Y') ?> StockPilot. Inventory & Business Management.</span>
<span>Organise better. Operate smarter.</span>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
