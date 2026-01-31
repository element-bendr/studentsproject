<?php
// Process login BEFORE outputting any headers
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $email = sanitize_string($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!validate_email($email) || !validate_non_empty($password)) {
            $error = 'Please provide valid credentials.';
        } else {
            if (too_many_attempts($email, 'admin')) {
                $error = 'Too many login attempts. Close the browser or clear cookies, then try again.';
            } elseif (admin_login($email, $password)) {
                header('Location: ' . BASE_URL . 'admin/dashboard.php');
                exit;
            } else {
                $error = 'Login failed. Please check your credentials or try again later.';
            }
        }
    }
}

// NOW include header (outputs HTML)
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Admin Login</h1>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="form">
  <?= csrf_input() ?>
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button type="submit" class="btn">Login</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
