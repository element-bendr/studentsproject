<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
session_boot();
require_student_auth();
$student = $_SESSION['student'];

// Get current page to highlight active nav
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(APP_NAME) ?> - Student Portal</title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/style.css">
</head>
<body class="portal-layout">
  <aside class="sidebar student-sidebar">
    <div class="sidebar-brand">
      <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student/dashboard.php"><?= e(APP_NAME) ?></a>
      <span class="role-badge">Student</span>
    </div>
      <nav class="sidebar-nav">
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student/dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
        <span class="icon">📊</span> Dashboard
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student-courses.php" class="nav-item <?= $current_page === 'student-courses.php' ? 'active' : '' ?>">
        <span class="icon">📚</span> My Courses
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student-attendance.php" class="nav-item <?= $current_page === 'student-attendance.php' ? 'active' : '' ?>">
        <span class="icon">✓</span> Attendance
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student-schedule.php" class="nav-item <?= $current_page === 'student-schedule.php' ? 'active' : '' ?>">
        <span class="icon">📅</span> Schedule
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student/download.php" class="nav-item <?= $current_page === 'download.php' ? 'active' : '' ?>">
        <span class="icon">⬇️</span> Downloads
      </a>
      <hr class="sidebar-divider">
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>index.html" class="nav-item">
        <span class="icon">🏠</span> Home
      </a>
        <a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>student/logout.php" class="nav-item logout">
        <span class="icon">🚪</span> Logout
      </a>
    </nav>
    <div class="sidebar-footer">
      <p class="student-name"><?= e($student['full_name']) ?></p>
      <p class="student-email"><?= e($student['email']) ?></p>
    </div>
  </aside>
  <header class="site-header student-header">
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">☰</button>
    <div class="header-content">
      <h1 class="page-title"></h1>
    </div>
  </header>
  <main class="main-content student-main">
