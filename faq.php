<?php
/**
 * FAQ Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$stmt = $db->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC");
$faqs = $stmt->fetchAll();

$customPageTitle = "Frequently Asked Questions – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Frequently Asked Questions</h1>
        <p class="text-light max-w-600 mx-auto">Find detailed answers about our civil warranties, modular interior timelines, and structural steel specs.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">FAQ</li>
            </ol>
        </nav>
    </div>
</div>

<!-- FAQ Accordion Section -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion accordion-custom" id="mainFaqAccordion">
                    <?php foreach ($faqs as $idx => $faq): ?>
                        <div class="accordion-item mb-3 border rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header" id="headingMain<?= $faq['id'] ?>">
                                <button class="accordion-button <?= $idx !== 0 ? 'collapsed' : '' ?> font-heading fw-bold fs-6 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMain<?= $faq['id'] ?>" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="collapseMain<?= $faq['id'] ?>">
                                    <i class="fas fa-circle-question text-warning me-3 fs-5"></i> <?= e($faq['question']) ?>
                                </button>
                            </h2>
                            <div id="collapseMain<?= $faq['id'] ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>" aria-labelledby="headingMain<?= $faq['id'] ?>" data-bs-parent="#mainFaqAccordion">
                                <div class="accordion-body text-muted lh-lg">
                                    <?= e($faq['answer']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="p-4 bg-light rounded-4 border text-center mt-5">
                    <h5 class="font-heading fw-bold mb-2">Still Have Unanswered Questions?</h5>
                    <p class="text-muted small mb-3">Our engineering desk is available to assist you with custom technical queries.</p>
                    <a href="contact.php" class="btn btn-gold btn-sm"><i class="fas fa-envelope me-1"></i> Contact Engineering Team</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
