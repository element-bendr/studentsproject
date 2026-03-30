<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_admin_auth();

$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$rows = [];
try {
    $pdo = get_db_connection();
    if ($filterEmail) {
        // Use escape_like to prevent LIKE wildcard injection
        $escaped = escape_like(trim($filterEmail));
        $stmt = $pdo->prepare('SELECT e.id, e.student_id, u.full_name AS student_name, e.name, e.email, e.message, e.created_at FROM enquiries e LEFT JOIN users u ON u.id = e.student_id WHERE e.email LIKE ? ESCAPE \'\\\' ORDER BY e.created_at DESC');
        $stmt->execute(['%' . $escaped . '%']);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query('SELECT e.id, e.student_id, u.full_name AS student_name, e.name, e.email, e.message, e.created_at FROM enquiries e LEFT JOIN users u ON u.id = e.student_id ORDER BY e.created_at DESC LIMIT 100')->fetchAll();
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
  <thead><tr><th>Name</th><th>Email</th><th>Student Account</th><th>Message</th><th>Received</th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['email']) ?></td>
        <td><?= $r['student_id'] ? e($r['student_name'] . ' (#' . $r['student_id'] . ')') : '<span class="muted">Guest</span>' ?></td>
        <td><?= e($r['message']) ?></td>
        <td><?= e($r['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
