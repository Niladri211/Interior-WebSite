<?php
/**
 * Client Testimonials Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$stmt = $db->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC");
$testimonials = $stmt->fetchAll();

$customPageTitle = "Testimonials – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Client Testimonials & Ratings</h1>
        <p class="text-light max-w-600 mx-auto">Read authentic reviews from industrial managers, estate owners, and commercial developers.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Testimonials Grid -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
                <div class="col-lg-6">
                    <div class="p-4 bg-white border rounded-4 shadow-sm h-100 position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <?= renderRatingStars($t['rating']) ?>
                            <span class="badge bg-light text-dark border"><i class="fas fa-tag text-warning me-1"></i> <?= e($t['project_type']) ?></span>
                        </div>
                        <p class="text-muted lh-base fs-6 mb-4">"<?= e($t['content']) ?>"</p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top">
                            <div class="bg-dark text-warning font-heading fw-bold rounded-circle d-flex align-items-center justify-content-center border border-warning" style="width:50px; height:50px;">
                                <?= strtoupper(substr($t['client_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h5 class="font-heading fw-bold mb-0 text-dark"><?= e($t['client_name']) ?></h5>
                                <small class="text-muted"><?= e($t['client_title']) ?> - <?= e($t['company']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="p-5 bg-dark text-white rounded-4 mt-5 text-center position-relative overflow-hidden">
            <h2 class="font-heading text-warning mb-3">Join Our List of Satisfied Clients</h2>
            <p class="text-light max-w-600 mx-auto mb-4">Experience seamless turnkey execution with India's leading unified construction and fabrication group.</p>
            <a href="quote.php" class="btn btn-gold btn-lg"><i class="fas fa-calculator me-2"></i> Request Project Quotation</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
