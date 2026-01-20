<?php
require_once __DIR__ . '/../includes/auth.php';
admin_logout();
header('Location: ' . BASE_URL . 'public/index.php');
exit;
?>
