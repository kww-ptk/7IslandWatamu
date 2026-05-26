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

## Phase 2 tasks (2026-05-26)
- Task 11 — Homepage rooms dynamic (was hardcoded static HTML)
- Task 12 — Homepage enquiry form fixed (was firing mailto: instead of API)
- Task 13 — Tours feature: DB table, tour.php single page, dynamic tours.php, admin/tours.php + tour-edit.php
- Task 14 — Contact form added to tours.php (id="toursContactForm" → submit-contact.php)
- Task 15 — SEO: header.php extended with meta description, OG tags, Twitter Card, canonical URL, JSON-LD
- Task 16 — robots.txt and sitemap.php (dynamic, includes rooms + tours)
- Task 17 — All public pages have $metaDesc and $canonicalUrl set
- Task 18 — Mobile CSS review (ongoing)

## Migration required on existing DB
Run before deploying Phase 2 code:
```
psql $DATABASE_URL -f db/migrations/add_tours.sql
```
This adds: tours table, tour_images table, tour_id column on submissions, and seeds 16 initial tours.

## Forms — how they connect to admin
| Form | Page | API endpoint | Admin type |
|------|------|-------------|-----------|
| Hero enquiry | index.php | submit-enquiry.php | enquiry |
| Room enquiry | room.php | submit-enquiry.php | enquiry |
| Tour enquiry | tour.php | submit-enquiry.php | enquiry (tour_id set) |
| Tours page contact | tours.php | submit-contact.php | contact |
| Contact page | contact.php | submit-contact.php | contact |
| Travel agency | agency.php | submit-agency.php | agency |

## SEO — variables supported in header.php
Set these PHP variables before including header.php:
- `$pageTitle` — `<title>` tag (required)
- `$metaDesc` — `<meta name="description">` (recommended)
- `$canonicalUrl` — `<link rel="canonical">` (recommended)
- `$ogImage` — Open Graph image URL (auto-defaults to resort hero image)
- `$ogType` — OG type (defaults to "website")
- `$ogTitle` — OG title (defaults to $pageTitle)
- `$ogDesc` — OG description (defaults to $metaDesc)
- `$jsonLd` — JSON-encoded structured data object (optional)

## Tours admin
- List: `admin/tours.php` — drag to reorder, toggle publish, edit/view links
- Edit: `admin/tour-edit.php` — tabs for Details, Gallery, SEO, Publish/Delete
- Categories: `classic`, `custom`, `excursion`
- Tour detail page: `tour.php?slug=xxx`

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

## Admin UI — responsive design
The admin panel is fully responsive. Key rules when editing admin CSS or layout:
- **Breakpoints:** 768px (mobile), 480px (small mobile), 1024px (tablet)
- **Sidebar:** fixed on desktop; hidden off-screen on mobile, opens via hamburger button in top bar
- **Mobile top bar:** `.admin-topbar` — 52px fixed bar with burger + title, visible only on mobile
- **Hamburger JS:** lives in `admin/_layout_end.php` — toggles `.is-open` on sidebar, `.is-visible` on overlay
- **Tables:** `display:block` + `overflow-x:auto` on mobile for horizontal scroll
- **Grids:** KPI grid (3→1 col), detail grid (2→1 col), form rows (2→1 col) on mobile
- **Never use fixed pixel widths** on admin layout elements — use percentages or grid
- **Test on 375px width** (iPhone SE) as the minimum target

## Security rules (never break these)
- PDO prepared statements only — no string-concatenated SQL
- `password_hash` / `password_verify` for all passwords
- `htmlspecialchars()` on every admin output via `e($x)` helper
- CSRF token on every admin POST
- `require_login()` first line of every admin file
- `.env` is gitignored — never commit credentials
