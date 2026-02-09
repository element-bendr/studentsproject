<?php
require_once __DIR__ . '/includes/student_header.php';
?>
<section class="card">
  <h1>My Courses</h1>
  <p class="intro">Keep track of your enrolled subjects and their current status.</p>
  <ul class="muted-list">
    <li><strong>Advanced PHP Security</strong> &ndash; Mon/Wed 09:00 - 10:30 / <span class="status-pill in-progress">In Progress</span></li>
    <li><strong>Database Design Principles</strong> &ndash; Tue/Thu 11:00 - 12:30 / <span class="status-pill upcoming">Scheduled</span></li>
    <li><strong>Cloud Deployments Lab</strong> &ndash; Fri 14:00 - 16:00 / <span class="status-pill completed">Completed</span></li>
  </ul>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
