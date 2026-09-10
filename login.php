<?php
/**
 * Public Customer & Admin Access Login Page
 * Raman Group Platform - Premium Luxury Client-Demo UI
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

<style>
/* 1. SECTION & REAL LUXURY INTERIOR BACKGROUND ENGINE */
.login-page-section {
    position: relative;
    background-color: #0f141f;
    min-height: 90vh;
    display: flex;
    align-items: center;
    padding: 50px 0;
    overflow: hidden;
}

/* High-End Real Interior Photograph Layer */
.login-bg-interior {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background-image: url('assets/images/services/home-interiors.jpg');
    background-size: cover;
    background-position: center center;
    filter: blur(4px) brightness(0.72) contrast(1.08);
    transform: scale(1.04);
    z-index: 0;
}

/* Multi-layered Warm Ivory, Champagne Gold & Ambient Vignette Overlay */
.login-bg-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: 
        /* Warm Champagne & Gold Center Spotlight */
        radial-gradient(circle at 50% 35%, rgba(212, 175, 55, 0.26) 0%, rgba(245, 239, 230, 0.12) 45%, transparent 75%),
        /* Soft Luxury Warm Ivory Tint */
        linear-gradient(135deg, rgba(253, 251, 247, 0.40) 0%, rgba(244, 236, 225, 0.30) 50%, rgba(15, 23, 42, 0.65) 100%),
        /* Top & Bottom Dark Navy Framing Vignette */
        linear-gradient(to bottom, rgba(12, 18, 28, 0.50) 0%, transparent 25%, transparent 75%, rgba(12, 18, 28, 0.65) 100%);
    pointer-events: none;
}

/* 3. MAIN LOGIN CARD WITH WARM IVORY SURFACE & AMBIENT GOLD GLOW */
.login-card-premium {
    position: relative;
    z-index: 2;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 30px;
    border: 1px solid rgba(212, 175, 55, 0.45);
    box-shadow: 
        0 0 60px rgba(212, 175, 55, 0.22),
        0 30px 70px rgba(15, 23, 42, 0.28);
    overflow: hidden;
    max-width: 640px;
    margin: 0 auto;
}

/* 4. BRAND LOGO AREA */
.login-brand-header {
    text-align: center;
    padding: 38px 40px 10px 40px;
}

.login-brand-logo-img {
    width: 175px;
    max-width: 80%;
    height: auto;
    object-fit: contain;
    margin-bottom: 14px;
    filter: drop-shadow(0 4px 18px rgba(212, 175, 55, 0.35));
    transition: transform 0.3s ease;
}

.login-brand-logo-img:hover {
    transform: scale(1.03);
}

/* 5. LOGIN HEADING TYPOGRAPHY */
.login-header-title {
    color: #121824;
    font-size: 1.7rem;
    letter-spacing: -0.3px;
}

.login-header-subtitle {
    color: #64748b;
    font-size: 0.92rem;
    font-weight: 500;
}

/* CARD BODY */
.login-card-body {
    padding: 0 44px 38px 44px;
}

/* 7. DEEP NAVY INPUT GROUPS */
.login-input-group {
    background-color: #121824;
    border-radius: 14px;
    border: 1px solid #334155;
    height: 58px;
    overflow: hidden;
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}

.login-input-group:focus-within {
    border-color: #d4af37 !important;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25) !important;
}

.login-input-icon {
    background-color: transparent !important;
    border: none !important;
    color: #d4af37 !important;
    font-size: 1.05rem;
    padding: 0 18px !important;
    display: flex;
    align-items: center;
    border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
}

.login-input-field {
    background-color: transparent !important;
    border: none !important;
    color: #ffffff !important;
    font-size: 0.96rem;
    padding: 0 18px !important;
    height: 100%;
    box-shadow: none !important;
}

.login-input-field::placeholder {
    color: #94a3b8 !important;
}

.login-input-field:-webkit-autofill,
.login-input-field:-webkit-autofill:hover,
.login-input-field:-webkit-autofill:focus {
    -webkit-text-fill-color: #ffffff !important;
    -webkit-box-shadow: 0 0 0px 1000px #121824 inset !important;
    transition: background-color 5000s ease-in-out 0s;
}

/* 8. PREMIUM GOLD CTA BUTTON */
.btn-portal-login {
    background: linear-gradient(135deg, #d4af37 0%, #b89628 100%) !important;
    color: #0f141f !important;
    font-weight: 700;
    font-size: 1rem;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px !important;
    border: none !important;
    box-shadow: 0 8px 24px rgba(212, 175, 55, 0.4) !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.btn-portal-login:hover {
    background: linear-gradient(135deg, #e5be48 0%, #d4af37 100%) !important;
    color: #0f141f !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(212, 175, 55, 0.55) !important;
}

/* 9. LOWER LINKS STYLING */
.login-footer-link-gold {
    color: #d4af37;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease;
}

.login-footer-link-gold:hover {
    color: #b89628;
    text-decoration: underline;
}

.login-footer-link-admin {
    color: #0ea5e9;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease;
}

.login-footer-link-admin:hover {
    color: #0284c7;
    text-decoration: underline;
}

/* 11. RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .login-page-section {
        padding: 25px 0;
    }
    .login-brand-header {
        padding: 30px 24px 8px 24px;
    }
    .login-card-body {
        padding: 0 24px 30px 24px;
    }
    .login-brand-logo-img {
        width: 145px;
    }
    .login-header-title {
        font-size: 1.45rem;
    }
    .login-input-group, .btn-portal-login {
        height: 52px;
    }
}
</style>

<section class="login-page-section">
    <!-- Real Luxury Interior Project Background Photograph Layer -->
    <div class="login-bg-interior"></div>
    
    <!-- Warm Ivory, Champagne Gold & Ambient Vignette Overlay -->
    <div class="login-bg-overlay"></div>

    <div class="container position-relative z-2">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                <div class="login-card-premium">
                    <!-- 4. RAMAN GROUP COMPANY LOGO -->
                    <div class="login-brand-header">
                        <a href="index.php" title="Raman Group">
                            <img src="assets/images/official-gold-crest.png" alt="Raman Group Official Logo" class="login-brand-logo-img">
                        </a>
                        
                        <!-- 5. LOGIN HEADING -->
                        <h3 class="font-heading fw-bold login-header-title mb-1">
                            Customer Portal <span style="color: #d4af37;">Login</span>
                        </h3>
                        <p class="login-header-subtitle mb-0">Access your project quotes, progress & communication</p>
                        
                        <!-- 6. DECORATIVE DIVIDER: ──────── ◆ ──────── -->
                        <div class="d-flex align-items-center justify-content-center my-3">
                            <div style="height: 1px; width: 65px; background: linear-gradient(90deg, transparent, #d4af37);"></div>
                            <div class="px-3" style="color: #d4af37; font-size: 0.75rem; line-height: 1;">◆</div>
                            <div style="height: 1px; width: 65px; background: linear-gradient(90deg, #d4af37, transparent);"></div>
                        </div>
                    </div>

                    <div class="login-card-body">
                        <?= getFlash() ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2 px-3 mb-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> <?= e($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- FORM SECTION -->
                        <form method="POST" action="login.php" class="needs-validation" novalidate>
                            <?= getCSRFInput() ?>

                            <!-- 7. EMAIL FIELD -->
                            <div class="mb-3">
                                <label for="email" class="form-label small font-heading fw-bold mb-1" style="color: #d4af37;">Email Address</label>
                                <div class="input-group login-input-group">
                                    <span class="input-group-text login-input-icon"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control login-input-field" id="email" name="email" placeholder="Enter your email address" value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
                                </div>
                            </div>

                            <!-- 8. PASSWORD FIELD -->
                            <div class="mb-3">
                                <label for="password" class="form-label small font-heading fw-bold mb-1" style="color: #d4af37;">Password</label>
                                <div class="input-group login-input-group">
                                    <span class="input-group-text login-input-icon"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control login-input-field" id="password" name="password" placeholder="Enter your password" required>
                                </div>
                            </div>

                            <!-- 9. LOGIN BUTTON -->
                            <div class="d-grid mt-4 mb-3">
                                <button type="submit" class="btn btn-portal-login font-heading">
                                    <i class="fas fa-sign-in-alt me-2"></i> Log In to Portal
                                </button>
                            </div>
                        </form>

                        <!-- 10. LOWER LINKS -->
                        <hr class="my-3" style="border-top: 1px solid #e2e8f0; opacity: 0.8;">
                        <div class="text-center pt-1">
                            <p class="small mb-2" style="color: #64748b;">
                                Don't have a Raman Group account? 
                                <a href="register.php" class="login-footer-link-gold ms-1">Register Here</a>
                            </p>
                            <p class="small mb-0" style="color: #64748b;">
                                Administrative Staff? 
                                <a href="admin/login.php" class="login-footer-link-admin ms-1"><i class="fas fa-user-shield me-1"></i> Admin Portal</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
