<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_admin_auth();

require_once __DIR__ . '/../includes/header.php';

$metrics = [
  'total_users' => 0,
  'active_users' => 0,
  'total_appointments' => 0,
  'total_enquiries' => 0,
  'total_uploads' => 0,
];

try {
    $pdo = get_db_connection();
    $metrics['total_users'] = (int)$pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'];
    $metrics['active_users'] = (int)$pdo->query("SELECT COUNT(*) c FROM users WHERE status='active'")->fetch()['c'];
    $metrics['total_appointments'] = (int)$pdo->query('SELECT COUNT(*) c FROM appointments')->fetch()['c'];
    $metrics['total_enquiries'] = (int)$pdo->query('SELECT COUNT(*) c FROM enquiries')->fetch()['c'];
    $metrics['total_uploads'] = (int)$pdo->query('SELECT COUNT(*) c FROM uploads')->fetch()['c'];
} catch (Throwable $e) {
    log_error('Admin dashboard metrics error: ' . $e->getMessage());
}
?>

<h1>Admin Dashboard</h1>
<section class="cards">
  <div class="card"><h2>Total Users</h2><p><?= e((string)$metrics['total_users']) ?></p></div>
  <div class="card"><h2>Active Users</h2><p><?= e((string)$metrics['active_users']) ?></p></div>
  <div class="card"><h2>Appointments</h2><p><?= e((string)$metrics['total_appointments']) ?></p></div>
  <div class="card"><h2>Enquiries</h2><p><?= e((string)$metrics['total_enquiries']) ?></p></div>
  <div class="card"><h2>Uploads</h2><p><?= e((string)$metrics['total_uploads']) ?></p></div>
</section>

<section class="grid">
  <div class="card">
    <h2>Quick Links</h2>
    <ul>
      <li><a href="attendance.php">Attendance</a></li>
      <li><a href="uploads.php">Uploads</a></li>
      <li><a href="notices.php">Notices</a></li>
      <li><a href="appointments.php">Appointments</a></li>
      <li><a href="enquiries.php">Enquiries</a></li>
    </ul>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
