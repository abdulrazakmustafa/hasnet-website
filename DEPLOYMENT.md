# Hasnet ICT Solution – Deployment Guide

## Prerequisites

- **PHP** 8.0 or higher
- **MySQL** 5.7+ / MariaDB 10.3+
- **Apache** with `mod_rewrite` enabled (or Nginx)
- A hosting account (cPanel / Shared Hosting recommended)

---

## Local Development Setup

### Option A: XAMPP (Recommended for Windows)

1. Download and install [XAMPP](https://www.apachefriends.org/) (includes PHP, MySQL, Apache)
2. Start **Apache** and **MySQL** from XAMPP Control Panel
3. Copy the website folder to `C:\xampp\htdocs\hasnet-website\`
4. Edit `config.php`:
   ```php
   define('APP_URL', 'http://localhost/hasnet-website');
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'hasnet_db');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // XAMPP default: empty password
   ```
5. Run setup: Open `http://localhost/hasnet-website/setup.php?token=hasnet_setup_2025`
6. Access admin: `http://localhost/hasnet-website/admin/login.php`

### Option B: PHP Built-in Server (CLI)

```bash
cd C:\Users\Abdulrazak Mustafa\Documents\Hasnet-Website
php -S localhost:8080
```
> Note: You still need MySQL running. Install MySQL separately or use XAMPP just for MySQL.

### Option C: Laragon (Lightweight alternative)

1. Download [Laragon](https://laragon.org/)
2. Place project in `C:\laragon\www\hasnet-website\`
3. Laragon auto-creates a vhost: `http://hasnet-website.test`

---

## Production Deployment (cPanel / Shared Hosting)

### Step 1 – Upload Files

Upload all project files to your hosting `public_html/` directory (or subdirectory).

**Important:** Do NOT upload `setup.php` to production after setup is done.

### Step 2 – Create MySQL Database

1. Log into **cPanel** → **MySQL Databases**
2. Create a new database: `hasnet_db`
3. Create a new user with a strong password
4. Assign ALL PRIVILEGES to the user on that database
5. Note down: DB name, username, password, host (usually `localhost`)

### Step 3 – Configure `config.php`

Edit `config.php` with your production values:

```php
define('APP_ENV', 'production');
define('APP_URL',  'https://hasnet.co.tz');
define('DB_HOST',  'localhost');
define('DB_NAME',  'your_cpanel_username_hasnet_db');
define('DB_USER',  'your_cpanel_username_dbuser');
define('DB_PASS',  'your_strong_password');
```

> On cPanel, database names are prefixed with your cPanel username, e.g. `abdulra_hasnet_db`

### Step 4 – Run Setup

1. Visit: `https://hasnet.co.tz/setup.php?token=hasnet_setup_2025`
2. Verify all checks pass (green ticks)
3. **Delete `setup.php` immediately after!**

```bash
# Or via cPanel File Manager – delete setup.php
```

### Step 5 – Set File Permissions

```
uploads/       → 755
uploads/*/     → 755
config.php     → 644
*.php          → 644
```

Via cPanel File Manager → Select folder → Permissions → 755

### Step 6 – Test Everything

1. Visit the website: `https://hasnet.co.tz`
2. Check admin login: `https://hasnet.co.tz/admin/login.php`
3. Submit a quote request – verify it appears in admin
4. Test subscribe popup (wait 15 seconds)
5. Upload a portfolio item with an image

---

## Updating the Website

### Via GitHub + FTP

```bash
# Pull latest changes locally
git pull origin main

# Upload changed files to server via FTP/cPanel
# Tools: FileZilla, cPanel File Manager, or SSH
```

### Via SSH (if your host supports it)

```bash
# SSH into your server
ssh username@hasnet.co.tz

# Navigate to web root
cd public_html

# Pull latest changes
git pull origin main
```

### Via cPanel Git (if available)

Some hosts support cPanel Git Version Control:
1. cPanel → Git Version Control → Create
2. Set Clone URL: `https://github.com/abdulrazakmustafa/hasnet-website.git`
3. Repository Path: `/home/username/public_html`
4. To update: cPanel → Git → Pull

---

## Admin Panel

| URL | Purpose |
|-----|---------|
| `/admin/login.php` | Admin login |
| `/admin/` | Dashboard |
| `/admin/portfolio/` | Portfolio management |
| `/admin/blog/` | Blog post management |
| `/admin/subscribers/` | View & export subscribers |
| `/admin/quotes/` | Quote requests |
| `/admin/users/` | User management (super_admin) |
| `/admin/settings/` | General settings |
| `/admin/settings/appearance.php` | Logo, colours |
| `/admin/settings/slider.php` | Hero slider |
| `/admin/settings/pages.php` | Page content |
| `/admin/settings/popup.php` | Subscribe popup |

### Default Admin Credentials

| Field | Value |
|-------|-------|
| Email | abdulrazak.jmus@gmail.com |
| Password | @Dulleycubic1 |
| Role | super_admin |

> **Change your password after first login!**

### User Roles

| Role | Can Do |
|------|--------|
| `super_admin` | Everything including user management and role assignment |
| `admin` | All content + settings, view users (cannot delete users) |
| `editor` | Create/edit portfolio and blog posts only |

---

## Folder Structure

```
hasnet-website/
├── admin/               ← Admin panel (password protected)
│   ├── includes/        ← Auth, DB, functions
│   ├── portfolio/       ← Portfolio CRUD
│   ├── blog/            ← Blog CRUD
│   ├── subscribers/     ← Subscriber management
│   ├── quotes/          ← Quote requests
│   ├── users/           ← User management
│   ├── settings/        ← All CMS settings
│   └── assets/          ← Admin CSS/JS
├── assets/              ← Website CSS, JS, images, fonts
├── database/
│   └── schema.sql       ← Database schema (run once)
├── uploads/             ← User-uploaded media
├── config.php           ← Database & app config ⚠️
├── setup.php            ← One-time install script ⚠️ DELETE AFTER USE
├── subscribe.php        ← Subscribe popup handler
├── quote_handler.php    ← Quote form handler
├── index.php            ← Home page
├── portfolio.php        ← Portfolio page
├── contact.php          ← Contact page
└── footer.php           ← Global footer (includes popup)
```

---

## Security Notes

1. **Delete `setup.php`** after running it
2. Keep `config.php` out of version control (use environment variables in production)
3. The `uploads/` directory has PHP execution disabled via `.htaccess`
4. Admin sessions expire after 8 hours
5. All admin forms are CSRF-protected
6. Passwords are hashed with bcrypt (cost 12)

---

## Troubleshooting

**Blank white page / 500 error**
- Enable PHP error display temporarily: add `ini_set('display_errors', 1);` at top of index.php
- Check PHP error logs in cPanel → Error Logs

**Database connection failed**
- Verify DB credentials in `config.php`
- On cPanel, database names must include the cPanel username prefix

**Uploads not working**
- Check that `uploads/` directory exists and is writable (755)
- Check PHP `upload_max_filesize` and `post_max_size` in cPanel → PHP Selector

**Admin login redirects back to login**
- Check that sessions are working (PHP session extension loaded)
- Ensure `APP_URL` in config.php matches your actual domain exactly

---

## Support

- **Website**: https://hasnet.co.tz
- **Email**: info@hasnet.co.tz
- **WhatsApp**: +255 777 019 901
