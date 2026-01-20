<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $title = sanitize_string($_POST['title'] ?? '');
        $body = sanitize_string($_POST['body'] ?? '');
        $visible = isset($_POST['visible']) ? 1 : 0;
        if (!validate_non_empty($title, 2, 150)) $errors[] = 'Title is required (2-150 chars).';
        if (!validate_non_empty($body, 5, 2000)) $errors[] = 'Body must be 5-2000 characters.';
        if (!$errors) {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare('INSERT INTO notices (title, body, visible_to_students, created_at) VALUES (?, ?, ?, NOW())');
                $stmt->execute([$title, $body, $visible]);
                $success = 'Notice published.';
            } catch (Throwable $e) {
                log_error('Notice publish error: ' . $e->getMessage());
                $errors[] = 'Failed to publish notice.';
            }
        }
    }
}

$recent = [];
try {
    $pdo = get_db_connection();
    $recent = $pdo->query('SELECT title, visible_to_students, created_at FROM notices ORDER BY created_at DESC LIMIT 20')->fetchAll();
} catch (Throwable $e) {
    log_error('Notices list error: ' . $e->getMessage());
}
?>

<h1>Content Manager: Notices</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) echo '<p>' . e($err) . '</p>'; ?></div><?php endif; ?>

<section class="card">
  <h2>New Notice</h2>
  <form method="post" class="form">
    <?= csrf_input() ?>
    <label>Title
      <input type="text" name="title" required>
    </label>
    <label>Body
      <textarea name="body" rows="5" required></textarea>
    </label>
    <label>
      <input type="checkbox" name="visible"> Visible to students
    </label>
    <button type="submit" class="btn">Publish</button>
  </form>
</section>

<section class="card">
  <h2>Recent Notices</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Visible</th><th>Created</th></tr></thead>
    <tbody>
      <?php foreach ($recent as $r): ?>
        <tr><td><?= e($r['title']) ?></td><td><?= e($r['visible_to_students'] ? 'Yes' : 'No') ?></td><td><?= e($r['created_at']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
