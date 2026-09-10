<?php
/**
 * Admin Logout Script
 */
require_once __DIR__ . '/../includes/auth.php';

logoutAdmin();
setFlash('success', 'You have been logged out successfully.');
header("Location: login.php");
exit;
