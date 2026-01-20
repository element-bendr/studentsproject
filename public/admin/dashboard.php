<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';

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
  <div class="card">
    <h2>👥 Total Students</h2>
    <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: var(--spacing-md) 0;"><?= e((string)$metrics['total_users']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;">All registered students</p>
  </div>
  <div class="card">
    <h2>✅ Active Students</h2>
    <p style="font-size: 2.5rem; font-weight: 700; color: #10B981; margin: var(--spacing-md) 0;"><?= e((string)$metrics['active_users']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;">Currently active</p>
  </div>
  <div class="card">
    <h2>📅 Appointments</h2>
    <p style="font-size: 2.5rem; font-weight: 700; color: var(--secondary); margin: var(--spacing-md) 0;"><?= e((string)$metrics['total_appointments']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;"><a href="appointments.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">View All →</a></p>
  </div>
  <div class="card">
    <h2>💬 Enquiries</h2>
    <p style="font-size: 2.5rem; font-weight: 700; color: #8B5CF6; margin: var(--spacing-md) 0;"><?= e((string)$metrics['total_enquiries']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;"><a href="enquiries.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">View All →</a></p>
  </div>
  <div class="card">
    <h2>📦 Study Materials</h2>
    <p style="font-size: 2.5rem; font-weight: 700; color: #EC4899; margin: var(--spacing-md) 0;"><?= e((string)$metrics['total_uploads']) ?></p>
    <p style="font-size: 0.875rem; color: #6B7280;"><a href="uploads.php" style="color: var(--primary); text-decoration: none; font-weight: 500;">Manage →</a></p>
  </div>
</section>

<section class="card" style="margin-top: var(--spacing-2xl);">
  <h2>📊 Quick Stats</h2>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); margin-top: var(--spacing-lg);">
    <div style="padding: var(--spacing-lg); background: linear-gradient(135deg, #E0E7FF 0%, #E0E7FF 100%); border-radius: 8px; text-align: center;">
      <p style="margin: 0; font-size: 0.875rem; color: #312E81; font-weight: 600;">Student Attendance Rate</p>
      <p style="margin: var(--spacing-md) 0 0 0; font-size: 1.5rem; color: #312E81; font-weight: 700;">Manage</p>
    </div>
    <div style="padding: var(--spacing-lg); background: linear-gradient(135deg, #FEF3C7 0%, #FEF3C7 100%); border-radius: 8px; text-align: center;">
      <p style="margin: 0; font-size: 0.875rem; color: #92400E; font-weight: 600;">System Status</p>
      <p style="margin: var(--spacing-md) 0 0 0; font-size: 1.5rem; color: #92400E; font-weight: 700;">✓ Active</p>
    </div>
    <div style="padding: var(--spacing-lg); background: linear-gradient(135deg, #DBEAFE 0%, #DBEAFE 100%); border-radius: 8px; text-align: center;">
      <p style="margin: 0; font-size: 0.875rem; color: #0C4A6E; font-weight: 600;">Database</p>
      <p style="margin: var(--spacing-md) 0 0 0; font-size: 1.5rem; color: #0C4A6E; font-weight: 700;">✓ Connected</p>
    </div>
  </div>
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
