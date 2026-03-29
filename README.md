# Café Reservation System (Laravel)

A full‑stack café reservation platform built with Laravel, allowing customers to book tables online and staff to manage reservations efficiently.  
Developed as part of the **SEHS4517 Group Project**.

---
## Dependencies 
| Package | Version |
|--------|---------|
| PHP |^8.2 |
| laravel/framework | 12.x |
|Node.js| v24.14.0|

---
## Full Laravel Setup Guide (XAMPP + Composer + Node.js + phpMyAdmin)
### 1.Install XAMPP
XAMPP gives you Apache + PHP + MySQL.

<b>Steps</b>
- Download XAMPP (PHP 8.2.12)
- Install it normally.
- After installation, verify:
```
"C:\xampp\php\php.exe" -v
```

### 2. Install Composer
Composer is required for Laravel.
<b>Steps</b>
- Download Composer installer.
- During installation, make sure it detects:
```
C:\xampp\php\php.exe
```
- After installation, verify:
```
composer -V
```

### 3.Install Node.js + npm
Node.js is required for Laravel Mix / Vite.
<b>Steps</b>
- Download Node.js v24.14.0 LTS.
-Install it.
-- Verify:
```
node -v
npm -v
```

### 4.Create Database in phpMyAdmin
<b>Steps</b>
- Open XAMPP Control Panel.
- Start: Apache + MySQL
- Open browser:
```
http://localhost/phpmyadmin
```
- Click Databases.
- Create a new database:
```
laravel_app
```
---
## How to install
### 1. Clone the repository
```
git clone https://github.com/wwa3123/sehs4517-group-project-cafe-reservation-laravel.git
```
```
cd sehs4517-group-project-cafe-reservation-laravel
```
### 2.Install backend dependencies (Composer)
```
composer install
```
### 3.Install frontend dependencies 
```
npm install
```
### 4. Create your environment file
```
cp .env.example .env
```
### 5. Generate application key
```
php artisan key:generate
```
### 6. Configure your database
Edit ```.env``` and update:
```
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass
```
### 7. Run database migration
```
php artisan migrate
```
### 8. Start everything
```
composer run dev
```
### ~~8. Start the Laravel development server~~

### ~~9. Start the frontend build tool (Vite)~~


