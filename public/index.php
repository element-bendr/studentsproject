<?php
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Welcome to <?= e(APP_NAME) ?></h1>
<p>Explore our courses, register as a student, and access the student portal. Admins can manage attendance, uploads, notices, enquiries, and appointments.</p>

<section class="cards">
  <div class="card">
    <h2>Students</h2>
    <p>Register, log in, and view your dashboard.</p>
    <a class="btn" href="<?= e(BASE_URL) ?>student/register.php">Get Started</a>
  </div>
  <div class="card">
    <h2>Admin Panel</h2>
    <p>Manage users, attendance, uploads, notices, and more.</p>
    <a class="btn" href="<?= e(BASE_URL) ?>admin/login.php">Admin Login</a>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
