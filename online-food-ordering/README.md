# Online Food Ordering System

A complete beginner-friendly **Online Food Ordering System** built with **PHP, MySQL, HTML, CSS, Bootstrap, JavaScript, and mysqli**. The project includes customer registration/login, admin role-based login, food management, a public menu, session cart, checkout, and order storage in MySQL.

## 1. Project Features

- User registration with `password_hash()`.
- User login with `password_verify()`.
- Session-based authentication using `session_start()`.
- Admin login with role-based authentication.
- Protected admin dashboard.
- Admin can add, edit, and delete food items.
- Public food menu page.
- Add to cart using PHP sessions.
- Cart page with remove item option.
- Protected checkout page for logged-in users only.
- Saves orders and order items into MySQL database.
- Uses `mysqli_connect()`, `mysqli_query()`, `mysqli_fetch_assoc()`, `mysqli_real_escape_string()`, and `mysqli_insert_id()`.
- Responsive Bootstrap design.
- Simple custom CSS and JavaScript.
- Clean folder structure suitable for beginners.

## 2. Folder Structure

```text
online-food-ordering/
├── admin/
│   ├── dashboard.php
│   ├── foods.php
│   └── login.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   └── db.php
├── includes/
│   ├── admin_auth.php
│   ├── auth.php
│   ├── header.php
│   └── footer.php
├── sql/
│   └── database.sql
├── index.php
├── register.php
├── login.php
├── logout.php
├── cart.php
├── checkout.php
└── README.md
```

## 3. XAMPP Setup Steps

These steps also work similarly for WAMP, MAMP, or LAMP.

1. Install and open **XAMPP**.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Copy the `online-food-ordering` folder into your web server folder:
   - XAMPP Windows: `C:\xampp\htdocs\`
   - XAMPP macOS: `/Applications/XAMPP/htdocs/`
   - WAMP: `C:\wamp64\www\`
   - MAMP: `/Applications/MAMP/htdocs/`
   - LAMP: usually `/var/www/html/`
4. The final path should look like:
   - `C:\xampp\htdocs\online-food-ordering\`

## 4. How to Import `sql/database.sql` in phpMyAdmin

1. Open your browser.
2. Go to `http://localhost/phpmyadmin`.
3. Click the **Import** tab.
4. Click **Choose File**.
5. Select this file:

   ```text
   online-food-ordering/sql/database.sql
   ```

6. Click **Go**.
7. phpMyAdmin will recreate the database named `food_ordering_system` and add all tables, the default admin account, and sample foods.

> Warning: `database.sql` starts with `DROP DATABASE IF EXISTS food_ordering_system;` so a fresh import replaces any existing `food_ordering_system` database data.

## 5. SQL File Location

The complete database file is located at:

```text
online-food-ordering/sql/database.sql
```

This file recreates the `food_ordering_system` database and contains:

- `users` table
- `foods` table
- `orders` table
- `order_items` table
- Default admin account
- Sample food records

## 6. Database Config Instructions

Database settings are stored in:

```text
online-food-ordering/config/db.php
```

Default XAMPP settings are:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "food_ordering_system";
```

If your MySQL username or password is different, update `config/db.php`.

Examples:

- If MySQL has a password, set `$password = "your_password";`.
- If your database host is different, update `$host`.
- If you renamed the database, update `$database`.

## 7. Localhost URL to Run Project

After copying the project folder into `htdocs`, open:

```text
http://localhost/online-food-ordering/
```

Public pages:

- Menu: `http://localhost/online-food-ordering/index.php`
- Register: `http://localhost/online-food-ordering/register.php`
- Login: `http://localhost/online-food-ordering/login.php`
- Cart: `http://localhost/online-food-ordering/cart.php`
- Checkout: `http://localhost/online-food-ordering/checkout.php`

Admin pages:

- Admin login: `http://localhost/online-food-ordering/admin/login.php`
- Admin dashboard: `http://localhost/online-food-ordering/admin/dashboard.php`
- Manage foods: `http://localhost/online-food-ordering/admin/foods.php`

## 8. Default Admin Login Details

Use these credentials after importing the database:

```text
Email: admin@foodorder.com
Password: admin123
```

The admin password is stored in the database using `password_hash()`.

## 9. Common Errors and Fixes

### Error: Database connection failed

**Cause:** MySQL is not running, database was not imported, or credentials are wrong.

**Fix:**

- Start MySQL in XAMPP/WAMP/MAMP.
- Import `online-food-ordering/sql/database.sql` using phpMyAdmin.
- Check username, password, host, and database name in `config/db.php`.

### Error: Unknown database `food_ordering_system`

**Cause:** The SQL file was not imported.

**Fix:** Import `sql/database.sql` from phpMyAdmin.

### Error: Access denied for user `root`

**Cause:** Your MySQL root account has a password, but `config/db.php` uses an empty password.

**Fix:** Update this line in `config/db.php`:

```php
$password = "your_mysql_password";
```

### Page shows PHP code instead of running the project

**Cause:** Apache/PHP is not running or the file was opened directly from the folder.

**Fix:**

- Start Apache in XAMPP/WAMP/MAMP.
- Open the project using `http://localhost/online-food-ordering/` instead of double-clicking PHP files.

### Admin login does not work

**Cause:** The database was not imported or the admin row was changed.

**Fix:**

- Re-import `sql/database.sql`.
- Use the exact credentials:
  - Email: `admin@foodorder.com`
  - Password: `admin123`

### Bootstrap design is not loading

**Cause:** Internet access is required for the Bootstrap CDN links.

**Fix:** Connect to the internet or download Bootstrap locally and update the `<link>` and `<script>` paths.

## Notes for Beginners

- This project is intentionally simple and uses procedural PHP.
- It uses `mysqli`, not PDO.
- The cart is stored in PHP sessions, so it exists until the browser session ends or checkout is completed.
- Admin pages are protected by `includes/admin_auth.php`.
- Checkout is protected by `includes/auth.php`.
