# CampusShare — Hrithik (Module 1 & 2)
**CSE471 Spring 2026 | Group 11**

## Features
| Module | Feature |
|--------|---------|
| Module 1 | View & Edit Resource Profile (credibility, reviews, stats) |
| Module 2 | Google Maps Pickup Locator |

## Setup (Laragon)
1. Put folder in: `C:\laragon\www\hrithik`
2. Create database: `campus_share`
3. Edit `.env` → set `GOOGLE_MAPS_API_KEY=your_key`
4. Double-click `setup.bat`
5. Open: `http://127.0.0.1:8000`

## Demo Accounts
| Email | Password |
|-------|----------|
| fahim@campus.com | password |
| hrithik@campus.com | password |
| admin@campus.com | password |

## Pages
| URL | Feature |
|-----|---------|
| `/map` | Module 2 — Pickup Map |
| `/resources/{id}` | Module 1 — View Profile |
| `/resources/{id}/edit` | Module 1 — Edit Profile |

## Google Maps API Key
Get a free key from: https://console.cloud.google.com
Enable: Maps JavaScript API
Then set in `.env`: `GOOGLE_MAPS_API_KEY=your_key_here`
