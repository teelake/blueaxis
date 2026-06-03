# BlueAxis Logistics & Warehousing — Corporate Website

Production-ready B2B corporate website with CMS, blog, lead management, and admin dashboard. Built for **private preview** in the `new/` directory while the root under-construction page remains public.

## Brand colors (from official logo)

| Token | Hex | Usage |
|-------|-----|--------|
| Navy (primary) | `#102A56` | Headers, buttons, brand UI |
| Navy dark | `#0B1D3F` | Deep backgrounds |
| Gold (accent) | `#C59E5F` | Accents, highlights |
| Gold light | `#D4B07A` | Hover states |

## Requirements

- PHP 8.3+
- MySQL 8+
- Node.js 18+ (Tailwind build)
- Apache with `mod_rewrite` **or** PHP built-in server

## Setup

1. **Configure environment**

   ```bash
   cd new
   copy .env.example .env
   ```

   Edit `.env` with database credentials and `APP_URL` (e.g. `http://localhost:8080`).

2. **Create database**

   ```sql
   CREATE DATABASE blueaxis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Run migrations & seed**

   ```bash
   php database/migrate.php --seed
   ```

4. **Install & build frontend assets**

   ```bash
   npm install
   npm run build
   ```

5. **Run locally**

   ```bash
   php -S localhost:8080 -t public public/router.php
   ```

   Open http://localhost:8080

## Admin access

- URL: `/admin/login`
- Default email: `admin@blueaxis.com`
- Password: value of `ADMIN_PASSWORD` in `.env` (default `ChangeMe123!`)

**Change the admin password immediately after first login.**

## Email notifications (leads)

When someone submits the **contact** or **quote** form, the team receives an HTML email (submission still saves if mail fails—check `storage/logs/mail.log`).

**Configure in the admin UI:** `/admin/settings/email` (Super Admin only). Settings are stored in the database and override `.env` defaults.

| Setting | Purpose |
|---------|---------|
| Driver | PHP `mail()` or SMTP |
| SMTP host / port / encryption / credentials | Gmail, SendGrid, hosting SMTP, etc. |
| Notify to | Inbox for lead alerts |
| Toggles | Contact emails, quote emails, Reply-To visitor |

Use **Send test email** on that page to verify configuration. Optional `.env` values apply until you save settings in admin.

## Rich text editor (admin)

Blog content, service descriptions, and About/Home HTML blocks use **[Quill](https://quilljs.com/)** (BSD-3-Clause, free for commercial use). Content is sanitized on save.

## Features

### Public site
- Home (10 sections), About, Services, Blog, Contact
- Quote request & contact forms with CSRF + validation
- SEO meta, Open Graph, canonical URLs, JSON-LD organization schema
- `/sitemap.xml` and `/robots.txt`

### Admin CMS
- Dashboard with stats (Chart.js placeholder analytics)
- Home & About content blocks
- Services CRUD
- Blog (draft/publish, categories, SEO fields)
- Contact & quote lead management with CSV export
- Media library with image upload

### Architecture
- Modular PHP MVC (`app/Controllers`, `Models`, `Services`, `Views`)
- PDO + prepared statements
- Session-based admin auth with role-ready schema
- MySQL migrations in `database/migrations/`

## Going live

When ready to replace the under-construction site:

1. Point the production web root to `new/public/`
2. Remove or archive root `index.html`, `styles.css`, `main.js`
3. Set `APP_ENV=production`, `APP_DEBUG=false` in `.env`

## Directory structure

```
new/
├── app/           Application code (MVC)
├── config/        App & database config
├── database/      Migrations, seeds, migrate.php
├── public/        Web document root
├── resources/css/ Tailwind source
├── routes/        Route definitions
└── storage/       Logs
```
