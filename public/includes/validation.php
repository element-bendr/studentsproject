<?php
function validate_email(string $email): bool {
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_non_empty(string $value, int $min = 1, int $max = 255): bool {
    $len = mb_strlen(trim($value));
    return $len >= $min && $len <= $max;
}

function validate_password(string $password): bool {
    return mb_strlen($password) >= 8;
}

function sanitize_string(string $value): string {
    return trim($value);
}

?>
