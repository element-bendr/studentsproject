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
        $stmt = $pdo->prepare('SELECT name, email, phone, preferred_date, preferred_time, reason, created_at FROM appointments WHERE email LIKE ? ESCAPE \'\\\' ORDER BY created_at DESC');
        $stmt->execute(['%' . $escaped . '%']);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $pdo->query('SELECT name, email, phone, preferred_date, preferred_time, reason, created_at FROM appointments ORDER BY created_at DESC LIMIT 100')->fetchAll();
    }
} catch (Throwable $e) {
    log_error('Appointments load error: ' . $e->getMessage());
}
?>

<h1>Appointments</h1>
<form method="get" class="form">
  <label>Filter by Email
    <input type="text" name="email" value="<?= e($filterEmail) ?>">
  </label>
  <button type="submit" class="btn">Search</button>
</form>

<table class="table">
  <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Time</th><th>Reason</th><th>Received</th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['email']) ?></td>
        <td><?= e($r['phone']) ?></td>
        <td><?= e($r['preferred_date']) ?></td>
        <td><?= e($r['preferred_time']) ?></td>
        <td><?= e($r['reason']) ?></td>
        <td><?= e($r['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
