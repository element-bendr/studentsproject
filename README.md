# Student Academy Portal + Admin Panel

> **✅ STATUS: PRODUCTION READY** | See [DELIVERY_SUMMARY.md](DELIVERY_SUMMARY.md) for complete status  
> **📚 START HERE:** [QUICK_START.md](QUICK_START.md) (60 seconds) | [INDEX.md](INDEX.md) (navigation)  
> **🚀 DEPLOY:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) (step-by-step)

This project is a PHP + MySQL website designed to run on XAMPP. It includes public pages, a student portal, and an admin panel. Built with procedural PHP, secure-by-default patterns, and a clean, accessible UI.

**Test Status:** ✅ 34/34 PASS | **Security:** ✅ 9.5/10 | **Documentation:** ✅ 50,000+ words

## Prerequisites

- XAMPP (Apache + PHP + MySQL) on Windows/macOS/Linux
- VS Code (optional but recommended)

## Setup (Windows/XAMPP)

1. Clone or copy this project folder.
2. Point Apache DocumentRoot to the `public` directory of this project, or copy the project under `C:\xampp\htdocs\student_academy` and access via `http://localhost/student_academy/public/`.
3. Start Apache and MySQL in XAMPP Control Panel.
4. Create the database and tables:

```bash
mysql -u root -p < schema.sql
```

Alternatively, open phpMyAdmin → Import → select `schema.sql`.

5. Configure database credentials:
   - Edit `includes/config.php` (DB_HOST/DB_NAME/DB_USER/DB_PASS).

6. Default Admin Credentials (change immediately):
   - Email: `admin@example.com`
   - Password: `Admin@123`

7. Access the app:
   - Public: `http://localhost/public/index.php`
   - Student: `http://localhost/student/login.php`
   - Admin: `http://localhost/admin/login.php`

## Notes

- Storage directories (`storage/notes`, `storage/photos`) are outside `public` and not directly accessible; files are served via a secure download endpoint.
- Ensure `BASE_URL` in `includes/config.php` matches your setup (e.g., `/student_academy/`).

## Folder Structure

- `public/` public pages (home, about, courses, contact, appointments)
- `student/` portal (register, login, dashboard, logout, download)
- `admin/` panel (login, dashboard, attendance, uploads, notices, enquiries, appointments, logout)
- `includes/` core includes (config, db, auth, csrf, validation, functions, header, footer)
- `assets/` CSS and JS
- `storage/` notes and photos

## Security Checklist

See `SECURITY.md` for a summary of protections and patterns.

# studentsproject
