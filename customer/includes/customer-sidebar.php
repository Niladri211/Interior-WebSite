<?php
/**
 * Customer Portal Sidebar
 * Raman Group Platform
 */
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="portal-sidebar">
    <div class="portal-brand">
        <a href="dashboard.php" class="text-decoration-none">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-building text-warning fs-3"></i>
                <div>
                    <div class="font-heading fw-bold text-white fs-5 mb-0">RAMAN GROUP</div>
                    <div class="text-warning small font-heading" style="font-size: 10px; letter-spacing: 1px;">CUSTOMER PORTAL</div>
                </div>
            </div>
        </a>
    </div>

    <nav class="portal-nav my-3 flex-grow-1">
        <a href="dashboard.php" class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-th-large text-warning"></i> Dashboard Overview
        </a>
        <a href="quotes.php" class="nav-link <?= $currentPage === 'quotes.php' ? 'active' : '' ?>">
            <i class="fas fa-calculator text-warning"></i> My Quote Requests
        </a>
        <a href="projects.php" class="nav-link <?= $currentPage === 'projects.php' ? 'active' : '' ?>">
            <i class="fas fa-hard-hat text-warning"></i> My Assigned Projects
        </a>
        <a href="enquiries.php" class="nav-link <?= $currentPage === 'enquiries.php' ? 'active' : '' ?>">
            <i class="fas fa-comments text-warning"></i> My Enquiries
        </a>
        <a href="profile.php" class="nav-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
            <i class="fas fa-user-cog text-warning"></i> Profile & Password
        </a>
    </nav>

    <div class="p-3 border-top border-secondary border-opacity-25 bg-dark text-center">
        <a href="logout.php" class="btn btn-outline-danger btn-sm w-100"><i class="fas fa-sign-out-alt me-1"></i> Sign Out</a>
    </div>
</aside>
