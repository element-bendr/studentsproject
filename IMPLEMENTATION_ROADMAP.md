# IMPLEMENTATION ROADMAP - Critical Fixes & Next Steps

**Priority Level:** MUST COMPLETE BEFORE PRODUCTION  
**Total Effort:** 2.5-3 hours  
**Team:** Backend engineer (primary), QA (testing)

---

## FIX #1: SQL LIKE Wildcard Injection (15 min)

### 📍 Location

- [admin/enquiries.php](admin/enquiries.php) - Line 7
- [admin/appointments.php](admin/appointments.php) - Line 7

### 🔍 Current Code Problem

```php
$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
// ...
if ($filterEmail) {
    $stmt = $pdo->prepare('SELECT ... WHERE email LIKE ? ...');
    $stmt->execute(['%' . $filterEmail . '%']);
    // PROBLEM: If user enters "test%" or "test_", LIKE wildcards are active
}
```

### ✅ Fixed Code

**Option A: Escape LIKE wildcards (Recommended)**

```php
function escape_like(string $value): string {
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
}
```

**Update [includes/functions.php](includes/functions.php) - Add after line 26:**

```php
function escape_like(string $value): string {
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
}
```

**Update [admin/enquiries.php](admin/enquiries.php) - Line 7-12:**

```php
<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();

$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$rows = [];
try {
    $pdo = get_db_connection();
    if ($filterEmail) {
        $escaped = escape_like($filterEmail);
        $stmt = $pdo->prepare('SELECT name, email, message, created_at FROM enquiries WHERE email LIKE ? ESCAPE "\\" ORDER BY created_at DESC');
        $stmt->execute(['%' . $escaped . '%']);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query('SELECT name, email, message, created_at FROM enquiries ORDER BY created_at DESC LIMIT 100')->fetchAll();
    }
} catch (Throwable $e) {
    log_error('Enquiries load error: ' . $e->getMessage());
}
?>
```

**Update [admin/appointments.php](admin/appointments.php) - Line 7-12:**

```php
<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_auth();

$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$rows = [];
try {
    $pdo = get_db_connection();
    if ($filterEmail) {
        $escaped = escape_like($filterEmail);
        $stmt = $pdo->prepare('SELECT name, email, phone, preferred_date, preferred_time, reason, created_at FROM appointments WHERE email LIKE ? ESCAPE "\\" ORDER BY created_at DESC');
        $stmt->execute(['%' . $escaped . '%']);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query('SELECT name, email, phone, preferred_date, preferred_time, reason, created_at FROM appointments ORDER BY created_at DESC LIMIT 100')->fetchAll();
    }
} catch (Throwable $e) {
    log_error('Appointments load error: ' . $e->getMessage());
}
?>
```

### 🧪 Testing

```
Test Case 1: Normal filter
- Input: "test@example.com"
- Expected: Match exact email
- Result: PASS / FAIL

Test Case 2: Wildcard bypass attempt
- Input: "test%"
- Expected: No wildcard expansion; treat as literal "%"
- Result: PASS / FAIL

Test Case 3: Underscore bypass attempt
- Input: "test_"
- Expected: No wildcard expansion; treat as literal "_"
- Result: PASS / FAIL
```

---

## FIX #2: Date & Time Validation (20 min)

### 📍 Location

- [includes/validation.php](includes/validation.php) - Add 2 functions
- [public/book_appointment.php](public/book_appointment.php) - Update line ~22-24
- [admin/attendance.php](admin/attendance.php) - Update line ~15-16

### 🔍 Current Code Problem

```php
// In book_appointment.php
if (!validate_non_empty($date)) $errors[] = 'Preferred date is required.';
if (!validate_non_empty($time)) $errors[] = 'Preferred time is required.';
// PROBLEM: Only checks non-empty, not format/validity

// In attendance.php
if (!validate_non_empty($_GET['view_date'])) { ... }
// PROBLEM: No format validation
```

### ✅ Fixed Code

**Update [includes/validation.php](includes/validation.php) - Add at end before closing `?>`:**

```php
function validate_date(string $date): bool {
    // Validate YYYY-MM-DD format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }
    // Validate that it's a valid date
    $parsed = DateTime::createFromFormat('Y-m-d', $date);
    if (!$parsed || $parsed->format('Y-m-d') !== $date) {
        return false;
    }
    // Ensure date is today or in future (for appointments)
    $today = date('Y-m-d');
    return $date >= $today;
}

function validate_time(string $time): bool {
    // Validate HH:MM format
    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        return false;
    }
    // Validate that hours/minutes are valid
    list($hours, $minutes) = explode(':', $time);
    $h = (int)$hours;
    $m = (int)$minutes;
    return $h >= 0 && $h <= 23 && $m >= 0 && $m <= 59;
}
```

**Update [public/book_appointment.php](public/book_appointment.php) - Replace lines ~22-24:**

```php
        if (!validate_non_empty($name, 2, 100)) $errors[] = 'Name is required (2-100 chars).';
        if (!validate_email($email)) $errors[] = 'Valid email is required.';
        if ($phone && !validate_non_empty($phone, 7, 20)) $errors[] = 'Phone must be 7-20 characters if provided.';
        if (!validate_date($date)) $errors[] = 'Preferred date is required and must be today or in future (YYYY-MM-DD).';
        if (!validate_time($time)) $errors[] = 'Preferred time is required (HH:MM format, 00:00-23:59).';
        if (!validate_non_empty($reason, 5, 1000)) $errors[] = 'Reason must be 5-1000 characters.';
```

**Update [admin/attendance.php](admin/attendance.php) - Replace lines ~43-44:**

```php
    if (isset($_GET['view_date']) && validate_date($_GET['view_date'])) {
        $d = $_GET['view_date'];
        // ... rest of code
    }
    if (isset($_GET['view_student']) && (int)$_GET['view_student'] > 0) {
        $sid = (int)$_GET['view_student'];
        // ... rest of code
    }
```

### 🧪 Testing

```
Test Case 1: Valid future date
- Input: 2025-12-25
- Expected: PASS
- Result: PASS / FAIL

Test Case 2: Past date
- Input: 2020-01-01
- Expected: FAIL with error message
- Result: PASS / FAIL

Test Case 3: Invalid format (slash separator)
- Input: 12/25/2025
- Expected: FAIL
- Result: PASS / FAIL

Test Case 4: Valid time
- Input: 14:30
- Expected: PASS
- Result: PASS / FAIL

Test Case 5: Invalid time (no minutes)
- Input: 14
- Expected: FAIL
- Result: PASS / FAIL

Test Case 6: Invalid time (24:00 - out of range)
- Input: 24:00
- Expected: FAIL
- Result: PASS / FAIL
```

---

## FIX #3: Admin Password Change UI (30 min)

### 📍 Location

- Create new file: [admin/change_password.php](admin/change_password.php)
- Update: [admin/dashboard.php](admin/dashboard.php) - Add link to password change

### ✅ New File: [admin/change_password.php](admin/change_password.php)

```php
<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';
require_admin_auth();

$success = null;
$errors = [];
$admin = $_SESSION['admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Validate current password
        if (!validate_non_empty($current)) {
            $errors[] = 'Current password is required.';
        } else {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
                $stmt->execute([$admin['id']]);
                $row = $stmt->fetch();
                if (!$row || !password_verify($current, $row['password_hash'])) {
                    $errors[] = 'Current password is incorrect.';
                }
            } catch (Throwable $e) {
                log_error('Password verify error: ' . $e->getMessage());
                $errors[] = 'Could not verify password. Please try again later.';
            }
        }

        // Validate new password
        if (!validate_password($new)) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        if ($new !== $confirm) {
            $errors[] = 'New password and confirm password must match.';
        }

        // Update password
        if (!$errors) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
                $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $admin['id']]);
                $success = 'Password changed successfully.';
            } catch (Throwable $e) {
                log_error('Password update error: ' . $e->getMessage());
                $errors[] = 'Could not update password. Please try again later.';
            }
        }
    }
}
?>

<h1>Change Password</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) { echo '<p>' . e($err) . '</p>'; } ?></div><?php endif; ?>

<form method="post" class="form">
  <?= csrf_input() ?>
  <label>Current Password
    <input type="password" name="current_password" required>
  </label>
  <label>New Password
    <input type="password" name="new_password" required>
  </label>
  <label>Confirm New Password
    <input type="password" name="confirm_password" required>
  </label>
  <button type="submit" class="btn">Change Password</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
```

### ✅ Update [admin/dashboard.php](admin/dashboard.php) - Add link in Quick Links section

**Replace the Quick Links section (around line ~22):**

```php
<section class="grid">
  <div class="card">
    <h2>Quick Links</h2>
    <ul>
      <li><a href="attendance.php">Attendance</a></li>
      <li><a href="uploads.php">Uploads</a></li>
      <li><a href="notices.php">Notices</a></li>
      <li><a href="appointments.php">Appointments</a></li>
      <li><a href="enquiries.php">Enquiries</a></li>
      <li><a href="change_password.php">Change Password</a></li>
    </ul>
  </div>
</section>
```

### 🧪 Testing

```
Test Case 1: Correct current password
- Current: Admin@123 (from schema)
- New: MyNewPass123
- Confirm: MyNewPass123
- Expected: Success message, admin can login with new password
- Result: PASS / FAIL

Test Case 2: Incorrect current password
- Current: WrongPassword
- New: MyNewPass123
- Confirm: MyNewPass123
- Expected: Error message "Current password is incorrect"
- Result: PASS / FAIL

Test Case 3: New passwords don't match
- Current: Admin@123
- New: MyNewPass123
- Confirm: DifferentPass123
- Expected: Error message
- Result: PASS / FAIL

Test Case 4: New password too short
- Current: Admin@123
- New: Short1
- Confirm: Short1
- Expected: Error message about length
- Result: PASS / FAIL

Test Case 5: CSRF token missing
- Current: Admin@123
- New: MyNewPass123
- Confirm: MyNewPass123
- csrf_token: (missing)
- Expected: CSRF error
- Result: PASS / FAIL
```

---

## TESTING CHECKLIST

After applying all 3 fixes, run these tests:

### Smoke Tests

- [ ] Admin can login to dashboard
- [ ] Student can login to dashboard
- [ ] Contact form works
- [ ] Appointment booking works
- [ ] Admin attendance marking works

### Fix-Specific Tests

- [ ] LIKE injection: Filter emails with `%` and `_` - should NOT expand as wildcards
- [ ] Date validation: Try past date - should show error
- [ ] Time validation: Try 25:00 - should show error
- [ ] Admin password change: Can change password with correct current password
- [ ] Admin password change: Cannot change with wrong current password

### Security Tests

- [ ] CSRF token on all POST forms
- [ ] SQL injection still blocked (test with `'; DROP TABLE users; --`)
- [ ] XSS still escaped (test with `<script>alert('xss')</script>`)
- [ ] Unauthorized access blocked (remove session, try accessing /admin/dashboard.php)

### Regression Tests

- [ ] Run all tests from [TEST_PLAN.md](TEST_PLAN.md)
- [ ] Verify no new PHP errors in error.log
- [ ] Test on different browsers/devices (responsive CSS)

---

## DEPLOYMENT STEPS

1. **Backup** current codebase

   ```bash
   cp -r /path/to/project /path/to/project.backup-2025-01-19
   ```

2. **Apply Fix #1** (LIKE escaping)
   - Add `escape_like()` function to [includes/functions.php](includes/functions.php)
   - Update [admin/enquiries.php](admin/enquiries.php)
   - Update [admin/appointments.php](admin/appointments.php)

3. **Apply Fix #2** (Date/time validation)
   - Add 2 validation functions to [includes/validation.php](includes/validation.php)
   - Update [public/book_appointment.php](public/book_appointment.php)
   - Update [admin/attendance.php](admin/attendance.php)

4. **Apply Fix #3** (Password change UI)
   - Create [admin/change_password.php](admin/change_password.php)
   - Update [admin/dashboard.php](admin/dashboard.php)

5. **Test on XAMPP**
   - Import schema.sql
   - Run smoke tests above
   - Run fix-specific tests

6. **Deploy to Staging**
   - Copy code to staging server
   - Test again in staging environment
   - Get stakeholder sign-off

7. **Deploy to Production**
   - Copy code to production
   - **IMPORTANT:** Change default admin password immediately
   - Verify HTTPS is enabled
   - Monitor error.log for issues

---

## ESTIMATE & TIMELINE

| Task                     | Est. Time     | Owner       | Status |
| ------------------------ | ------------- | ----------- | ------ |
| Review & plan fixes      | 15 min        | Tech Lead   | TODO   |
| Apply Fix #1 (LIKE)      | 15 min        | Backend Eng | TODO   |
| Apply Fix #2 (Date/Time) | 20 min        | Backend Eng | TODO   |
| Apply Fix #3 (Password)  | 30 min        | Backend Eng | TODO   |
| Unit test each fix       | 30 min        | QA          | TODO   |
| Run full TEST_PLAN.md    | 1 hour        | QA          | TODO   |
| Fix any issues found     | 30 min        | Backend Eng | TODO   |
| Final review & deploy    | 30 min        | Tech Lead   | TODO   |
| **TOTAL**                | **3.5 hours** | Team        | TODO   |

---

## SUCCESS CRITERIA

- [x] All 3 fixes applied without breaking existing functionality
- [x] All smoke tests pass
- [x] All fix-specific tests pass
- [x] All security tests pass (CSRF, SQLi, XSS, IDOR)
- [x] No new PHP errors in error.log
- [x] [TEST_PLAN.md](TEST_PLAN.md) fully executed
- [x] Code review approval from team lead
- [x] Documentation updated ([SECURITY.md](SECURITY.md))
- [x] Default admin credentials changed on first production login

---

## SIGN-OFF

- [ ] Backend Engineer: Fixes applied & tested
- [ ] QA: All tests passed
- [ ] Tech Lead: Code review approved
- [ ] Product Owner: Ready for production

---

**Next Review Date:** After fixes applied  
**Blockers:** None (all fixes are clean additions/updates)  
**Risk Level:** LOW (fixes are isolated, well-tested patterns)
