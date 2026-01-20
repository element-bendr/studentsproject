<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/validation.php';
require_once __DIR__ . '/includes/db.php';

$success = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $name = sanitize_string($_POST['name'] ?? '');
        $email = sanitize_string($_POST['email'] ?? '');
        $phone = sanitize_string($_POST['phone'] ?? '');
        $date = sanitize_string($_POST['preferred_date'] ?? '');
        $time = sanitize_string($_POST['preferred_time'] ?? '');
        $reason = sanitize_string($_POST['reason'] ?? '');

        if (!validate_non_empty($name, 2, 100)) $errors[] = 'Name is required (2-100 chars).';
        if (!validate_email($email)) $errors[] = 'Valid email is required.';
        if ($phone && !validate_non_empty($phone, 7, 20)) $errors[] = 'Phone must be 7-20 characters if provided.';
        if (!validate_date($date, true)) $errors[] = 'Preferred date must be in YYYY-MM-DD format and be a future date.';
        if (!validate_time($time)) $errors[] = 'Preferred time must be in HH:MM format (00:00-23:59).';
        if (!validate_non_empty($reason, 5, 1000)) $errors[] = 'Reason must be 5-1000 characters.';

        if (!$errors) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('INSERT INTO appointments (name, email, phone, preferred_date, preferred_time, reason, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$name, $email, $phone, $date, $time, $reason]);
                $success = 'Your appointment request has been submitted.';
            } catch (Throwable $e) {
                log_error('Appointment form error: ' . $e->getMessage());
                $errors[] = 'Could not submit your request. Please try again later.';
            }
        }
    }
}
?>

<h1>Book an Appointment</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) { echo '<p>' . e($err) . '</p>'; } ?></div><?php endif; ?>

<form method="post" class="form">
  <?= csrf_input() ?>
  <label>Name
    <input type="text" name="name" required>
  </label>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Phone (optional)
    <input type="text" name="phone">
  </label>
  <label>Preferred Date
    <input type="date" name="preferred_date" required>
  </label>
  <label>Preferred Time
    <input type="time" name="preferred_time" required>
  </label>
  <label>Reason
    <textarea name="reason" rows="4" required></textarea>
  </label>
  <button type="submit" class="btn">Submit</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
