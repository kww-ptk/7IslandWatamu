# 7 Islands Watamu — Project Guide

## What this is
Resort website for Seven Islands Resort, Watamu Kenya. PHP-based, no framework.
This is the **template project** — future hotels clone this repo and update `.env`.

## Stack
- **Backend:** PHP 8.2
- **Database:** PostgreSQL (via PDO)
- **Hosting:** Render (Docker-based, auto-deploy from GitHub)
- **Repo:** github.com/kww-ptk/7IslandWatamu
- **Local server:** `php -S localhost:8765` (see `.claude/launch.json`)

## Key decisions
- Single-hotel per deployment — clone repo for each new hotel, update `.env`
- No PHP framework — vanilla PHP only
- PostgreSQL not MySQL — spec says MySQL but we use PostgreSQL for Render compatibility
- PDO prepared statements only — no raw string SQL
- Admin is a single shared login (v1) — schema ready for multi-user later

## Spec
Full implementation plan: `2026-05-24-admin-dashboard-design.md` (in Downloads).
Follow it exactly except MySQL → PostgreSQL.

## Local dev
```
php -S localhost:8765
```
Visit http://localhost:8765

## Implementation tasks (from spec)
- Task 0 — Project setup (folders, .env, .gitignore)
- Task 1 — Database schema + seed
- Task 2 — Shared includes (db.php, auth.php, mail.php, tracking.php)
- Task 3 — Dynamic room.php
- Task 4 — Form submission API
- Task 5 — Admin auth
- Task 6 — Admin dashboard
- Task 7 — Admin rooms list + editor
- Task 8 — Admin submissions inbox
- Task 9 — Admin settings
- Task 10 — Deployment

## File structure (target)
```
7island/
├── index.php, about.php, rooms.php, dining.php, spa.php,
│   tours.php, agency.php, contact.php       ← existing
├── room.php                                  ← becomes dynamic (?slug=)
├── includes/
│   ├── header.php, footer.php               ← existing
│   ├── db.php                               ← PDO connection
│   ├── auth.php                             ← session, CSRF, login
│   ├── mail.php                             ← email notifications
│   ├── tracking.php                         ← UTM/referrer capture
│   ├── form-enquiry.php                     ← enquiry form partial
│   └── form-availability.php               ← v2 stub
├── api/
│   ├── submit-enquiry.php
│   ├── submit-contact.php
│   └── submit-agency.php
├── admin/
│   ├── index.php, login.php, logout.php
│   ├── dashboard.php, rooms.php, room-edit.php
│   ├── submissions.php, submission-view.php
│   ├── settings.php
│   ├── .htaccess
│   └── assets/admin.css
├── assets/img/rooms/                        ← uploaded room images
├── db/
│   ├── schema.sql
│   ├── seed.sql
│   └── migrations/
├── bin/
│   ├── create-admin.php
│   └── reset-admin-password.php
├── logs/                                    ← gitignored
├── .env                                     ← gitignored
└── .env.example
```

## Security rules (never break these)
- PDO prepared statements only — no string-concatenated SQL
- `password_hash` / `password_verify` for all passwords
- `htmlspecialchars()` on every admin output via `e($x)` helper
- CSRF token on every admin POST
- `require_login()` first line of every admin file
- `.env` is gitignored — never commit credentials
