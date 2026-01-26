<?php
require_once __DIR__ . '/../includes/admin_header.php';

// Get search filter if provided
$search = sanitize_string($_GET['search'] ?? '');

try {
    $pdo = get_db_connection();
    
    // Build query with search filter
    $query = 'SELECT id, full_name, email, phone, status, created_at FROM users';
    $params = [];
    
    if ($search) {
        $query .= ' WHERE full_name LIKE :search OR email LIKE :search OR phone LIKE :search';
        $params['search'] = '%' . escape_like($search) . '%';
    }
    
    $query .= ' ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
} catch (Throwable $e) {
    log_error('Student listing error: ' . $e->getMessage());
    $users = [];
    $error = 'Error loading students. Please try again.';
}
?>

<div class="content-header">
    <h1>Student Management</h1>
    <p>Manage and view all registered students</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Students (<?= count($users) ?>)</h2>
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Search by name, email, or phone..." value="<?= e($search) ?>">
            <button type="submit" class="btn btn-small">Search</button>
            <?php if ($search): ?>
                <a href="users.php" class="btn btn-small">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="card-body">
        <?php if (empty($users)): ?>
            <p class="text-center text-muted">No students found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= e($user['full_name']) ?></td>
                                <td><?= e($user['email']) ?></td>
                                <td><?= e($user['phone'] ?? '—') ?></td>
                                <td>
                                    <span class="badge badge-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= e(ucfirst($user['status'])) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="view_student.php?id=<?= (int)$user['id'] ?>" class="btn btn-tiny">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.search-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-form input {
    flex: 1;
    min-width: 200px;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table thead {
    background-color: #f5f5f5;
}

.table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #ddd;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.table tbody tr:hover {
    background-color: #f9f9f9;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background-color: #d4edda;
    color: #155724;
}

.badge-secondary {
    background-color: #e2e3e5;
    color: #383d41;
}

.btn-tiny {
    padding: 4px 8px;
    font-size: 12px;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #6c757d;
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
