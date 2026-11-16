# StockPilot — InfinityFree-ready package

## Important: upload location
This ZIP has the application files directly at the top level. In InfinityFree File Manager,
open `htdocs` and upload/extract the CONTENTS of this ZIP there. After extraction,
`htdocs` should contain `index.php`, `config.php`, `assets/`, `includes/`, and `modules/`.
Do not leave everything inside an extra `StockPilot/StockPilot/` folder.

## 1. Database settings
In InfinityFree Control Panel, open **MySQL Databases** and create a database.
Copy the exact values shown there:
- MySQL hostname
- MySQL database name (often includes an account prefix)
- MySQL username
- MySQL password

Open `config.php` in `htdocs` and replace only these four values:
`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

Do not use the local XAMPP values `root` and a blank password on InfinityFree.
The package detects the public website URL and installation subfolder automatically.

## 2. Import the database
Open phpMyAdmin from the InfinityFree MySQL Databases page, select the database you created,
and import `database.sql`. If you already have live StockPilot data, BACK IT UP FIRST.
Do not import the sample SQL over a database containing important data because it may
create or replace tables/data.

## 3. Open the application
Visit your domain. If the database is new, open `/setup.php` once to create the first
administrator, then log in. After creating the administrator, delete `setup.php` from htdocs.

## 4. Asset path check
The stylesheet is at `htdocs/assets/style.css`; the JavaScript file is at
`htdocs/assets/app.js`. The header and footer now use the detected application URL,
so the paths work whether the app is installed at the domain root or in a subfolder.

## Local XAMPP
For localhost, keep the same folder structure and set the database values in `config.php`
to your local database credentials. APP_URL is detected automatically.
