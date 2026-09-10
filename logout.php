<?php
/**
 * Logout Page - Customer & Public Logout
 * Raman Group Platform
 */
require_once __DIR__ . '/includes/auth.php';

logoutCustomer();
setFlash('info', 'You have logged out successfully.');
header('Location: login.php');
exit;
