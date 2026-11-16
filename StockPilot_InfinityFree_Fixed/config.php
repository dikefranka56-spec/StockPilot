<?php
// StockPilot configuration.
// Database credentials below must match the values shown in InfinityFree's
// MySQL Databases section. Keep the database name exactly as provided there.
define('DB_HOST', 'localhost');
define('DB_NAME', 'stockpilot');
define('DB_USER', 'root');
define('DB_PASS', '');

// Detect the public installation path automatically.
// Supports both https://example.com/ and https://example.com/StockPilot/.
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if (preg_match('~/modules$~', $scriptDir)) {
    $appPath = dirname($scriptDir);
} else {
    $appPath = $scriptDir;
}
if ($appPath === '/' || $appPath === '.' || $appPath === '\\') {
    $appPath = '';
}
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
$scheme = $https ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('APP_URL', $scheme . '://' . $host . $appPath);
define('APP_BASE', $appPath);

define('APP_NAME', 'StockPilot');
define('BASE_PATH', __DIR__);
define('CURRENCY', '₦');
define('LOW_STOCK_THRESHOLD', 10);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
date_default_timezone_set('Africa/Lagos');
