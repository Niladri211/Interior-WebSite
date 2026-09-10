<?php
/**
 * 404 Custom Error Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

http_response_code(404);

$customPageTitle = "Page Not Found – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding min-vh-75 d-flex align-items-center bg-light-surface text-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="display-1 text-warning font-heading fw-extrabold mb-3" style="font-size: 8rem;">404</div>
                <h2 class="font-heading fw-bold display-6 mb-3">Page Not Found</h2>
                <p class="text-muted fs-5 mb-4 max-w-600 mx-auto">
                    The architectural page or resource you are looking for might have been moved, renamed, or is temporarily unavailable.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php" class="btn btn-gold btn-lg"><i class="fas fa-home me-2"></i> Return to Homepage</a>
                    <a href="contact.php" class="btn btn-outline-gold btn-lg"><i class="fas fa-envelope me-2"></i> Contact Desk</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
