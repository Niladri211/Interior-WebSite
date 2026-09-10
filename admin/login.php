<?php
/**
 * Admin Login Page - Raman Group
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// If already logged in, redirect to dashboard
if (isAdminLoggedIn()) {
    header("Location: index.php");
    exit;
}

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        $errorMsg = "Security token validation failed. Please refresh.";
    } else {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $errorMsg = "Please enter both username and password.";
        } else {
            $loginResult = attemptAdminLogin($username, $password);
            if ($loginResult['success']) {
                header("Location: index.php");
                exit;
            } else {
                $errorMsg = $loginResult['error'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Raman Group</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f141f 0%, #1a2234 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }
        .login-card {
            background: rgba(26, 34, 52, 0.95);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #b89628 100%);
            color: #0f141f;
            font-weight: 700;
            border: none;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #e5be48 0%, #d4af37 100%);
            color: #0f141f;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="h2 font-heading fw-bold text-white mb-1">
            <i class="fas fa-building text-warning me-2"></i> RAMAN GROUP
        </div>
        <div class="text-warning small font-monospace text-uppercase fw-bold letter-spacing-1">Executive Admin Portal</div>
    </div>

    <?= getFlash() ?>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show small">
            <i class="fas fa-exclamation-circle me-2"></i> <?= e($errorMsg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <?= getCSRFInput() ?>

        <div class="mb-3">
            <label class="form-label text-light small fw-bold font-heading">Username or Email</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" placeholder="admin" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label text-light small fw-bold font-heading">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-gold btn-lg w-100 py-3 font-heading fw-bold mb-3">
            <i class="fas fa-sign-in-alt me-2"></i> Secure Login
        </button>
        
        <div class="text-center mt-3">
            <a href="../index.php" class="text-muted small text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Main Website</a>
        </div>
    </form>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
