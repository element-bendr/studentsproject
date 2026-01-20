# Developer Workflow

## Run Locally

- Windows/XAMPP: start Apache/MySQL, navigate to http://localhost/student_academy/public/
- Configure DB in includes/config.php and import schema.sql.

## Testing

- Follow docs/Test_Plan.md.
- Login paths:
  - Student: http://localhost/student/login.php
  - Admin: http://localhost/admin/login.php

## Debugging

- Check error.log at project root (written via functions.php: log_error()).
- Avoid display_errors in production; for local dev, use php.ini and revert after troubleshooting.

## Common Tasks

- Add a page: create PHP in public/student/admin and include header/footer.
- DB ops: always use PDO prepared statements from includes/db.php.
- Forms: include CSRF token via csrf_input() and validate via csrf_validate().
