🎓 CampusShare

Peer-to-Peer University Resource Sharing Platform

CampusShare is a web-based platform that enables university students to lend, borrow, and exchange academic resources such as textbooks, notes, lab equipment, and electronics within a trusted campus community.

✨ Features
Resource posting with image upload
Lending & borrowing dashboard
Google Maps pickup locator
Credibility score & contribution tracking
Due-date reminders and admin moderation
Leaderboard and badge system
🛠 Tech Stack
Backend: PHP 8.2, Laravel 11
Frontend: Blade, JavaScript
Styling: Tailwind CSS
Database: MySQL
Authentication: Laravel Auth
APIs: Google Maps, SMS API, OCR Integration
🚀 Installation
git clone https://github.com/hrithik220/CSE471-PROJECT-campusconnect-resource-sharing.git
cd CSE471-PROJECT-campusconnect-resource-sharing/hrithik

composer install
cp .env.example .env
php artisan key:generate

# Configure database inside .env

php artisan migrate --seed
php artisan storage:link
php artisan serve

Open → http://127.0.0.1:8000

🔐 Demo Accounts
Role	Email	Password
Student log in - hrithik@campus.com
	password
Admin log in - admin@campus.com
	password

database/migrations/
resources/views/
routes/web.php
🎯 Purpose

Developed as part of CSE471 — System Analysis and Design to promote collaborative learning and efficient academic resource sharing within a university ecosystem.
