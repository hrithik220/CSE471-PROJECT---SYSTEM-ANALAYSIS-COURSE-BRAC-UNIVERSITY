# CampusShare — Setup Guide

## Requirements
- PHP >= 8.1
- Composer
- MySQL
- Node.js (optional, for assets)

## Installation Steps

### 1. Extract & Navigate
```bash
unzip campusshare.zip
cd campusshare
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env`:
```
DB_DATABASE=campusshare
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Create Database & Run Migrations
```bash
# Create database in MySQL first:
# CREATE DATABASE campusshare;

php artisan migrate
php artisan db:seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Run the App
```bash
php artisan serve
```
Visit: http://localhost:8000

## Demo Accounts
| Name | Email | Password |
|------|-------|----------|
| Samantha Lee | samantha@campus.edu | password |
| Hrithik Roy | hrithik@campus.edu | password |

## Features
1. **Post Resources** — Upload photo, description, availability, location
2. **Resource Profiles** — Owner credibility score, reviews, transaction history
3. **Transaction Dashboard** — Color-coded status (active, overdue, pending)
4. **Campus Map** — Leaflet-powered map with resource pickup markers

## Tech Stack
- Laravel 10 + Blade + Tailwind CSS
- MySQL + Eloquent ORM
- Leaflet.js (Maps)
- Alpine.js (UI tabs)
