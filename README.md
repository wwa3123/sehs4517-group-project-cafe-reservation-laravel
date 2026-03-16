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
### 8. Start the Laravel development server
```
php artisan serve
```
### 9. Start the frontend build tool (Vite)
```
npm run dev
```

