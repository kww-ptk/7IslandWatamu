# Seven Islands Resort — Watamu

Resort website with PHP admin backend. Single-hotel deployment — clone this repo and update environment variables for each new hotel.

## Stack

- **Backend:** PHP 8.2 (no framework)
- **Database:** PostgreSQL (via PDO)
- **Hosting:** Render (Docker)
- **Image storage:** Local disk (ephemeral on Render free tier) — configure Cloudflare R2 for persistence
- **Email:** Resend API (configure `RESEND_API_KEY`) or PHP `mail()`

---

## First-time deploy (Render)

### 1. Create a PostgreSQL database on Render
- Render dashboard → **New** → **PostgreSQL** → Free plan
- Copy the **Internal Database URL**

### 2. Create a Web Service on Render
- **New** → **Web Service** → connect GitHub repo
- Runtime: **Docker**, Branch: `master`

### 3. Set environment variables on the web service

| Variable | Description |
|----------|-------------|
| `DATABASE_URL` | Internal Database URL from step 1 |
| `SITE_URL` | Your public URL e.g. `https://yoursite.onrender.com` |
| `MAIL_FROM` | Sender address e.g. `reservation@yourdomain.com` |
| `RESEND_API_KEY` | Resend API key (get one at resend.com) |
| `R2_ACCOUNT_ID` | Cloudflare R2 account ID (optional) |
| `R2_BUCKET` | R2 bucket name (optional) |
| `R2_ACCESS_KEY` | R2 access key (optional) |
| `R2_SECRET_KEY` | R2 secret key (optional) |
| `R2_PUBLIC_URL` | R2 public bucket URL (optional) |

### 4. Run database setup
Once deployed, visit:
```
https://yoursite.onrender.com/bin/setup-db.php?secret=7iw-setup-2024&admin_email=you@email.com&admin_pass=yourpassword
```
This runs schema.sql + seed.sql and creates the first admin user.

> **Delete `bin/setup-db.php` from the repo after running it.**

### 5. Verify
- Admin panel: `https://yoursite.onrender.com/admin`
- Public site: `https://yoursite.onrender.com`

---

## Local development

### Requirements
- PHP 8.2 with `pdo_pgsql` and `gd` extensions
- PostgreSQL running locally

### Setup
```bash
cp .env.example .env
# Edit .env with your local DB credentials

# Run schema and seed
psql $DATABASE_URL -f db/schema.sql
psql $DATABASE_URL -f db/seed.sql

# Create admin user
php bin/create-admin.php admin@example.com yourpassword

# Start server
php -S localhost:8765
```

Visit http://localhost:8765

---

## Admin management

### Create admin user
```bash
php bin/create-admin.php <email> <password>
```

### Reset admin password
```bash
php bin/reset-admin-password.php <email> <new_password>
```

---

## Cloning for a new hotel

1. Fork or clone this repo
2. Create a new Render web service + PostgreSQL database
3. Set environment variables with the new hotel's details
4. Run the setup URL to initialise the database
5. Log in and update rooms, images, and settings

No code changes needed — everything is configured via environment variables and the admin panel.

---

## File structure

```
├── index.php, about.php, rooms.php ...  ← public pages
├── room.php                             ← dynamic room page (?slug=)
├── includes/
│   ├── db.php                           ← PDO helpers, storage_url()
│   ├── auth.php                         ← session, CSRF, login
│   ├── mail.php                         ← email notifications
│   ├── tracking.php                     ← UTM/referrer capture
│   ├── storage.php                      ← R2 / local image storage
│   ├── form-enquiry.php                 ← booking form partial
│   └── form-availability.php           ← v2 placeholder
├── api/
│   ├── submit-enquiry.php
│   ├── submit-contact.php
│   └── submit-agency.php
├── admin/                               ← password protected
│   ├── dashboard.php
│   ├── rooms.php / room-edit.php
│   ├── submissions.php / submission-view.php
│   └── settings.php
├── db/
│   ├── schema.sql
│   └── seed.sql
└── bin/
    ├── create-admin.php
    └── reset-admin-password.php
```

---

## Security notes

- All SQL uses PDO prepared statements — no string concatenation
- All output escaped via `e()` helper (`htmlspecialchars`)
- CSRF tokens on every admin POST
- Bcrypt password hashing
- Rate limiting on login (5 attempts / 10 min) and form submissions (5 / 10 min per IP)
- Honeypot field on all public forms
- `.env` is gitignored — never commit credentials
