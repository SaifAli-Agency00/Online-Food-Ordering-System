# Deployment Guide

This project can be deployed on a PHP/MySQL shared hosting server, cPanel hosting, or a VPS/LAMP server.

## Minimum Server Requirements

- PHP 8.0 or newer
- MySQL or MariaDB
- Apache or Nginx
- PHP `mysqli` extension enabled

## Deploy on cPanel / Shared Hosting

1. Zip the `online-food-ordering` folder.
2. Open cPanel and go to **File Manager**.
3. Upload the zip file into `public_html` or a subfolder such as `public_html/food-ordering`.
4. Extract the zip file.
5. Open **MySQL Databases** in cPanel.
6. Create a database, database user, and password.
7. Add the user to the database with all privileges.
8. Open **phpMyAdmin** from cPanel.
9. Select your new database and import `online-food-ordering/sql/database.sql`.
10. Edit `online-food-ordering/config/db.php` with the hosting database name, username, password, and host.

Example hosting config:

```php
$host = "localhost";
$username = "cpaneluser_dbuser";
$password = "your_db_password";
$database = "cpaneluser_food_ordering";
```

## Deploy on a VPS / LAMP Server

1. Install Apache, PHP, MySQL, and the PHP mysqli extension.
2. Copy the `online-food-ordering` folder into `/var/www/html/`.
3. Create a MySQL database and user.
4. Import `sql/database.sql` with phpMyAdmin or the MySQL command line.
5. Update `config/db.php` with your database credentials.
6. Visit your domain or server IP in the browser.

## Important Deployment Notes

- Do not keep the default admin password on a live server. Login as admin and change the seeded password manually in the database or create a new admin hash with `password_hash()`.
- If your hosting provider uses a different database host, replace `localhost` in `config/db.php`.
- If Bootstrap CDN files do not load, download Bootstrap and link local files from the `assets` folder.
- The project is intentionally procedural and beginner-friendly. For production, add stronger validation, CSRF protection, prepared statements, and HTTPS.
