<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_student_auth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If an id is provided, serve the file as a download
if ($id > 0) {
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
}

// No id — show the downloads listing page
$uploads = [];
try {
    $pdo = get_db_connection();
    $uploads = $pdo->query('SELECT id, title, type, mime_type, size, created_at FROM uploads ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
    log_error('Downloads listing error: ' . $e->getMessage());
}

$current_page = 'download.php';
require_once __DIR__ . '/../includes/student_header.php';
?>

<h1>Downloads</h1>

<section class="card">
  <h2>&#128218; Study Materials</h2>
  <?php if ($uploads): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Size</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($uploads as $u): ?>
          <tr>
            <td><?= e($u['title']) ?></td>
            <td><?= e(ucfirst($u['type'])) ?></td>
            <td><?= e(number_format($u['size'] / 1024, 1)) ?> KB</td>
            <td><?= e(date('M d, Y', strtotime($u['created_at']))) ?></td>
            <td><a class="btn" href="download.php?id=<?= e((string)$u['id']) ?>" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Download</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="color: #6B7280; text-align: center; padding: var(--spacing-2xl);">No study materials available yet.</p>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
