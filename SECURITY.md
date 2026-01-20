# Security Summary

This project follows secure-by-default practices suitable for student-run Windows environments with XAMPP.

- Passwords: Stored via `password_hash`; verified via `password_verify`.
- DB Access: PDO with prepared statements everywhere. Emulation disabled.
- XSS: Escape dynamic outputs using `includes/functions.php:e()`.
- CSRF: Tokens on every POST form via `includes/csrf.php`.
- Sessions: `session_regenerate_id` on login; `httponly` cookies and `samesite=Lax`.
- RBAC: Student/admin separation via `includes/auth.php`; protected routes enforce role sessions.
- Uploads: Allowlist MIME (`pdf/jpg/png`), max 5MB, randomized filenames, stored under `storage/` (outside `public`).
- Error Handling: No raw SQL errors shown; errors logged to `error.log`.
- Throttling: Simple session-based counters block after 5 failed login attempts.
- IDOR: Download endpoint checks session and resolves file via DB metadata, not user-provided paths.

Potential Enhancements:

- Add server-side rate limiting table with timestamps.
- Integrate captcha for registration/login if abuse is observed.
