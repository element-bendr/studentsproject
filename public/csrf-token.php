<?php
/**
 * CSRF token endpoint for HTML forms.
 * HTML pages cannot use PHP includes, so they fetch a CSRF token from this
 * endpoint via JavaScript before submitting forms to PHP handlers.
 *
 * Returns JSON: {"token": "<64-char-hex>"}
 * Only GET requests are accepted.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}

// Start session so the token persists for the subsequent POST
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header('Content-Type: application/json');
header('Cache-Control: no-store');
echo json_encode(['token' => $_SESSION['csrf_token']]);
