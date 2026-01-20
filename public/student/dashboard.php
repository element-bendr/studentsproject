<?php
require_once __DIR__ . '/../includes/student_header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

$student = $_SESSION['student'];

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
    <h2>📋 Profile</h2>
    <p><strong><?= e($student['full_name']) ?></strong></p>
    <p style="font-size: 0.875rem; color: #6B7280;">Email: <?= e($student['email']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;">Joined: <?= e(date('M d, Y', strtotime($student['created_at']))) ?></p>
  </div>
  <div class="card">
    <h2>👥 Active Students</h2>
    <p style="font-size: 2rem; font-weight: 700; color: var(--primary); margin: var(--spacing-md) 0;"><?= e((string)$activeCount) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;">Currently Active</p>
  </div>
  <div class="card">
    <h2>✓ Attendance Rate</h2>
    <p style="font-size: 2rem; font-weight: 700; color: #10B981; margin: var(--spacing-md) 0;"><?= e((string)$attendancePct) ?>%</p>
    <p style="font-size: 0.875rem; color: #6B7280;"><?= count($attendance) ?> records</p>
  </div>
</section>

<section class="card" style="margin-bottom: var(--spacing-2xl);">
  <h2>📅 Recent Attendance</h2>
  <?php if ($attendance): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice($attendance, 0, 10) as $row): ?>
          <tr>
            <td><?= e($row['date']) ?></td>
            <td>
              <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 500; background: <?= $row['status'] === 'present' ? '#D1FAE5' : '#FEE2E2' ?>; color: <?= $row['status'] === 'present' ? '#065F46' : '#7F1D1D' ?>;">
                <?= e($row['status']) ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="color: #6B7280; text-align: center; padding: var(--spacing-2xl);">No attendance records yet.</p>
  <?php endif; ?>
</section>

<section class="card" style="margin-bottom: var(--spacing-2xl);">
  <h2>📚 Study Materials</h2>
  <?php if ($uploads): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Size</th>
          <th>Date Added</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($uploads as $u): ?>
          <tr>
            <td><?= e($u['title']) ?></td>
            <td>
              <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 500; background: <?= $u['type'] === 'note' ? '#E0E7FF' : '#FEF3C7' ?>; color: <?= $u['type'] === 'note' ? '#312E81' : '#92400E' ?>;">
                <?= e($u['type']) ?>
              </span>
            </td>
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

<section class="card">
  <h2>📢 Notices</h2>
  <?php if ($notices): ?>
    <div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
      <?php foreach (array_slice($notices, 0, 5) as $n): ?>
        <div style="padding: var(--spacing-md); background: var(--neutral-50); border-left: 4px solid var(--primary); border-radius: 4px;">
          <h3 style="margin: 0 0 var(--spacing-sm) 0; color: var(--neutral-900); font-size: var(--font-size-base);"><?= e($n['title']) ?></h3>
          <p style="margin: 0; color: var(--neutral-700); font-size: var(--font-size-sm); line-height: 1.5;"><?= e(substr($n['body'], 0, 150)) ?>...</p>
          <p style="margin: var(--spacing-sm) 0 0 0; color: var(--neutral-500); font-size: var(--font-size-xs);"><?= e(date('M d, Y', strtotime($n['created_at']))) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p style="color: #6B7280; text-align: center; padding: var(--spacing-2xl);">No notices yet.</p>
  <?php endif; ?>
</section>

</main>
<script>
  // Set page title
  document.querySelector('.page-title').textContent = 'Dashboard';
  
  // Mobile menu toggle
  document.getElementById('menuToggle').addEventListener('click', function() {
    document.querySelector('.sidebar-nav').classList.toggle('active');
  });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
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
