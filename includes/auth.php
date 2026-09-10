<?php
/**
 * Authentication Module
 * Raman Group Admin & Customer Auth
 */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

// ==========================================
// ADMIN AUTHENTICATION
// ==========================================

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        setFlash('danger', 'Please log in to access the admin panel.');
        header('Location: login.php');
        exit;
    }
}

function attemptAdminLogin($usernameOrEmail, $password) {
    $db = getDB();
    $user = trim($usernameOrEmail);
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$user, $user]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_full_name'] = $admin['full_name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['last_activity'] = time();

        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }

        return ['success' => true, 'admin' => $admin];
    }

    return ['success' => false, 'error' => 'Invalid username or password.'];
}

function logoutAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_full_name']);
    unset($_SESSION['admin_email']);
}


// ==========================================
// CUSTOMER AUTHENTICATION
// ==========================================

function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

function requireCustomerLogin() {
    if (!isCustomerLoggedIn()) {
        setFlash('warning', 'Please log in to access your customer portal.');
        header('Location: ../login.php');
        exit;
    }
}

function getLoggedInCustomer() {
    if (!isCustomerLoggedIn()) {
        return null;
    }
    static $cachedCustomer = null;
    if ($cachedCustomer === null) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, full_name, email, phone, address, created_at FROM customers WHERE id = ? LIMIT 1");
        $stmt->execute([$_SESSION['customer_id']]);
        $cachedCustomer = $stmt->fetch();
    }
    return $cachedCustomer;
}

function attemptCustomerLogin($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => trim($email)]);
    $customer = $stmt->fetch();

    if ($customer && password_verify($password, $customer['password_hash'])) {
        $_SESSION['customer_id'] = $customer['id'];
        $_SESSION['customer_name'] = $customer['full_name'];
        $_SESSION['customer_email'] = $customer['email'];
        $_SESSION['customer_phone'] = $customer['phone'];
        $_SESSION['last_activity'] = time();

        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }

        return ['success' => true, 'customer' => $customer];
    }

    return ['success' => false, 'error' => 'Invalid email address or password.'];
}

function registerCustomer($fullName, $email, $phone, $password, $address = '') {
    $db = getDB();
    
    $email = strtolower(trim($email));
    $phone = trim($phone);
    $fullName = trim($fullName);

    // Check duplicate email
    $stmtCheck = $db->prepare("SELECT id FROM customers WHERE email = ? LIMIT 1");
    $stmtCheck->execute([$email]);
    if ($stmtCheck->fetch()) {
        return ['success' => false, 'error' => 'An account with this email address already exists.'];
    }

    $passHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $db->prepare("INSERT INTO customers (full_name, email, phone, password_hash, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $email, $phone, $passHash, trim($address)]);
        $customerId = $db->lastInsertId();

        // Auto log in after registration
        $_SESSION['customer_id'] = $customerId;
        $_SESSION['customer_name'] = $fullName;
        $_SESSION['customer_email'] = $email;
        $_SESSION['customer_phone'] = $phone;
        $_SESSION['last_activity'] = time();

        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }

        return ['success' => true, 'customer_id' => $customerId];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => 'Registration failed: ' . $e->getMessage()];
    }
}


function logoutCustomer() {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_email']);
    unset($_SESSION['customer_phone']);
}

