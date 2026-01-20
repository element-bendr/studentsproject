# Database Schema Overview

See schema.sql for full definitions.

## Tables

- users: id, full_name, email (unique), phone, password_hash, status, created_at
- admins: id, full_name, email (unique), password_hash, created_at
- attendance: id, student_id (FK users), date (unique with student_id), status, marked_by_admin_id (FK admins)
- uploads: id, title, type (note/photo), filename, mime_type, size, uploaded_by_admin_id (FK admins), created_at
- notices: id, title, body, visible_to_students, created_at
- appointments: id, name, email, phone, preferred_date, preferred_time, reason, created_at
- enquiries: id, name, email, message, created_at

## Constraints & Indexes

- Uniqueness: users.email, admins.email, attendance(student_id,date)
- Indexes: attendance.date, uploads.type, notices.visible_to_students, appointments.email, enquiries.email
