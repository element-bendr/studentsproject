# Test Plan

## Environment

- Windows + XAMPP

## Setup

1. Import schema.sql
2. Configure includes/config.php
3. Access public/ via localhost

## Happy Paths

- Registration → success row
- Student Login → dashboard
- Dashboard → details, downloads, attendance %, active count
- Contact → enquiry row
- Appointment → appointment row
- Admin Login → dashboard metrics
- Attendance → mark present; duplicate blocked
- Uploads → PDF/JPG/PNG ≤5MB listed
- Notices → published listed
- Enquiries/Appointments → listed + filter

## Security Checks

- SQLi → blocked (prepared statements)
- CSRF → blocked without token
- XSS → escaped
- Session Fixation → regen on login
- Unauthorized → redirected to login
- Upload Abuse → invalid type/oversize blocked
- IDOR → restricted download
