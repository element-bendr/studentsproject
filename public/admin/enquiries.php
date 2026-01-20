<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';

$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$rows = [];
try {
    $pdo = get_db_connection();
    if ($filterEmail) {
        // Use escape_like to prevent LIKE wildcard injection
        $escaped = escape_like(trim($filterEmail));
        $stmt = $pdo->prepare('SELECT name, email, message, created_at FROM enquiries WHERE email LIKE ? ESCAPE \'\\\' ORDER BY created_at DESC');
        $stmt->execute(['%' . $escaped . '%']);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query('SELECT name, email, message, created_at FROM enquiries ORDER BY created_at DESC LIMIT 100')->fetchAll();
    }
} catch (Throwable $e) {
    log_error('Enquiries load error: ' . $e->getMessage());
}
?>

<h1>Enquiries</h1>
<form method="get" class="form">
  <label>Filter by Email
    <input type="text" name="email" value="<?= e($filterEmail) ?>">
  </label>
  <button type="submit" class="btn">Search</button>
</form>

<table class="table">
  <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Received</th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><td><?= e($r['name']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['message']) ?></td><td><?= e($r['created_at']) ?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
