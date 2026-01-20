# Security Summary

- Passwords: password_hash / password_verify.
- DB: PDO prepared statements; emulation disabled.
- XSS: escape outputs via includes/functions.php:e().
- CSRF: tokens on every POST form via includes/csrf.php.
- Sessions: session_regenerate_id on login; httponly; samesite=Lax.
- RBAC: role guards in includes/auth.php.
- Uploads: allowlist pdf/jpg/png; max 5MB; random filenames; stored under storage/.
- Errors: no raw SQL errors; log to error.log.
- Throttling: simple session counters block after 5 failed attempts.
- IDOR: downloads resolved via DB metadata; no direct paths.

See SECURITY.md for the root summary.
