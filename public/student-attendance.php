<?php
require_once __DIR__ . '/includes/student_header.php';
?>
<section class="card">
  <h1>Attendance Tracker</h1>
  <p class="intro">See your most recent sessions and whether you were present.</p>
  <table class="table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Course</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>2026-02-08</td>
        <td>Advanced PHP Security</td>
        <td><span class="status-pill present">Present</span></td>
      </tr>
      <tr>
        <td>2026-02-06</td>
        <td>Database Design Principles</td>
        <td><span class="status-pill absent">Absent</span></td>
      </tr>
      <tr>
        <td>2026-02-04</td>
        <td>Cloud Deployments Lab</td>
        <td><span class="status-pill present">Present</span></td>
      </tr>
    </tbody>
  </table>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
