# Product Manager Output

## User Stories & Acceptance Criteria

- As a visitor, I can view Home, About (with Class History & Achievements), Courses, Contact so I can learn and get in touch.
  - Accepts: pages load; Contact form saves to `enquiries` and shows success.
- As a student, I can register with Full Name, unique Email, optional Phone, Password/Confirm so I can access the portal.
  - Accepts: server validation; `password_hash`; duplicates rejected; `status=active`.
- As a student, I can log in securely so I can view my dashboard.
  - Accepts: CSRF on POST; session regeneration; throttling after 5 failed attempts.
- As a student, I can see my details, downloads (notes/photos), my attendance history and percentage, and active student count.
  - Accepts: recent attendance shown; percentage computed; downloads list protected; active count reflects `status=active`.
- As a visitor/student, I can book an appointment so admins can schedule meetings.
  - Accepts: CSRF + validation; saves to `appointments`; admin list & filter.
- As an admin, I can log in securely and see metrics.
  - Accepts: dashboard cards for users, active users, appointments, enquiries, uploads.
- As an admin, I can mark attendance and view by date/student without duplicates.
  - Accepts: unique constraint `(student_id,date)`; friendly error on duplicate.
- As an admin, I can upload notes/photos and post notices visible to students.
  - Accepts: uploads with allowlist and random filenames; notices persisted and shown on student dashboard.
- As an admin, I can view contact enquiries and appointment bookings with search/filter.
  - Accepts: tables with optional email filter.

## Sitemap & Roles

- Public
  - /public/index.php
  - /public/about.php
  - /public/courses.php
  - /public/contact.php
  - /public/book_appointment.php
- Student (auth required except register/login)
  - /student/register.php
  - /student/login.php
  - /student/dashboard.php
  - /student/download.php
  - /student/logout.php
- Admin (auth required except login)
  - /admin/login.php
  - /admin/dashboard.php
  - /admin/attendance.php
  - /admin/uploads.php
  - /admin/notices.php
  - /admin/enquiries.php
  - /admin/appointments.php
  - /admin/logout.php

## Active Student Definition

- `users.status='active'`. Set on registration; optional administrative deactivation.
