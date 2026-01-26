<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/csrf.php';

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

        // Validation
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
                $success = 'Student "' . e($name) . '" has been successfully added.';
                // Clear form on success
                $_POST = [];
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') { // unique violation
                    $errors[] = 'An account with this email already exists.';
                } else {
                    log_error('Add student error: ' . $e->getMessage());
                    $errors[] = 'Could not add student. Please try again later.';
                }
            } catch (Throwable $e) {
                log_error('Add student error: ' . $e->getMessage());
                $errors[] = 'Could not add student. Please try again later.';
            }
        }
    }
}
?>

<div class="content-header">
    <h1>Add New Student</h1>
    <p>Create a new student account from the admin panel</p>
</div>

<?php if ($success): ?>
    <div class="alert success"><?= e($success) ?></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert error">
        <?php foreach ($errors as $err): ?>
            <p><?= e($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Student Registration Form</h2>
    </div>
    <div class="card-body">
        <form method="post" class="form">
            <?= csrf_input() ?>
            
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" value="<?= e($_POST['full_name'] ?? '') ?>" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label for="phone">Phone (optional)</label>
                <input type="tel" id="phone" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="1234567890">
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required placeholder="Min 8 characters">
                <small>Password must be at least 8 characters long</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repeat password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Add Student</button>
                <a href="users.php" class="btn btn-secondary">View All Students</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input {
    width: 100%;
    max-width: 400px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
}

.form-group small {
    display: block;
    margin-top: 4px;
    color: #666;
    font-size: 12px;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert p {
    margin: 5px 0;
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
