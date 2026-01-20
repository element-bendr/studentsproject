# Test Plan

## Environment

- Windows + XAMPP (Apache/PHP/MySQL)

## Setup

1. Import `schema.sql` into MySQL.
2. Configure DB creds in `includes/config.php`.
3. Set Apache DocumentRoot to `public/` or run via `http://localhost/<project>/public/`.

## Happy Paths

- Registration: Fill all fields → success message → user row created with `status=active`.
- Login (student): Valid credentials → redirected to `student/dashboard.php`; session set.
- Dashboard: Sees details, downloads list, attendance percentage, active count.
- Contact: Submit valid form → row in `enquiries`; success alert.
- Appointment: Submit valid form → row in `appointments`; success alert.
- Admin Login: Valid credentials → `admin/dashboard.php`; metrics visible.
- Attendance: Mark student present for today → success; duplicate attempt → friendly error.
- Uploads: Upload PDF/JPG/PNG ≤5MB → saved to `storage` and `uploads` row; appears in recent list.
- Notices: Publish notice → appears in recent list.
- Enquiries/Appointments: Lists load and filter by email.

## Security Checks

- SQL Injection: Attempt to inject in any form → no break; inputs handled by prepared statements.
- CSRF: Submit POST without token → rejected.
- XSS: Displayed data escaped; try `<script>` in fields → rendered harmless.
- Session Fixation: Login regenerates session ID.
- Unauthorized Access: Access admin/student pages without session → redirected to login.
- Upload Abuse: Try `.exe` or oversized file → rejected.
- IDOR: Attempt to download arbitrary path via `student/download.php?id=...` → restricted to DB-recorded files only.

## Regression

- Re-test happy paths after any change to includes or database.
