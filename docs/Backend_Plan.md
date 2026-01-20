# Backend Plan

## Architecture Notes

- Procedural PHP pages per route; common includes for config, DB, auth, CSRF, validation, layout.
- DB via PDO (`includes/db.php`) with exceptions enabled and prepared statements only.
- CSRF (`includes/csrf.php`): token generation/validation; hidden input helper.
- Auth (`includes/auth.php`): session boot, login/logout, throttling, role guards, session ID regeneration.
- Validation (`includes/validation.php`): email, non-empty, password, sanitization.
- Helpers (`includes/functions.php`): escaping `e()`, error logging, redirects, upload validation, random filenames.
- File uploads: admin/uploads.php pipeline → MIME allowlist, size limit, random filename → storage/notes|photos → DB metadata.
- RBAC: require_student_auth / require_admin_auth on protected pages.
- Error handling: log to `error.log`; generic user-facing messages.

## Routes & Responsibilities

- Public: index, about, courses (static fallback), contact (save enquiries), book_appointment (save appointments).
- Student: register (create users), login (session), dashboard (details, attendance, downloads, notices), download (serve files), logout.
- Admin: login, dashboard (metrics), attendance (mark + views), uploads (manage content), notices (publish), enquiries (list/filter), appointments (list/filter), logout.
