<?php
/**
 * Customer Registration Page
 * Raman Group Platform
 */
$customPageTitle = "Customer Registration";
$customMetaDesc = "Create a new Raman Group Customer Portal account to submit quote requests, track structural builds & interior installations, and manage your projects.";

require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isCustomerLoggedIn()) {
    header('Location: customer/dashboard.php');
    exit;
}

$error = '';
$fullName = '';
$email = '';
$phone = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        $error = 'Security session expired. Please refresh the page and try again.';
    } else {
        $fullName = sanitize($_POST['full_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $address = sanitize($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please provide a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters in length.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Password and Confirm Password do not match.';
        } else {
            $res = registerCustomer($fullName, $email, $phone, $password, $address);
            if ($res['success']) {
                setFlash('success', 'Your account has been created successfully! Welcome to Raman Group.');
                header('Location: customer/dashboard.php');
                exit;
            } else {
                $error = $res['error'];
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5 bg-dark position-relative overflow-hidden" style="min-height: 85vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-navy-dark text-white border-gold shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header text-center bg-navy py-4 border-bottom border-gold">
                        <div class="brand-logo mb-2">
                            <i class="fas fa-user-plus text-warning display-6 me-2"></i>
                            <span class="h4 font-heading text-white fw-bold">RAMAN GROUP</span>
                        </div>
                        <h5 class="font-heading text-warning mb-0">Create Customer Account</h5>
                        <p class="small text-muted mb-0 mt-1">Get instant access to quote requests & project management</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <?= getFlash() ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> <?= e($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="register.php" class="needs-validation" novalidate>
                            <?= getCSRFInput() ?>

                            <div class="mb-3">
                                <label for="full_name" class="form-label text-warning small font-heading fw-bold">Full Name *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" id="full_name" name="full_name" placeholder="Enter your full name" value="<?= e($fullName) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label text-warning small font-heading fw-bold">Email Address *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" placeholder="Enter your email address" value="<?= e($email) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label text-warning small font-heading fw-bold">Phone Number *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control bg-dark text-white border-secondary" id="phone" name="phone" placeholder="Enter your phone number" value="<?= e($phone) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label text-warning small font-heading fw-bold">Address / City (Optional)</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="address" name="address" placeholder="Enter your address or city" value="<?= e($address) ?>">
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label text-warning small font-heading fw-bold">Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" placeholder="Create a password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label text-warning small font-heading fw-bold">Confirm Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-check-double"></i></span>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-gold btn-lg font-heading fw-bold py-3 shadow">
                                    <i class="fas fa-user-check me-2"></i> Register Account
                                </button>
                            </div>
                        </form>

                        <div class="text-center pt-3 border-top border-secondary">
                            <p class="text-secondary small mb-0">
                                Already registered? 
                                <a href="login.php" class="text-warning fw-bold">Log In to Your Account</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
