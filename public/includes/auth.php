<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function session_boot(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function login_throttle_key(string $email, string $role): string {
    return 'login_attempts_' . $role . '_' . strtolower($email);
}

function increment_login_attempts(string $email, string $role): int {
    session_boot();
    $key = login_throttle_key($email, $role);
    $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
    return $_SESSION[$key];
}

function reset_login_attempts(string $email, string $role): void {
    session_boot();
    $key = login_throttle_key($email, $role);
    $_SESSION[$key] = 0;
}

function too_many_attempts(string $email, string $role): bool {
    session_boot();
    $key = login_throttle_key($email, $role);
    return ($_SESSION[$key] ?? 0) >= 5;
}

function student_login(string $email, string $password): bool {
    session_boot();
    if (too_many_attempts($email, 'student')) {
        return false;
    }
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare('SELECT id, full_name, email, phone, password_hash, status, created_at FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['student'] = [
                'id' => (int)$user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'status' => $user['status'],
                'created_at' => $user['created_at']
            ];
            reset_login_attempts($email, 'student');
            return true;
        }
        increment_login_attempts($email, 'student');
        return false;
    } catch (Throwable $e) {
        log_error('Student login error: ' . $e->getMessage());
        return false;
    }
}

function admin_login(string $email, string $password): bool {
    session_boot();
    if (too_many_attempts($email, 'admin')) {
        return false;
    }
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, created_at FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin'] = [
                'id' => (int)$admin['id'],
                'full_name' => $admin['full_name'],
                'email' => $admin['email'],
                'created_at' => $admin['created_at']
            ];
            reset_login_attempts($email, 'admin');
            return true;
        }
        increment_login_attempts($email, 'admin');
        return false;
    } catch (Throwable $e) {
        log_error('Admin login error: ' . $e->getMessage());
        return false;
    }
}

function require_student_auth(): void {
    session_boot();
    if (empty($_SESSION['student'])) {
        header('Location: ' . BASE_URL . 'student/login.php');
        exit;
    }
}

function require_admin_auth(): void {
    session_boot();
    if (empty($_SESSION['admin'])) {
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}

function student_logout(): void {
    session_boot();
    unset($_SESSION['student']);
    session_regenerate_id(true);
}

function admin_logout(): void {
    session_boot();
    unset($_SESSION['admin']);
    session_regenerate_id(true);
}

function change_admin_password(int $admin_id, string $current_password, string $new_password): bool {
    // Verify current password and update to new password for admin
    try {
        $pdo = get_db_connection();
        
        // Fetch current password hash
        $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();
        
        if (!$admin) {
            return false;
        }
        
        // Verify current password is correct
        if (!password_verify($current_password, $admin['password_hash'])) {
            return false;
        }
        
        // Hash new password and update
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
        $stmt->execute([$new_hash, $admin_id]);
        
        return true;
    } catch (Throwable $e) {
        log_error('Password change error: ' . $e->getMessage());
        return false;
    }
}

?>
