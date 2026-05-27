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

## Phase 3 tasks — Availability & booking (not yet started)
Build in this order — each phase depends on the previous.

### Phase 3a — Foundation (schema + request-to-book)
- Task 19 — Schema additions: `units` (bookable instances per room), `availability_blocks` (date ranges blocked/held/booked), `rates` (price overrides by date range), `holds` (24h soft holds), `ical_feeds` (OTA feed URLs per unit)
- Task 20 — Request-to-book flow: guest requests dates → 24h soft hold created → admin confirms or expires → submission lifecycle (pending → confirmed → expired/cancelled) → email triggers at each state change → cron job clears expired holds
- Task 21 — Admin hold/booking list: view active holds, confirm or release, filter by unit/date/status

### Phase 3b — Guest-facing calendar
- Task 22 — Public availability query API: `api/check-availability.php?room=slug&check_in=&check_out=` returns available units + rate for date range
- Task 23 — Calendar widget on room.php: date range picker showing blocked/available dates, replaces stub `form-availability.php` when `form_mode=availability`
- Task 24 — iCal feed generation: `api/ical.php?unit=ID&token=SECRET` serves .ics feed per unit (for OTAs to subscribe to)

### Phase 3c — OTA sync + admin Gantt (hardest, build last)
- Task 25 — iCal pull sync: cron job fetches external .ics URLs from `ical_feeds`, parses blocks, inserts into `availability_blocks`, applies conflict rules (external blocks always win)
- Task 26 — Admin Gantt calendar: timeline view (units as rows, dates as columns), drag-drop to move/resize blocks, bulk-select date ranges to set rates or block, visual state colours (available/held/booked/blocked)

### Key decisions for Phase 3
- A **room** has 1–N **units** (e.g. "Standard Room" has 3 physical units — unit A/B/C)
- Availability is tracked at the **unit** level, not the room level
- `form_mode=availability` (already in settings) is the toggle that activates the calendar widget
- iCal feeds use a secret token in the URL (no auth UI needed for OTAs)
- Holds expire after 24h — a cron or lazy-expiry on next page load is acceptable for v1

### Migration for Phase 3
```
psql $DATABASE_URL -f db/migrations/add_availability.sql
```

---

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

## Holds & booking system (Phase 3a — built)

### How the hold flow works end-to-end
1. Guest picks dates on `room.php` → submits `includes/form-availability.php`
2. `api/submit-enquiry.php` validates, calls `find_available_unit()`, calls `create_hold_with_block()`, calls `send_hold_notification()`
3. Admin receives HTML email with **Confirm** and **Decline** buttons (HMAC-signed URLs)
4. Clicking a button → `admin/hold-action.php` → verifies login + token → applies action → redirects to `admin/holds.php` with flash message
5. On confirm: guest receives `send_hold_confirmed()` email
6. On decline/expiry: guest receives `send_hold_cancelled()` email

### Hold expiry
- **Lazy expiry:** `expire_stale_holds()` (in `db.php`) runs automatically inside `find_available_unit()` and on `admin/holds.php` page load. It expires holds, frees blocks, and emails guests.
- **Cron expiry:** `bin/ical-expire-holds.php` — run every 5 min on Render Cron (`*/5 * * * *`). Ensures expiry happens even when no admin is active.
- Do NOT call `expire_stale_holds()` from public pages — it is already called inside `find_available_unit()`.

### Token security (email action buttons)
- Tokens: `hash_hmac('sha256', "$holdId:$action", BOOKING_TOKEN_SECRET)` via `includes/booking.php`
- `BOOKING_TOKEN_SECRET` must be set in `.env` (generate: `openssl rand -hex 32`)
- If secret is missing, email falls back to a plain "Manage holds" link — no crash
- Tokens require admin login — token alone is not enough; it is anti-CSRF, not anti-auth
- Token is valid as long as the hold is in a state where the action is allowed (pending→confirm, pending/confirmed→decline)

### Key files
| File | Purpose |
|------|---------|
| `includes/booking.php` | `make_hold_token()` / `verify_hold_token()` |
| `admin/hold-action.php` | One-click confirm/decline from email |
| `admin/holds.php` | Admin hold list — confirm/cancel via form, reads session flash |
| `includes/mail.php` | `send_hold_notification()` (HTML + text), `send_hold_confirmed()`, `send_hold_cancelled()` |
| `includes/db.php` | `create_hold_with_block()`, `find_available_unit()`, `expire_stale_holds()` |
| `bin/ical-expire-holds.php` | Cron wrapper for expiry |
| `api/submit-enquiry.php` | Handles availability-mode submission, creates hold |

### env vars required for holds
```
BOOKING_TOKEN_SECRET=   # openssl rand -hex 32 — for email action buttons
SITE_URL=               # base URL for email links (must be set in production)
MAIL_FROM=              # sender address
RESEND_API_KEY=         # Resend key for HTML emails
```

### iCal export/import
- Export: `api/ical.php?unit=<id>&token=<feed_token>` — serve .ics per unit
- Import: `api/sync-ical.php?secret=<ICAL_SYNC_SECRET>` — pull OTA feeds, insert blocks
- Feed tokens are stored in `units.feed_token` — visible in `admin/gantt.php`
- "Sync Now" button in Gantt triggers import for all configured feeds

### SEO best practices (enforced)
- Every public page sets `$pageTitle`, `$metaDesc`, `$canonicalUrl` BEFORE including `header.php`
- `$pageTitle` must use plain `&` not `&amp;` — `header.php` runs it through `e()` (htmlspecialchars)
- `$jsonLd` must be valid JSON — use `json_encode()`, never hand-build JSON strings
- Dynamic pages (room.php, tour.php) pull SEO fields from DB (`seo_title`, `seo_description`)
- Sitemap is at `sitemap.php` — dynamically includes all published rooms and tours

## Security rules (never break these)
- PDO prepared statements only — no string-concatenated SQL
- `password_hash` / `password_verify` for all passwords
- `htmlspecialchars()` on every admin output via `e($x)` helper
- CSRF token on every admin POST
- `require_login()` first line of every admin file
- `.env` is gitignored — never commit credentials
- HMAC tokens for email actions — always use `hash_equals()`, never `===`
- Never output hold tokens in HTML pages — only in emails sent server-side
