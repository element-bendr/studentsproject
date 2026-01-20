# AI Coding Agent Instructions

These instructions make AI agents immediately productive in this repo. The authoritative requirements live in [prompt/mainprompt.md](../prompt/mainprompt.md). Follow that document precisely, including its output order and security mandates.

## Scope & Source of Truth

- Purpose: Build a complete PHP + MySQL website (XAMPP-compatible) with public pages, a student portal, and an admin panel.
- Reference: Read and obey [prompt/mainprompt.md](../prompt/mainprompt.md). Do not invent frameworks or dev stacks outside its constraints.
- Output format: Emit artifacts in the exact order specified, using fenced code blocks labelled with their relative file paths.

## Architecture & Structure

- Use the prescribed layout (procedural PHP, minimal structure):
  - `/public`: `index.php`, `about.php`, `courses.php`, `contact.php`, `book_appointment.php`
  - `/student`: `register.php`, `login.php`, `dashboard.php`, `logout.php`
  - `/admin`: `login.php`, `dashboard.php`, `attendance.php`, `uploads.php`, `notices.php`, `enquiries.php`, `appointments.php`, `logout.php`
  - `/includes`: `config.php`, `db.php`, `auth.php`, `csrf.php`, `validation.php`, `functions.php`, `header.php`, `footer.php`
  - `/assets`: `css/style.css`, `js/app.js`
  - `/storage` or `/uploads`: `notes/`, `photos/`
  - Top-level docs: `schema.sql`, `README.md`, `SECURITY.md`, `TEST_PLAN.md`, `ASSUMPTIONS.md` (optional)
- Data model (from prompt): tables for `users`, `admins`, `attendance`, `uploads`, `notices`, `appointments`, `enquiries`; include indexes, constraints, and `created_at` timestamps.

## Security & Patterns (Mandatory)

- Database: prepared statements everywhere (mysqli or PDO). No string interpolation.
- Passwords: `password_hash` for storage; `password_verify` on login.
- Output escaping: escape all dynamic content in views to prevent XSS.
- CSRF: token generation/verification for all POST forms via `includes/csrf.php`.
- Sessions: `session_regenerate_id` on login; cookies `httponly` and `samesite` when possible.
- RBAC: student endpoints require student session; admin endpoints require admin session.
- Uploads: type allowlist (pdf/jpg/png), size limits, safe randomized filenames, and avoid serving raw paths; prefer storage outside web root or protect via `.htaccess`.
- Error handling: never show raw SQL errors to users; log to file.
- Throttling: basic rate limiting for login attempts.

## Developer Workflow

- Local runtime: XAMPP (Apache + PHP + MySQL). Point DocumentRoot to `/public` or place project under `htdocs` and route to public files.
- Database setup: import `schema.sql` into MySQL.

```bash
# Example import (adjust credentials as needed)
mysql -u root -p < schema.sql
```

- Configuration: keep DB credentials in `includes/config.php`; centralize connection in `includes/db.php`.
- Layout: include `includes/header.php` and `includes/footer.php` for consistent UI; keep styles in `assets/css/style.css`.

## Cross-Component Behavior

- Student dashboard: personal details, downloads, attendance history/percentage, active student count.
- Admin dashboard: metrics cards (users, active users, appointments, enquiries, uploads), attendance manager, content manager (uploads, notices), bookings/enquiries views with search/filter.
- Booking & contact forms: save to DB, show friendly success messages.

## Output Order & Verification

- Produce artifacts in the exact sequence defined in [prompt/mainprompt.md](../prompt/mainprompt.md): PM → UX → DB → backend plan → full code → README → SECURITY → TEST_PLAN → QA verification matrix + PASS/FAIL.
- QA must fail if any requirement/security control is missing; iterate until PASS.
- Ambiguities: choose reasonable defaults and record them in `ASSUMPTIONS.md`.

## Practical Implementation Notes

- Keep to procedural PHP; small helpers in `includes/*.php` are fine.
- Prefer server-side validation; JS only for UX niceties (e.g., form hints). Security is never delegated to JS.
- Use consistent, modern, accessible UI (responsive CSS, labels, focus states, readable contrast, keyboard navigation).

If any part of these instructions is unclear or incomplete given your current task, please comment with the specific gap and I’ll update this doc.
