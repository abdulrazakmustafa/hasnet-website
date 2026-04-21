<?php
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/includes/auth.php';
logout_user();
header('Location: ' . APP_URL . '/admin/login.php');
exit;
