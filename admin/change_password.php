<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';
require_admin_auth();

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!$current_password) {
            $errors[] = 'Current password is required.';
        }
        if (!$new_password) {
            $errors[] = 'New password is required.';
        } elseif (!validate_password($new_password)) {
            $errors[] = 'Password must be at least 8 characters with uppercase, lowercase, and number.';
        }
        if ($new_password !== $confirm_password) {
            $errors[] = 'New passwords do not match.';
        }
        if ($current_password === $new_password) {
            $errors[] = 'New password must be different from current password.';
        }

        if (!$errors) {
            if (change_admin_password($_SESSION['admin']['id'], $current_password, $new_password)) {
                $success = 'Your password has been changed successfully.';
                // Clear form
                $_POST = [];
            } else {
                $errors[] = 'Current password is incorrect.';
            }
        }
    }
}
?>

<h1>Change Password</h1>

<?php if ($success): ?>
    <div class="alert success">
        <p><?= e($success) ?></p>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert error">
        <?php foreach ($errors as $err): ?>
            <p><?= e($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" class="form card">
    <?= csrf_input() ?>
    
    <label>Current Password
        <input type="password" name="current_password" required autocomplete="current-password">
    </label>
    
    <label>New Password
        <input type="password" name="new_password" required autocomplete="new-password">
        <small>At least 8 characters with uppercase, lowercase, and number</small>
    </label>
    
    <label>Confirm New Password
        <input type="password" name="confirm_password" required autocomplete="new-password">
    </label>
    
    <button type="submit" class="btn">Change Password</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
