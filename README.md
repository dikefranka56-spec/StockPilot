# 🚀 StockPilot
### Smart Inventory & Business Management System

**Take control of your inventory. Simplify your operations. Manage your business with confidence.**

StockPilot is a web-based inventory and business management application designed to help businesses organize their products, monitor stock activities, manage warehouse operations, and maintain better control over their inventory.

Built with PHP and MySQL, StockPilot provides a centralized platform where administrators, managers, and warehouse staff can work together to support efficient business operations.

---

## 🌟 Overview

Managing inventory shouldn't be complicated.

StockPilot brings essential inventory management activities into one organized system. From user access and role management to inventory monitoring, it helps businesses maintain visibility and structure across their daily operations.

Whether you're managing a small business, a warehouse, or a growing organization, StockPilot provides a foundation for more organized inventory management.

## ✨ Key Features

- **Centralized Dashboard** — Access important inventory and business information from one place.
- **User Authentication** — Secure login and account access.
- **Role-Based Access Control** — Separate access levels for administrators, managers, and warehouse staff.
- **User Registration** — Allow authorized new users to create warehouse staff accounts.
- **Inventory Management** — Organize and manage business inventory records.
- **Warehouse Operations** — Support day-to-day warehouse activities.
- **Stock Monitoring** — Maintain visibility into inventory information.
- **Database Integration** — Store and manage application data using MySQL.
- **Responsive Interface** — Access the application through a modern web browser.

## 👥 User Roles

| Role | Responsibilities |
|---|---|
| Administrator | Oversees the application, users, and system management. |
| Manager | Handles assigned management and operational responsibilities. |
| Warehouse Staff | Performs permitted warehouse and inventory activities. |

Each user works within the permissions assigned to their account.

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Backend application logic |
| MySQL | Database management |
| HTML5 | Application structure |
| CSS3 | Interface styling |
| JavaScript | Frontend interactions |
| Bootstrap | Responsive interface components |

## 📸 Application Access

**Live Website:**  
https://stockpilot.freedev.app/

## 📂 Project Structure

```text
StockPilot/
│
├── index.php
├── setup.php
├── config.php
├── database.sql
│
├── includes/
│
├── modules/
│
├── assets/
│
└── README.md
```

## ⚙️ Installation & Setup

Follow these steps to run StockPilot locally.

**1. Install the requirements**

Install XAMPP or another compatible PHP and MySQL environment.

**2. Download the project**

Clone the repository or download the source code as a ZIP file.

**3. Move the project**

Place the StockPilot folder inside your XAMPP `htdocs` directory.

**4. Start the services**

Open XAMPP Control Panel and start:

- Apache
- MySQL

**5. Create the database**

Open phpMyAdmin, create your database, and import the supplied `database.sql` file.

**6. Configure the application**

Update the database connection settings in `config.php` with your local database details.

**7. Create the first administrator**

Open:

```text
http://localhost/StockPilot/setup.php
```

Complete the administrator setup.

**8. Launch StockPilot**

Visit:

```text
http://localhost/StockPilot/
```

You can now access the application.

> **Security:** Delete `setup.php` after successfully creating your first administrator account. Never publish database passwords or private encryption keys.

## 🔐 Security & Access

StockPilot uses account-based access and role assignments to organize system permissions.

Public registration is intended for Warehouse Staff accounts. Administrator privileges should remain restricted to authorized personnel.

Keep production credentials and sensitive configuration files out of public repositories.

## 🎯 Project Goals

StockPilot was developed to demonstrate how web technologies can be combined to create a practical inventory and business management solution.

Its goals include:

- Improving inventory organization.
- Supporting structured warehouse operations.
- Simplifying user and role management.
- Providing centralized access to business information.
- Creating a foundation for scalable business management.

## 👨‍💻 Developer

**Dike Frankjacob Chizurumoke**

Software Developer | Web Application Development

## 📄 License

This project is presented as a software development and portfolio project. Please contact the developer regarding reuse, distribution, or licensing permissions.

---

**StockPilot — Smarter Inventory. Better Business.**
