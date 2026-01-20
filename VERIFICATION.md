# Dockerization Verification Guide

This document describes the steps to verify the Dockerized Student Academy Portal.

Prerequisites
- Docker Engine and Docker Compose v2 installed on Pop!_OS
- Project cloned to local machine
- From project root: `pwd` points to the project directory containing `docker-compose.yml`

Quick start
1. Copy example env:

   cp .env.example .env
   # Edit `.env` to set passwords you control (do not commit .env)

2. Build and start:

   docker compose up --build -d

3. Observe build and startup logs. Expected: build completes and both `web` and `db` containers start.

Runtime Checks (A)
- Command: `docker compose ps`
  - Expected: two services `web` and `db` listed. `web` should expose `0.0.0.0:8080->80/tcp`.
- Command: `docker compose logs -f --tail=50 web` and `docker compose logs -f --tail=50 db`
  - Expected: no fatal errors; MySQL shows readiness and the web container starts Apache without fatal error.
- Health checks:
  - `docker inspect --format='{{.State.Health.Status}}' studentproject_web_1` → `healthy` (or `starting` shortly after startup)
- App reachable at: http://localhost:8080  (should load homepage)
- Admin login: use seeded admin credentials:
  - Email: `admin@example.com`
  - Password: `Admin@123`
  - Expected: login succeeds and `/admin/dashboard.php` loads
- Student login/registration:
  - Register a new student or use existing student; login should succeed and `/student/dashboard.php` should be accessible

Persistence Checks (B)
1. Create records:
  - Create a student account and/or mark attendance and note the details (e.g., Student email and attendance date)
  - Create at least one appointment or enquiry
2. Restart containers:
  - `docker compose down`
  - `docker compose up -d`
3. Verify:
  - Data persists (e.g., student exists, attendance rows still present, appointment still visible)

File Upload Checks (C)
1. Login as admin → Admin → Uploads
2. Upload a file (note or photo) via the UI
3. Expected:
  - File saved under project `storage/notes` or `storage/photos`
  - File metadata present in `uploads` table
4. Restart containers and verify file and metadata are still accessible and downloadable

Security Sanity Checks (D)
- CSRF tokens: All POST forms should include a CSRF hidden input and server-side validation should reject POST without token
  - Test: Remove the CSRF token field from a POST (or submit a forged request) and verify the server rejects the request (friendly error)
- Unauthorized admin access: Access an admin page such as `/admin/dashboard.php` without logging in. Expected: redirect to `/admin/login.php` (or access blocked)
- DB credentials are not hardcoded: The container uses env vars and `includes/config.php` now first checks env vars. Ensure `.env` is used instead of hardcoded credentials.

Notes & Helpful Commands
- View container logs:
  - `docker compose logs -f web`
- Access a shell inside the web container:
  - `docker compose exec web bash`
- Inspect MySQL data:
  - `docker compose exec db mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE}`

PASS/FAIL Checklist
- [ ] docker compose up completes without errors
- [ ] `web` and `db` containers healthy
- [ ] Homepage reachable at `http://localhost:8080`
- [ ] Admin login works (`admin@example.com` / `Admin@123`)
- [ ] Student login works
- [ ] Data persists after `docker compose down` + `up`
- [ ] Uploaded files persist and are downloadable after restart
- [ ] CSRF tokens are required and enforced
- [ ] Unauthorized admin access is blocked/redirected
- [ ] Database credentials are provided via environment variables (`.env`) and not hardcoded

Troubleshooting Tips
- If MySQL fails to initialize, check `docker compose logs db` for schema import errors.
- If DB seeding didn't occur, ensure `schema.sql` is present and correct in project root (`./schema.sql` is mounted into `/docker-entrypoint-initdb.d/schema.sql`).

Cleanup
- Tear down the stack and remove volumes (if you want a clean DB):

  docker compose down -v


-- End --
