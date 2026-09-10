<?php
/**
 * Navbar Component
 * Raman Group Website
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$phone = getSiteSetting('phone', '+91 98765 43210');
$email = getSiteSetting('email', 'info@ramangroup.com');
$hours = getSiteSetting('business_hours', 'Mon - Sat: 9:00 AM - 7:00 PM');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Top Utility Bar -->
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-end align-items-center">
            <div class="d-inline-flex align-items-center gap-3">
                <a href="<?= e(getSiteSetting('facebook_url', '#')) ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="<?= e(getSiteSetting('instagram_url', '#')) ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="<?= e(getSiteSetting('linkedin_url', '#')) ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="<?= e(getSiteSetting('youtube_url', '#')) ?>" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                
                <span class="text-secondary ms-2">|</span>
                
                <?php if (isCustomerLoggedIn()): ?>
                    <a href="customer/dashboard.php" class="text-warning font-semibold"><i class="fas fa-user-circle me-1"></i> My Portal</a>
                    <a href="logout.php" class="text-white small ms-1" title="Log Out"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="login.php" class="text-white font-semibold"><i class="fas fa-user me-1"></i> Customer Login</a>
                    <a href="register.php" class="text-warning font-semibold"><i class="fas fa-user-plus me-1"></i> Register</a>
                <?php endif; ?>

                <span class="text-secondary ms-1">|</span>
                <a href="admin/login.php" class="text-warning font-semibold" title="Staff Admin Portal"><i class="fas fa-lock me-1"></i> Admin</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar Header -->
<header class="navbar-capsule-wrapper">
    <div class="container container-capsule">
        <nav class="navbar navbar-expand-lg align-items-center justify-content-between p-0">
            <a class="navbar-brand me-lg-3" href="index.php" title="RAMAN GROUP">
                <img src="assets/images/raman-group-logo-transparent.png" alt="RAMAN GROUP" class="navbar-logo-img">
            </a>
            <button class="navbar-toggler text-white border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-warning fs-5"></i>
            </button>

            <div class="collapse navbar-collapse justify-content-end justify-content-lg-between align-items-center" id="navbarMain">
                <!-- Single Capsule ONLY Around Navigation Links -->
                <div class="nav-capsule-box mx-auto my-2 my-lg-0">
                    <ul class="navbar-nav align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" href="about.php">About Us</a>
                        </li>
                        
                        <!-- Services Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= str_contains($currentPage, 'services') ? 'active' : '' ?>" href="services.php" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Services
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark-glass shadow-lg" aria-labelledby="servicesDropdown">
                                <li><a class="dropdown-item fw-bold text-warning" href="services.php"><i class="fas fa-th-large me-2"></i> All Services</a></li>
                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li><a class="dropdown-item" href="services-construction.php"><i class="fas fa-hard-hat me-2 text-warning"></i> Construction Services</a></li>
                                <li><a class="dropdown-item" href="services-interior.php"><i class="fas fa-couch me-2 text-warning"></i> Interior Services</a></li>
                                <li><a class="dropdown-item" href="services-fabrication.php"><i class="fas fa-industry me-2 text-warning"></i> Fabrication Services</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= str_contains($currentPage, 'project') ? 'active' : '' ?>" href="projects.php">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'gallery.php' ? 'active' : '' ?>" href="gallery.php">Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'testimonials.php' ? 'active' : '' ?>" href="testimonials.php">Testimonials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'faq.php' ? 'active' : '' ?>" href="faq.php">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="contact.php">Contact</a>
                        </li>
                    </ul>
                </div>

                <div class="nav-cta-wrapper ms-lg-2 mt-3 mt-lg-0">
                    <a class="btn btn-gold btn-capsule-cta" href="quote.php">
                        <i class="fas fa-calculator me-1"></i> Get a Quote
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Dedicated Horizontal Business Verticals Marquee -->
<div class="verticals-marquee-strip">
    <div class="marquee-track">
        <div class="marquee-content">
            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>

            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>

            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>
        </div>
        <div class="marquee-content" aria-hidden="true">
            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>

            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>

            <a href="services-construction.php" class="marquee-link">CONSTRUCTION</a>
            <span class="marquee-separator">✦</span>
            <a href="services-interior.php" class="marquee-link">INTERIOR</a>
            <span class="marquee-separator">✦</span>
            <a href="services-fabrication.php" class="marquee-link">FABRICATION</a>
            <span class="marquee-separator">✦</span>
        </div>
    </div>
</div>


