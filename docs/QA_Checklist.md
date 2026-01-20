# QA Verification Checklist

## Requirements Coverage

- Public pages present: public/index.php, about.php, courses.php, contact.php, book_appointment.php
- Student portal: student/register.php, login.php, dashboard.php, download.php, logout.php
- Admin panel: admin/login.php, dashboard.php, attendance.php, uploads.php, notices.php, enquiries.php, appointments.php, logout.php
- DB schema: schema.sql tables for users/admins/attendance/uploads/notices/appointments/enquiries
- Security controls: CSRF (includes/csrf.php), prepared statements (includes/db.php usage), escaping (includes/functions.php:e), RBAC (includes/auth.php), uploads safe pipeline (admin/uploads.php), session hardening
- Docs: README.md, docs/\*, SECURITY.md, TEST_PLAN.md

## Spot Checks

- Prepared statements only (search for ->prepare and avoid query with user input)
- CSRF tokens present on POST forms
- Session regeneration on login
- Attendance uniqueness enforced
- Upload allowlist + random filenames
- No direct file serving from public
