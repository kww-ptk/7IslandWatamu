# 7 Islands Watamu — Tech Stack

## Backend

| Layer | What we use |
|---|---|
| Language | **PHP 8.2** — vanilla, no framework |
| Database | **PostgreSQL** via native PDO (prepared statements only) |
| Server | **Apache 2** (inside Docker, mod_rewrite enabled) |
| Email | **Resend API** (primary) → falls back to PHP `mail()` |
| Sessions | Native PHP sessions (tracking, auth) |

## Frontend

| Layer | What we use |
|---|---|
| CSS | Custom hand-written **CSS** — no framework (no Tailwind, no Bootstrap) |
| JavaScript | Vanilla **JS** — no React, no Vue |
| Font | **Google Fonts** — Open Sans |
| Date picker | **Flatpickr 4.6.13** (CDN) |
| Icons | Inline **SVG** only |

## Infrastructure & Hosting

| Layer | What we use |
|---|---|
| Hosting | **Render** (PaaS) |
| Containerisation | **Docker** (`php:8.2-apache` base image) |
| Deploy | Auto-deploy from **GitHub** push |
| Image storage | **Cloudflare R2** (or local `/assets/img/`) |
| Environment config | `.env` file locally, Render env vars in production |

## Security & Spam

| Layer | What we use |
|---|---|
| SQL injection | PDO prepared statements throughout |
| XSS | `htmlspecialchars()` via `e()` helper on all output |
| CSRF | Token on every admin POST |
| Spam | Honeypot field + IP rate limiting (5 req / 10 min) |
| Passwords | `password_hash()` / `password_verify()` |

## SEO & Analytics

| Layer | What we use |
|---|---|
| Meta | Custom `header.php` — title, description, OG, Twitter Card |
| Structured data | JSON-LD per page |
| Sitemap | Dynamic `sitemap.php` (rooms + tours) |
| UTM tracking | First-touch capture stored in session, saved per submission |

---

> No third-party PHP packages or Composer. The only external JS dependency is Flatpickr for the date picker.
