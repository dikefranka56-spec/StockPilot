-- StockPilot Database
-- Import this file after selecting your existing database in phpMyAdmin.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  module VARCHAR(50) NOT NULL,
  can_view TINYINT(1) DEFAULT 1,
  can_create TINYINT(1) DEFAULT 0,
  can_edit TINYINT(1) DEFAULT 0,
  can_delete TINYINT(1) DEFAULT 0,
  UNIQUE KEY uq_role_module (role_id, module),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS warehouses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(255) NULL,
  manager VARCHAR(100) NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS suppliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  contact_person VARCHAR(100) NULL,
  phone VARCHAR(30) NULL,
  email VARCHAR(120) NULL,
  address TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NULL,
  email VARCHAR(120) NULL,
  address TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(150) NOT NULL,
  category_id INT NULL,
  description TEXT NULL,
  cost_price DECIMAL(12,2) DEFAULT 0,
  selling_price DECIMAL(12,2) DEFAULT 0,
  low_stock_threshold INT DEFAULT 10,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  INDEX idx_products_name (name),
  INDEX idx_products_sku (sku)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_stock (product_id, warehouse_id),
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stock_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  movement_type ENUM('purchase','sale','transfer_in','transfer_out','adjustment','return') NOT NULL,
  quantity INT NOT NULL,
  reference VARCHAR(100) NULL,
  notes TEXT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transfers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transfer_no VARCHAR(50) NOT NULL UNIQUE,
  product_id INT NOT NULL,
  from_warehouse_id INT NOT NULL,
  to_warehouse_id INT NOT NULL,
  quantity INT NOT NULL,
  status ENUM('pending','completed','cancelled') DEFAULT 'completed',
  notes TEXT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (from_warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
  FOREIGN KEY (to_warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS purchases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  purchase_no VARCHAR(50) NOT NULL UNIQUE,
  supplier_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  total_amount DECIMAL(12,2) DEFAULT 0,
  status ENUM('received','pending','cancelled') DEFAULT 'received',
  purchase_date DATE NOT NULL,
  notes TEXT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS purchase_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  purchase_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice_no VARCHAR(50) NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  total_amount DECIMAL(12,2) DEFAULT 0,
  payment_method VARCHAR(50) DEFAULT 'Cash',
  status ENUM('completed','refunded','pending') DEFAULT 'completed',
  sale_date DATE NOT NULL,
  notes TEXT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sale_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  type VARCHAR(50) DEFAULT 'info',
  is_read TINYINT(1) DEFAULT 0,
  related_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(100) NOT NULL,
  module VARCHAR(50) NULL,
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO roles (id,name,description) VALUES (1,'Administrator','Full access'),(2,'Manager','Manage products, sales, purchases, reports'),(3,'Warehouse Staff','Stock, transfers, warehouses')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Permissions: admin all, manager most, warehouse staff stock/transfers/warehouses
INSERT INTO permissions (role_id,module,can_view,can_create,can_edit,can_delete) VALUES
(1,'dashboard',1,1,1,1),(1,'products',1,1,1,1),(1,'categories',1,1,1,1),(1,'warehouses',1,1,1,1),(1,'stock',1,1,1,1),(1,'transfers',1,1,1,1),(1,'suppliers',1,1,1,1),(1,'purchases',1,1,1,1),(1,'customers',1,1,1,1),(1,'sales',1,1,1,1),(1,'reports',1,1,1,1),(1,'notifications',1,1,1,1),(1,'users',1,1,1,1),(1,'audit_logs',1,1,1,1),
(2,'dashboard',1,0,0,0),(2,'products',1,1,1,0),(2,'categories',1,1,1,0),(2,'warehouses',1,0,0,0),(2,'stock',1,1,1,0),(2,'transfers',1,1,0,0),(2,'suppliers',1,1,1,0),(2,'purchases',1,1,0,0),(2,'customers',1,1,1,0),(2,'sales',1,1,0,0),(2,'reports',1,0,0,0),(2,'notifications',1,0,0,0),(2,'users',0,0,0,0),(2,'audit_logs',1,0,0,0),
(3,'dashboard',1,0,0,0),(3,'products',1,0,0,0),(3,'categories',1,0,0,0),(3,'warehouses',1,0,0,0),(3,'stock',1,1,1,0),(3,'transfers',1,1,0,0),(3,'suppliers',1,0,0,0),(3,'purchases',1,0,0,0),(3,'customers',1,0,0,0),(3,'sales',0,0,0,0),(3,'reports',1,0,0,0),(3,'notifications',1,0,0,0),(3,'users',0,0,0,0),(3,'audit_logs',0,0,0,0)
ON DUPLICATE KEY UPDATE can_view=VALUES(can_view);

INSERT INTO categories (name,description) VALUES ('Electronics','Electronic items'),('Groceries','Food and household'),('Clothing','Apparel'),('Accessories','Accessories')
ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO warehouses (name,location,manager) VALUES ('Main Warehouse','Lagos','Admin'),('Branch Warehouse','Benin City','Manager') ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO suppliers (name,contact_person,phone,email) VALUES ('Global Supplies Ltd','John Doe','08030000000','info@globalsupplies.com'),('Prime Distributors','Jane Smith','08030000001','sales@prime.com') ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO customers (name,phone,email) VALUES ('Walk-in Customer','0000000000','walkin@example.com'),('Retail Customer','08030000002','retail@example.com') ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO products (sku,name,category_id,cost_price,selling_price,low_stock_threshold) VALUES ('SKU-001','Wireless Mouse',1,3500,5500,10),('SKU-002','Rice 50kg',2,42000,48000,5),('SKU-003','T-Shirt',3,4000,6500,15) ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO stock (product_id,warehouse_id,quantity) VALUES (1,1,50),(2,1,20),(3,1,35),(1,2,15) ON DUPLICATE KEY UPDATE quantity=VALUES(quantity);
