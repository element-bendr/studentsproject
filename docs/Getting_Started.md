# Getting Started (Windows/XAMPP)

## Prerequisites

- XAMPP (Apache + PHP + MySQL)
- VS Code (optional)

## Installation

1. Copy this project to C:\xampp\htdocs\student_academy
2. Access: http://localhost/student_academy/public/
3. Start Apache and MySQL in XAMPP Control Panel.
4. Import database schema:

```bash
mysql -u root -p < schema.sql
```

Or phpMyAdmin → Import → schema.sql.

5. Configure DB credentials:
   - Edit includes/config.php (DB_HOST/DB_NAME/DB_USER/DB_PASS).
6. Set BASE_URL in includes/config.php (e.g., /student_academy/).
7. Default Admin (change immediately):
   - Email: admin@example.com
   - Password: Admin@123

## Optional Apache DocumentRoot

- Update httpd.conf to point DocumentRoot to .../public for direct access at http://localhost/
