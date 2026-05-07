🎓 CampusShare — Peer-to-Peer University Resource Sharing Platform

CSE471: System Analysis and Design | Summer 2025
BRAC University

📌 About the Project

CampusShare is a web-based peer-to-peer resource sharing platform for university students. Students can lend, borrow, and exchange academic resources — textbooks, notes, lab equipment, and electronics — within the campus community.

🛠️ Tech Stack
Category	Technology
Backend	PHP 8.2 + Laravel 11
Frontend	Blade Template Engine + Vanilla JavaScript
Styling	Tailwind CSS
Database	MySQL 8.0
ORM	Eloquent ORM
Authentication	Laravel Auth (Session-based)
Map Integration	Google Maps API / Leaflet.js
SMS Notification	SMS API
OCR	Google Vision API / Tesseract
File Handling	Laravel File Storage
Version Control	Git / GitHub
✅ Functional Requirements
Module 1
Post Resource with photo upload, availability, free/exchange toggle
View & Edit Resource Profile — credibility score, reviews, transaction history
Module 2
Lending & Borrowing Dashboard — due dates, overdue tracking, color-coded status
Google Maps Pickup Locator
Module 3
Due Date Reminders via push notifications
Contribution Statistics — karma points, items lent, leaderboard
Report Lost/Damaged Resource — admin review process
Leaderboard & Badge System
🚀 Setup Instructions
Requirements
PHP >= 8.2
Composer
MySQL
Node.js (for asset compilation)
Installation
# 1. Clone the repository
git clone https://github.com/hrithik220/CSE471-PROJECT-campusconnect-resource-sharing.git
cd CSE471-PROJECT-campusconnect-resource-sharing/hrithik

# 2. Install dependencies
composer install

# 3. Create .env file
copy .env.example .env   # Windows
cp .env.example .env     # Mac/Linux

# 4. Generate app key
php artisan key:generate

# 5. Configure database in .env
# DB_DATABASE=campus_share
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Seed demo data
php artisan db:seed

# 8. Create storage link
php artisan storage:link

# 9. Start server
php artisan serve

Open in browser:

http://127.0.0.1:8000
🔑 Demo Accounts
Email	Password	Role
hrithik@campus.com
	password	Student
samantha@campus.com
	password	Student
admin@campus.com
	password	Admin
📄 Pages
URL	Feature
/map	Google Maps Pickup Locator
/resources/{id}	Resource Profile View
/resources/{id}/edit	Edit Resource
/resources/create	Post Resource
/dashboard	Lending/Borrowing Dashboard
🗄️ Database Schema
users — id, name, email, role, credibility_score, karma_points
categories — id, name, slug, icon
resources — id, user_id, category_id, title, description, condition, availability_status, sharing_type, pickup_lat, pickup_lng, pickup_address, image_paths, is_approved
resource_reviews — id, resource_id, reviewer_id, rating, comment
transactions — id, resource_id, lender_id, borrower_id, borrow_date, due_date, return_date, status




hrithik/
├── app/
│   ├── Http/Controllers/
│   │   ├── ResourceController.php
│   │   ├── ResourcePostController.php
│   │   ├── MapController.php
│   │   └── DashboardController.php
│   └── Models/
│       ├── Resource.php
│       ├── Category.php
│       ├── ResourceReview.php
│       └── Transaction.php
├── database/migrations/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── resources/
│   │   ├── show.blade.php
│   │   ├── edit.blade.php
│   │   └── create.blade.php
│   ├── map/index.blade.php
│   └── dashboard/index.blade.php
└── routes/web.php
