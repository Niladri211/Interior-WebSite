<?php
/**
 * Admin Sidebar Navigation
 */
$adminCurrentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <div class="brand-header">
        <a href="dashboard.php" class="text-decoration-none">
            <div class="brand-title">
                <i class="fas fa-building text-warning me-2"></i> RAMAN GROUP
            </div>
            <div class="small text-muted font-monospace mt-1">Management Portal</div>
        </a>
    </div>

    <div class="nav-label">Core Navigation</div>
    <ul class="admin-nav">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= in_array($adminCurrentPage, ['dashboard.php', 'index.php']) ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
    </ul>

    <div class="nav-label">Customer & Business</div>
    <ul class="admin-nav">
        <li class="nav-item">
            <a href="customers.php" class="nav-link <?= $adminCurrentPage === 'customers.php' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Customers
            </a>
        </li>
        <li class="nav-item">
            <a href="quotes.php" class="nav-link <?= in_array($adminCurrentPage, ['quotes.php', 'quote-requests.php']) ? 'active' : '' ?>">
                <i class="fas fa-calculator"></i> Quote Requests
            </a>
        </li>
        <li class="nav-item">
            <a href="contact-messages.php" class="nav-link <?= in_array($adminCurrentPage, ['contact-messages.php', 'enquiries.php']) ? 'active' : '' ?>">
                <i class="fas fa-inbox"></i> Enquiries & Messages
            </a>
        </li>
    </ul>

    <div class="nav-label">Content Management</div>
    <ul class="admin-nav">
        <li class="nav-item">
            <a href="services.php" class="nav-link <?= $adminCurrentPage === 'services.php' ? 'active' : '' ?>">
                <i class="fas fa-tools"></i> Services
            </a>
        </li>
        <li class="nav-item">
            <a href="projects.php" class="nav-link <?= str_contains($adminCurrentPage, 'project') ? 'active' : '' ?>">
                <i class="fas fa-hard-hat"></i> Projects Portfolio
            </a>
        </li>
        <li class="nav-item">
            <a href="gallery.php" class="nav-link <?= $adminCurrentPage === 'gallery.php' ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Photo Gallery
            </a>
        </li>
        <li class="nav-item">
            <a href="testimonials.php" class="nav-link <?= $adminCurrentPage === 'testimonials.php' ? 'active' : '' ?>">
                <i class="fas fa-comment-dots"></i> Testimonials
            </a>
        </li>
        <li class="nav-item">
            <a href="team.php" class="nav-link <?= $adminCurrentPage === 'team.php' ? 'active' : '' ?>">
                <i class="fas fa-users-gear"></i> Team Members
            </a>
        </li>
        <li class="nav-item">
            <a href="faqs.php" class="nav-link <?= $adminCurrentPage === 'faqs.php' ? 'active' : '' ?>">
                <i class="fas fa-circle-question"></i> FAQs
            </a>
        </li>
    </ul>

    <div class="nav-label">System</div>
    <ul class="admin-nav mb-4">
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?= $adminCurrentPage === 'settings.php' ? 'active' : '' ?>">
                <i class="fas fa-sliders"></i> Site Settings
            </a>
        </li>
        <li class="nav-item mt-3">
            <a href="logout.php" class="nav-link text-danger">
                <i class="fas fa-right-from-bracket text-danger"></i> Logout
            </a>
        </li>
    </ul>
</aside>

