# Assumptions

- Courses page uses static fallback initially; DB-backed list can be added later.
- Login throttling uses session counters; can be extended to DB if needed.
- BASE_URL configured per environment (e.g., /student_academy/).
- PHP 7.4+ (or newer) assumed with PDO and password\_\* available.
- Storage directory is within project but outside public; access via download.php.
- Email verification not implemented; mitigated via throttling; captcha optional.
