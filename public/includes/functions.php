<?php
require_once __DIR__ . '/config.php';

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function log_error(string $message): void {
    $line = '[' . date('c') . '] ' . $message . PHP_EOL;
    @file_put_contents(ERROR_LOG_FILE, $line, FILE_APPEND | LOCK_EX);
}

function random_filename(string $extension = ''): string {
    $name = bin2hex(random_bytes(16));
    return $extension ? ($name . '.' . $extension) : $name;
}

function is_allowed_upload(string $mime, int $size): bool {
    $allowed = ['application/pdf', 'image/jpeg', 'image/png'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    return in_array($mime, $allowed, true) && $size > 0 && $size <= $maxSize;
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function escape_like(string $value): string {
    // Escape SQL LIKE wildcard characters (% and _) to prevent LIKE injection
    // This must be used with ESCAPE clause in SQL: WHERE field LIKE ? ESCAPE '\\'
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
}

function validate_date(string $date, bool $require_future = false): bool {
    // Validate date format (YYYY-MM-DD) and optionally check it's in future
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return false;
    }
    if ($require_future && $timestamp < strtotime('today')) {
        return false;
    }
    return true;
}

function validate_time(string $time): bool {
    // Validate time format (HH:MM or HH:MM:SS) with valid hour/minute values
    if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])(?::[0-5][0-9])?$/', $time)) {
        return false;
    }
    return true;
}

?>
