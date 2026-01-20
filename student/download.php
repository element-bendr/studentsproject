<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_student_auth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    exit('Invalid file request');
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT type, filename, mime_type, size FROM uploads WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $file = $stmt->fetch();
    if (!$file) {
        http_response_code(404);
        exit('File not found');
    }
    $baseDir = $file['type'] === 'photo' ? PHOTOS_PATH : NOTES_PATH;
    $path = $baseDir . DIRECTORY_SEPARATOR . $file['filename'];
    if (!is_file($path)) {
        http_response_code(404);
        exit('File missing');
    }
    header('Content-Type: ' . $file['mime_type']);
    header('Content-Length: ' . (string)$file['size']);
    header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
    readfile($path);
    exit;
} catch (Throwable $e) {
    log_error('Download error: ' . $e->getMessage());
    http_response_code(500);
    exit('Server error');
}

?>
