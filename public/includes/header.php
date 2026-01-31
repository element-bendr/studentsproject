<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
session_boot();
$isStudent = !empty($_SESSION['student']);
$isAdmin = !empty($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <nav class="navbar">
      <a class="brand" href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>index.php"><?= e(APP_NAME) ?></a>
      <ul class="nav-links">
        <li><a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>index.php">Home</a></li>
        <li><a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>about.php">About</a></li>
        <li><a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>courses.php">Courses</a></li>
        <li><a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>contact.php">Contact</a></li>
        <li><a href="<?= e(BASE_URL . PUBLIC_URL_PREFIX) ?>book_appointment.php">Appointments</a></li>
        <?php if ($isStudent): ?>
          <li><a href="<?= e(BASE_URL) ?>student/dashboard.php">Student Dashboard</a></li>
          <li><a href="<?= e(BASE_URL) ?>student/logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="<?= e(BASE_URL) ?>student/login.php">Student Login</a></li>
        <?php endif; ?>
        <?php if ($isAdmin): ?>
          <li><a href="<?= e(BASE_URL) ?>admin/dashboard.php">Admin</a></li>
          <li><a href="<?= e(BASE_URL) ?>admin/logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="<?= e(BASE_URL) ?>admin/login.php">Admin Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <main class="container">
