<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

require_student_auth();
$student = $_SESSION['student'];

require_once __DIR__ . '/../includes/header.php';

$activeCount = 0;
$attendance = [];
$attendancePct = 0;
$uploads = [];
$notices = [];

try {
    $pdo = get_db_connection();
    $activeCount = (int)$pdo->query("SELECT COUNT(*) AS c FROM users WHERE status='active'")->fetch()['c'];

    $stmt = $pdo->prepare('SELECT date, status FROM attendance WHERE student_id = ? ORDER BY date DESC LIMIT 50');
    $stmt->execute([$student['id']]);
    $attendance = $stmt->fetchAll();
    if ($attendance) {
        $present = 0; $total = 0;
        foreach ($attendance as $row) {
            $total++;
            if ($row['status'] === 'present') $present++;
        }
        $attendancePct = $total ? round(($present / $total) * 100, 2) : 0;
    }

    $uploads = $pdo->query("SELECT id, title, type, filename, mime_type, size, created_at FROM uploads ORDER BY created_at DESC LIMIT 50")->fetchAll();
    $notices = $pdo->query("SELECT title, body, created_at FROM notices WHERE visible_to_students = 1 ORDER BY created_at DESC LIMIT 10")->fetchAll();
} catch (Throwable $e) {
    log_error('Dashboard load error: ' . $e->getMessage());
}
?>

<h1>Student Dashboard</h1>

<section class="cards">
  <div class="card">
    <h2>My Details</h2>
    <p><strong>Name:</strong> <?= e($student['full_name']) ?></p>
    <p><strong>Email:</strong> <?= e($student['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($student['phone'] ?? '') ?></p>
    <p><strong>Joined:</strong> <?= e($student['created_at']) ?></p>
  </div>
  <div class="card">
    <h2>Active Student Count</h2>
    <p><?= e((string)$activeCount) ?></p>
  </div>
  <div class="card">
    <h2>Attendance</h2>
    <p>Recent records (last 50). Present rate: <?= e((string)$attendancePct) ?>%</p>
    <table class="table">
      <thead><tr><th>Date</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($attendance as $row): ?>
          <tr><td><?= e($row['date']) ?></td><td><?= e($row['status']) ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<section>
  <h2>Downloads: Notes & Photos</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Type</th><th>Size</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($uploads as $u): ?>
        <tr>
          <td><?= e($u['title']) ?></td>
          <td><?= e($u['type']) ?></td>
          <td><?= e((string)$u['size']) ?> bytes</td>
          <td><a class="btn" href="download.php?id=<?= e((string)$u['id']) ?>">Download</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<section>
  <h2>Notices</h2>
  <?php if (!$notices): ?>
    <p>No notices at the moment.</p>
  <?php else: ?>
    <div class="cards">
      <?php foreach ($notices as $n): ?>
        <div class="card">
          <h3><?= e($n['title']) ?></h3>
          <p><?= e($n['body']) ?></p>
          <p class="muted">Posted: <?= e($n['created_at']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
