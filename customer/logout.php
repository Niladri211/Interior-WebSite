<?php
/**
 * Customer Session Logout
 * Raman Group Platform
 */
require_once __DIR__ . '/../includes/auth.php';

logoutCustomer();
setFlash('info', 'You have been logged out of the Customer Portal.');
header('Location: ../login.php');
exit;
