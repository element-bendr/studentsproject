<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/db.php';

$success = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $name = sanitize_string($_POST['full_name'] ?? '');
        $email = sanitize_string($_POST['email'] ?? '');
        $phone = sanitize_string($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!validate_non_empty($name, 2, 100)) $errors[] = 'Full name is required (2-100 chars).';
        if (!validate_email($email)) $errors[] = 'Valid email is required.';
        if ($phone && !validate_non_empty($phone, 7, 20)) $errors[] = 'Phone must be 7-20 characters if provided.';
        if (!validate_password($password)) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Password and confirm password must match.';

        if (!$errors) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash, status, created_at) VALUES (?, ?, ?, ?, "active", NOW())');
                $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
                $success = 'Registration successful. You can now log in.';
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') { // unique violation
                    $errors[] = 'An account with this email already exists.';
                } else {
                    log_error('Registration error: ' . $e->getMessage());
                    $errors[] = 'Could not complete registration. Please try again later.';
                }
            } catch (Throwable $e) {
                log_error('Registration error: ' . $e->getMessage());
                $errors[] = 'Could not complete registration. Please try again later.';
            }
        }
    }
}
?>

<h1>Student Registration</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) { echo '<p>' . e($err) . '</p>'; } ?></div><?php endif; ?>

<form method="post" class="form">
  <?= csrf_input() ?>
  <label>Full Name
    <input type="text" name="full_name" required>
  </label>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Phone (optional)
    <input type="text" name="phone">
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <label>Confirm Password
    <input type="password" name="confirm_password" required>
  </label>
  <button type="submit" class="btn">Register</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
