# CampusShare — Samantha's Modules
## Module 1 (Post Resource) & Module 2 (Lending/Borrowing Dashboard)

---

## 🐛 Bug Fixes Applied

| # | File | Bug | Fix |
|---|------|-----|-----|
| 1 | `app/Models/Transaction.php` | `BorrowRequest` class was incorrectly defined inside `Transaction.php` — two unrelated models in one file breaks Laravel's autoloader | Moved `BorrowRequest` to its own file: `app/Models/BorrowRequest.php` |
| 2 | `app/Models/BorrowRequest.php` | `proposed_pickup` & `proposed_return` were cast as `datetime` but the migration defines them as `date` columns — caused wrong format in SMS and date comparisons | Changed casts to `'date'` to match the migration |
| 3 | `app/Http/Controllers/ResourceController.php` | `show()` method was completely missing — `store()` redirects to `resources.show` and there's also a `transactions.show` route, both would throw `BadMethodCallException` | Added `show()` method |
| 4 | `app/Http/Controllers/ResourceController.php` | `markReturned()` called `authorize('update', $transaction)` — no `TransactionPolicy` exists, causing an `AuthorizationException` | Changed to `authorize('update', $transaction->resource)` to use the existing `ResourcePolicy` |
| 5 | `resources/views/samantha/post-resource.blade.php` | Navbar "Browse" link used `route('resources.index')` which doesn't exist — throws `RouteNotFoundException` on page load | Changed to `route('dashboard')` |

---

## 📁 File Structure

```
samantha/
├── app/
│   ├── Http/Controllers/
│   │   ├── ResourceController.php       ← Module 1 & 2 logic
│   │   └── BorrowRequestController.php  ← Approve / Reject requests
│   ├── Models/
│   │   ├── Resource.php
│   │   └── Transaction.php              ← Also contains BorrowRequest model
│   └── Services/
│       └── SmsService.php               ← SMS API wrapper
├── database/migrations/
│   └── 2026_04_13_000001_create_resources_table.php
├── resources/views/samantha/
│   ├── post-resource.blade.php          ← Module 1 UI
│   └── dashboard.blade.php             ← Module 2 UI
└── routes/
    └── web.php                          ← All Samantha routes
```

---

## ⚙️ Setup Instructions

### 1. Copy files into your Laravel project
- Copy `app/Http/Controllers/*` → your project's `app/Http/Controllers/`
- Copy `app/Models/*` → your project's `app/Models/`
- Copy `app/Services/SmsService.php` → your project's `app/Services/`
- Copy `resources/views/samantha/` → your project's `resources/views/samantha/`
- Add migration file to `database/migrations/`
- Merge routes from `routes/web.php` into your project's `routes/web.php`

### 2. Run the migration
```bash
php artisan migrate
```

### 3. Add SMS config to `config/services.php`
```php
'sms' => [
    'key'       => env('SMS_API_KEY'),
    'url'       => env('SMS_API_URL'),
    'sender_id' => env('SMS_SENDER_ID', 'CampusShare'),
],
```

### 4. Add to `.env`
```
SMS_API_KEY=your_key_here
SMS_API_URL=https://your-sms-provider.com/api/send
SMS_SENDER_ID=CampusShare
```

### 5. Register SmsService (optional, auto-discovered)
In `app/Providers/AppServiceProvider.php`:
```php
$this->app->singleton(\App\Services\SmsService::class);
```

### 6. Storage link (for photo uploads)
```bash
php artisan storage:link
```

---

## 🔗 Routes Summary

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/resources/create` | `resources.create` | Post Resource form |
| POST | `/resources` | `resources.store` | Save new resource |
| GET | `/dashboard/transactions` | `dashboard` | Lending/Borrowing dashboard |
| POST | `/transactions/{id}/remind` | `transactions.remind` | Send SMS reminder |
| PATCH | `/transactions/{id}/return` | `transactions.return` | Mark item returned |
| PATCH | `/requests/{id}/approve` | `requests.approve` | Approve borrow request |
| PATCH | `/requests/{id}/reject` | `requests.reject` | Reject borrow request |

---

## 🎨 Tech Used
- **Laravel** (Blade, Eloquent, File Storage, HTTP Client)
- **Tailwind CSS** (via CDN)
- **Google Fonts** — Syne + DM Sans
- **SMS API** — plug in any provider (Twilio, SSL Wireless, etc.)
