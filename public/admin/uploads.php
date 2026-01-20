<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $title = sanitize_string($_POST['title'] ?? '');
        $type = sanitize_string($_POST['type'] ?? '');
        if (!validate_non_empty($title, 2, 150)) $errors[] = 'Title is required (2-150 chars).';
        if (!in_array($type, ['note', 'photo'], true)) $errors[] = 'Type must be note or photo.';
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) $errors[] = 'Valid file is required.';

        if (!$errors) {
            $tmp = $_FILES['file']['tmp_name'];
            $size = (int)$_FILES['file']['size'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);
            if (!is_allowed_upload($mime, $size)) {
                $errors[] = 'File type/size not allowed.';
            } else {
                $ext = $mime === 'application/pdf' ? 'pdf' : ($mime === 'image/jpeg' ? 'jpg' : 'png');
                $filename = random_filename($ext);
                $destDir = $type === 'photo' ? PHOTOS_PATH : NOTES_PATH;
                $dest = $destDir . DIRECTORY_SEPARATOR . $filename;
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                if (!move_uploaded_file($tmp, $dest)) {
                    $errors[] = 'Failed to save file.';
                } else {
                    try {
                        $pdo = get_db_connection();
                        $stmt = $pdo->prepare('INSERT INTO uploads (title, type, filename, mime_type, size, uploaded_by_admin_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                        $stmt->execute([$title, $type, $filename, $mime, $size, $_SESSION['admin']['id']]);
                        $success = 'Upload saved successfully.';
                    } catch (Throwable $e) {
                        log_error('Upload save error: ' . $e->getMessage());
                        $errors[] = 'Failed to record upload.';
                    }
                }
            }
        }
    }
}

$recent = [];
try {
    $pdo = get_db_connection();
    $recent = $pdo->query('SELECT id, title, type, filename, created_at FROM uploads ORDER BY created_at DESC LIMIT 20')->fetchAll();
} catch (Throwable $e) {
    log_error('Uploads list error: ' . $e->getMessage());
}
?>

<h1>Content Manager: Uploads</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) echo '<p>' . e($err) . '</p>'; ?></div><?php endif; ?>

<section class="card">
  <h2>New Upload</h2>
  <form method="post" enctype="multipart/form-data" class="form">
    <?= csrf_input() ?>
    <label>Title
      <input type="text" name="title" required>
    </label>
    <label>Type
      <select name="type" required>
        <option value="note">Note (PDF)</option>
        <option value="photo">Photo (JPG/PNG)</option>
      </select>
    </label>
    <label>File
      <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
    </label>
    <button type="submit" class="btn">Upload</button>
  </form>
</section>

<section class="card">
  <h2>Recent Uploads</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Type</th><th>Filename</th><th>Created</th></tr></thead>
    <tbody>
      <?php foreach ($recent as $r): ?>
        <tr><td><?= e($r['title']) ?></td><td><?= e($r['type']) ?></td><td><?= e($r['filename']) ?></td><td><?= e($r['created_at']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
