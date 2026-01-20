# Architecture

## Components

- Public: public/index.php, about.php, courses.php, contact.php, book_appointment.php
- Student: student/register.php, login.php, dashboard.php, download.php, logout.php
- Admin: admin/login.php, dashboard.php, attendance.php, uploads.php, notices.php, enquiries.php, appointments.php, logout.php
- Includes: includes/config.php, db.php (PDO), auth.php, csrf.php, validation.php, functions.php, header.php, footer.php
- Assets: assets/css/style.css, assets/js/app.js
- Storage: storage/notes, storage/photos (served via student/download.php)

## Data Flow

- Forms (contact, appointment, registration, login, admin actions) → CSRF validate → server-side validation → prepared statement insert/select → success/error feedback.
- Session: auth.php manages login/logout; session_regenerate_id on login; role-guard via require_student_auth/require_admin_auth.
- Uploads: uploads.php → validate MIME/size → random filename → storage/\* → DB metadata saved; downloads via dashboard + download.php.

## Rationale

- Procedural PHP: simple, XAMPP-friendly for students.
- Security baked in: CSRF, escaping, prepared statements, RBAC, safe file IO.
- Paths use DIRECTORY_SEPARATOR in config for cross-platform.
