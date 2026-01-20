<?php
// Platform-agnostic configuration
// Update these values for your local XAMPP/MySQL setup.

// Allow overriding via environment variables for Docker compatibility while keeping XAMPP defaults
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'student_academy');
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// App settings
define('APP_NAME', 'Student Academy Portal');
define('BASE_URL', '/'); // Set to the web root of /public in your XAMPP config

// Paths (use DIRECTORY_SEPARATOR for cross-platform)
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define('INCLUDES_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'includes');
define('ASSETS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'assets');
define('STORAGE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'storage');
define('NOTES_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'notes');
define('PHOTOS_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'photos');

// Session and security
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
// SameSite is best set in php.ini for older PHP versions; modern PHP supports session.cookie_samesite
if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// Error logging
define('ERROR_LOG_FILE', ROOT_PATH . DIRECTORY_SEPARATOR . 'error.log');

?>
