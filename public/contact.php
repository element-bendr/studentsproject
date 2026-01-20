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
        $message = sanitize_string($_POST['message'] ?? '');

        if (!validate_non_empty($name, 2, 100)) $errors[] = 'Name is required (2-100 chars).';
        if (!validate_email($email)) $errors[] = 'Valid email is required.';
        if (!validate_non_empty($message, 5, 1000)) $errors[] = 'Message must be 5-1000 characters.';

        if (!$errors) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('INSERT INTO enquiries (name, email, message, created_at) VALUES (?, ?, ?, NOW())');
                $stmt->execute([$name, $email, $message]);
                $success = 'Thanks! Your enquiry has been received.';
            } catch (Throwable $e) {
                log_error('Contact form error: ' . $e->getMessage());
                $errors[] = 'Could not submit your enquiry. Please try again later.';
            }
        }
    }
}
?>

<h1>Contact Us</h1>
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
  <label>Message
    <textarea name="message" rows="5" required></textarea>
  </label>
  <button type="submit" class="btn">Send</button>
  </form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
