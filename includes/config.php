<?php
// Platform-agnostic configuration
// Update these values for your local XAMPP/MySQL setup.

// Allow overriding via environment variables for Docker compatibility while keeping XAMPP defaults
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'student_academy');
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Paths (use DIRECTORY_SEPARATOR for cross-platform)
$__rootPath = dirname(__DIR__);
if (basename($__rootPath) === 'public') {
    $__rootPath = dirname($__rootPath);
}
define('ROOT_PATH', $__rootPath);
unset($__rootPath);

// App settings
define('APP_NAME', 'Student Academy Portal');
// BASE_URL should point to the project root URL (the folder that contains /public, /admin, /student).
// Example in XAMPP: http://localhost/studentproject/  => BASE_URL = /studentproject/
if (!defined('BASE_URL')) {
    $envBaseUrl = getenv('BASE_URL') ?: '';
    if (is_string($envBaseUrl) && trim($envBaseUrl) !== '') {
        $baseUrl = trim($envBaseUrl);
        if ($baseUrl[0] !== '/') {
            $baseUrl = '/' . $baseUrl;
        }
        if (substr($baseUrl, -1) !== '/') {
            $baseUrl .= '/';
        }
    } else {
        $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $docRoot = rtrim(str_replace('\\', '/', (string)$docRoot), '/');

        $pathForBase = ROOT_PATH;
        // If this config lives under /public/includes, ROOT_PATH will be .../public.
        // The project root is one level up.
        if (basename($pathForBase) === 'public') {
            $pathForBase = dirname($pathForBase);
        }
        $pathForBase = str_replace('\\', '/', $pathForBase);

        $baseUrl = '/';
        if ($docRoot !== '' && strpos($pathForBase, $docRoot) === 0) {
            $relative = substr($pathForBase, strlen($docRoot));
            $relative = '/' . trim((string)$relative, '/');
            $baseUrl = ($relative === '/') ? '/' : ($relative . '/');
        }
    }
    define('BASE_URL', $baseUrl);
}

// Paths (use DIRECTORY_SEPARATOR for cross-platform)
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define('INCLUDES_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'includes');
define('ASSETS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'assets');
define('STORAGE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'storage');
define('NOTES_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'notes');
define('PHOTOS_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'photos');

// When Apache serves /public as DocumentRoot (Dockerfile default), public pages are at BASE_URL + "index.php".
// When /public is a subfolder under the project root (common XAMPP setup), public pages are under BASE_URL + "public/".
$__docRootReal = realpath((string)($_SERVER['DOCUMENT_ROOT'] ?? ''));
$__publicPathReal = realpath(PUBLIC_PATH);
$__publicPrefix = ($__docRootReal && $__publicPathReal && $__docRootReal === $__publicPathReal) ? '' : 'public/';
define('PUBLIC_URL_PREFIX', $__publicPrefix);
unset($__docRootReal, $__publicPathReal, $__publicPrefix);

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
