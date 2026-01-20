<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/validation.php';

$errors = [];
$success = null;

// Mark attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_attendance'])) {
    if (!csrf_validate()) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $date = sanitize_string($_POST['date'] ?? '');
        $status = sanitize_string($_POST['status'] ?? '');
        if ($studentId <= 0) $errors[] = 'Select a valid student.';
        if (!validate_date($date)) $errors[] = 'Date must be in YYYY-MM-DD format.';
        if (!in_array($status, ['present', 'absent'], true)) $errors[] = 'Status must be present or absent.';
        if (!$errors) {
            try {
                $pdo = get_db_connection();
                // Prevent duplicates (student_id + date unique)
                $stmt = $pdo->prepare('INSERT INTO attendance (student_id, date, status, marked_by_admin_id) VALUES (?, ?, ?, ?)');
                $stmt->execute([$studentId, $date, $status, $_SESSION['admin']['id']]);
                $success = 'Attendance marked.';
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    $errors[] = 'Attendance already marked for this student and date.';
                } else {
                    log_error('Attendance mark error: ' . $e->getMessage());
                    $errors[] = 'Could not mark attendance.';
                }
            } catch (Throwable $e) {
                log_error('Attendance mark error: ' . $e->getMessage());
                $errors[] = 'Could not mark attendance.';
            }
        }
    }
}

// Load students for selection
$students = [];
// View attendance
$viewByDate = [];
$viewByStudent = [];

try {
    $pdo = get_db_connection();
    $students = $pdo->query('SELECT id, full_name, email FROM users ORDER BY full_name')->fetchAll();

    if (isset($_GET['view_date']) && validate_non_empty($_GET['view_date'])) {
        $d = sanitize_string($_GET['view_date']);
        $stmt = $pdo->prepare('SELECT a.date, a.status, u.full_name FROM attendance a JOIN users u ON a.student_id=u.id WHERE a.date = ? ORDER BY u.full_name');
        $stmt->execute([$d]);
        $viewByDate = $stmt->fetchAll();
    }
    if (isset($_GET['view_student']) && (int)$_GET['view_student'] > 0) {
        $sid = (int)$_GET['view_student'];
        $stmt = $pdo->prepare('SELECT date, status FROM attendance WHERE student_id = ? ORDER BY date DESC');
        $stmt->execute([$sid]);
        $viewByStudent = $stmt->fetchAll();
    }
} catch (Throwable $e) {
    log_error('Attendance load error: ' . $e->getMessage());
}
?>

<h1>Attendance Manager</h1>
<?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $err) echo '<p>' . e($err) . '</p>'; ?></div><?php endif; ?>

<section class="card">
  <h2>Mark Attendance</h2>
  <form method="post" class="form">
    <?= csrf_input() ?>
    <input type="hidden" name="mark_attendance" value="1">
    <label>Student
      <select name="student_id" required>
        <option value="">Select student</option>
        <?php foreach ($students as $s): ?>
          <option value="<?= e((string)$s['id']) ?>"><?= e($s['full_name']) ?> (<?= e($s['email']) ?>)</option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Date
      <input type="date" name="date" required>
    </label>
    <label>Status
      <select name="status" required>
        <option value="present">Present</option>
        <option value="absent">Absent</option>
      </select>
    </label>
    <button type="submit" class="btn">Save</button>
  </form>
</section>

<section class="card">
  <h2>View By Date</h2>
  <form method="get" class="form">
    <label>Date
      <input type="date" name="view_date" required>
    </label>
    <button type="submit" class="btn">View</button>
  </form>
  <table class="table">
    <thead><tr><th>Student</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($viewByDate as $row): ?>
        <tr><td><?= e($row['full_name']) ?></td><td><?= e($row['status']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<section class="card">
  <h2>View By Student</h2>
  <form method="get" class="form">
    <label>Student
      <select name="view_student" required>
        <option value="">Select student</option>
        <?php foreach ($students as $s): ?>
          <option value="<?= e((string)$s['id']) ?>"><?= e($s['full_name']) ?> (<?= e($s['email']) ?>)</option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit" class="btn">View</button>
  </form>
  <table class="table">
    <thead><tr><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($viewByStudent as $row): ?>
        <tr><td><?= e($row['date']) ?></td><td><?= e($row['status']) ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

</main>

<script>
  document.querySelector('.page-title').textContent = 'Attendance Manager';
  document.getElementById('menuToggle').addEventListener('click', function() {
    document.querySelector('.sidebar-nav').classList.toggle('active');
  });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
