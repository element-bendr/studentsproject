<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/auth.php';
session_boot();
require_admin_auth();
$admin = $_SESSION['admin'];

// Get current page to highlight active nav
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(APP_NAME) ?> - Admin Panel</title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/style.css">
</head>
<body class="portal-layout">
  <aside class="sidebar admin-sidebar">
    <div class="sidebar-brand">
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/dashboard.php"><?= e(APP_NAME) ?></a>
      <span class="role-badge admin">Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
        <span class="icon">📊</span> Dashboard
      </a>
      <div class="nav-section">
        <p class="nav-label">Management</p>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/attendance.php" class="nav-item <?= $current_page === 'attendance.php' ? 'active' : '' ?>">
          <span class="icon">✓</span> Attendance
        </a>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/users.php" class="nav-item <?= $current_page === 'users.php' ? 'active' : '' ?>">
          <span class="icon">👥</span> Students
        </a>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/add_student.php" class="nav-item <?= $current_page === 'add_student.php' ? 'active' : '' ?>">
          <span class="icon">➕</span> Add Student
        </a>
      </div>
      <div class="nav-section">
        <p class="nav-label">Content</p>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/uploads.php" class="nav-item <?= $current_page === 'uploads.php' ? 'active' : '' ?>">
          <span class="icon">📁</span> Upload Notes & Photos
        </a>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/notices.php" class="nav-item <?= $current_page === 'notices.php' ? 'active' : '' ?>">
          <span class="icon">📢</span> Notices
        </a>
      </div>
      <div class="nav-section">
        <p class="nav-label">Requests</p>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/appointments.php" class="nav-item <?= $current_page === 'appointments.php' ? 'active' : '' ?>">
          <span class="icon">📅</span> Appointments
        </a>
          <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/enquiries.php" class="nav-item <?= $current_page === 'enquiries.php' ? 'active' : '' ?>">
          <span class="icon">💬</span> Enquiries
        </a>
      </div>
      <hr class="sidebar-divider">
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/change_password.php" class="nav-item <?= $current_page === 'change_password.php' ? 'active' : '' ?>">
        <span class="icon">🔐</span> Change Password
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>index.html" class="nav-item">
        <span class="icon">🏠</span> Home
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>admin/logout.php" class="nav-item logout">
        <span class="icon">🚪</span> Logout
      </a>
    </nav>
    <div class="sidebar-footer">
      <p class="admin-name"><?= e($admin['full_name']) ?></p>
      <p class="admin-email"><?= e($admin['email']) ?></p>
    </div>
  </aside>
  <header class="site-header admin-header">
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">☰</button>
    <div class="header-content">
      <h1 class="page-title"></h1>
    </div>
  </header>
  <main class="main-content admin-main">
