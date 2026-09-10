<?php
/**
 * Public Customer & Admin Access Login Page
 * Raman Group Platform
 */
$customPageTitle = "Account Login";
$customMetaDesc = "Log in to your Raman Group Customer Portal to manage quote requests, track ongoing construction & interior projects, and access support.";

require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isCustomerLoggedIn()) {
    header('Location: customer/dashboard.php');
    exit;
}
if (isAdminLoggedIn()) {
    header('Location: admin/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        $error = 'Security session expired. Please refresh the page and try again.';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Please enter both your email address and password.';
        } else {
            $result = attemptCustomerLogin($email, $password);
            if ($result['success']) {
                setFlash('success', 'Welcome back, ' . htmlspecialchars($_SESSION['customer_name']) . '!');
                header('Location: customer/dashboard.php');
                exit;
            } else {
                $error = $result['error'];
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5 bg-dark position-relative overflow-hidden" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card bg-navy-dark text-white border-gold shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header text-center bg-navy py-4 border-bottom border-gold">
                        <div class="brand-logo mb-2">
                            <i class="fas fa-building text-warning display-6 me-2"></i>
                            <span class="h4 font-heading text-white fw-bold">RAMAN GROUP</span>
                        </div>
                        <h5 class="font-heading text-warning mb-0">Customer Portal Login</h5>
                        <p class="small text-muted mb-0 mt-1">Access your project quotes, progress & communication</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <?= getFlash() ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> <?= e($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="login.php" class="needs-validation" novalidate>
                            <?= getCSRFInput() ?>

                            <div class="mb-4">
                                <label for="email" class="form-label text-warning small font-heading fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" placeholder="name@example.com" value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label text-warning small font-heading fw-bold mb-0">Password</label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" placeholder="Enter your password" required>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-gold btn-lg font-heading fw-bold py-3 shadow">
                                    <i class="fas fa-sign-in-alt me-2"></i> Log In to Portal
                                </button>
                            </div>
                        </form>

                        <div class="text-center pt-3 border-top border-secondary">
                            <p class="text-secondary small mb-2">
                                Don't have a Raman Group account? 
                                <a href="register.php" class="text-warning fw-bold">Register Here</a>
                            </p>
                            <p class="text-secondary small mb-0">
                                Administrative Staff? 
                                <a href="admin/login.php" class="text-info fw-bold"><i class="fas fa-user-shield me-1"></i> Admin Portal</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
