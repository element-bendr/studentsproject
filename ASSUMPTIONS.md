# Assumptions

This project documents assumptions at [docs/Assumptions.md](docs/Assumptions.md). Key points:

- Courses page uses a static fallback initially; DB-backed list is optional.
- Login throttling uses session counters; can be extended to DB.
- BASE_URL configured per environment (e.g., /student_academy/).
- PHP 7.4+ assumed; PDO and password\_\* available.
- Storage directory is outside public; files served via student/download.php.
- Email verification omitted; mitigated via throttling; captcha optional.# Assumptions

- Courses page uses a static fallback initially. Database integration for courses can be added later; current schema omits a `courses` table.
- Apache DocumentRoot points to `public/` for secure routing. If running under `htdocs`, use the URL path to reach `public/`.
- `BASE_URL` in `includes/config.php` may need to be set to your folder prefix on Windows (e.g., `/student_academy/`).
- Default admin account is seeded via `schema.sql`; password should be changed immediately after first login.
- Login throttling uses session counters; for multi-user environments, consider a DB-based rate limiting table.
