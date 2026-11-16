# StockPilot - Inventory & Business Management System
## Requirements
- XAMPP (PHP 8+, Apache, MySQL / MariaDB)
- Windows / Mac / Linux
## Installation (Beginner Steps)
1. Install and open XAMPP.
2. Start **Apache** and **MySQL** in XAMPP Control Panel.
3. Extract the `StockPilot` folder into `C:\xampp\htdocs\` (so you have `C:\xampp\htdocs\StockPilot\`).
4. Open phpMyAdmin at http://localhost/phpmyadmin
5. Create database `stockpilot` (or just import - the SQL creates it). Click Import > Choose File > select `database.sql` > Go.
6. Check `config.php` - defaults are: Host `localhost`, Database `stockpilot`, Username `root`, Password `` (blank). Change if yours differ.
7. Open http://localhost/StockPilot/setup.php
8. Create the first Administrator account.
9. Log in at http://localhost/StockPilot/
10. **Security:** After creating the admin, delete or rename `setup.php` (e.g. `setup.php.disabled`) so no one else can create an admin.
## Default Data
- Sample categories, warehouses, suppliers, customers, products and stock are included in `database.sql`.
- Roles: Administrator (full), Manager (products/sales/purchases/reports), Warehouse Staff (stock/transfers).
## Modules
Dashboard, Products, Categories, Warehouses, Stock, Transfers, Suppliers, Purchases, Customers, Sales, Reports, Notifications, Users, Audit Logs.
## Features
- PDO prepared statements, CSRF protection, password hashing, role permissions, audit logs, low-stock alerts.
- Purchases add stock, Sales deduct stock, Transfers move stock - all inside DB transactions.
## Troubleshooting
- "Database connection failed": ensure MySQL is running, database.sql imported, config.php correct.
- Blank page: check `C:\xampp\apache\logs\error.log` and PHP version >= 8.0.
