<?php
/**
 * Dynamic Service Details Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if ($serviceId > 0) {
    $stmt = $db->prepare("SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN service_categories c ON s.category_id = c.id WHERE s.id = :id AND s.is_active = 1 LIMIT 1");
    $stmt->execute([':id' => $serviceId]);
} elseif (!empty($slug)) {
    $stmt = $db->prepare("SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN service_categories c ON s.category_id = c.id WHERE s.slug = :slug AND s.is_active = 1 LIMIT 1");
    $stmt->execute([':slug' => $slug]);
} else {
    // Default to first service
    $stmt = $db->query("SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN service_categories c ON s.category_id = c.id WHERE s.is_active = 1 ORDER BY s.id ASC LIMIT 1");
}

$service = $stmt->fetch();

if (!$service) {
    header("Location: services.php");
    exit;
}

// Fetch related projects for this service/category
$stmtRel = $db->prepare("SELECT * FROM projects WHERE category_id = :cat_id AND is_active = 1 ORDER BY id DESC LIMIT 3");
$stmtRel->execute([':cat_id' => $service['category_id']]);
$relatedProjects = $stmtRel->fetchAll();

// Parse features and benefits into arrays
$features = array_filter(array_map('trim', explode(';', $service['features'] ?? '')));
$benefits = array_filter(array_map('trim', explode(';', $service['benefits'] ?? '')));

$customPageTitle = $service['title'] . " – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <span class="badge bg-warning text-dark font-heading fw-bold px-3 py-2 mb-2 text-uppercase"><?= e($service['category_name']) ?></span>
        <h1 class="text-white font-heading display-5 fw-bold mb-2"><?= e($service['title']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="services.php">Services</a></li>
                <li class="breadcrumb-item"><a href="services-<?= e($service['category_slug']) ?>.php"><?= e($service['category_name']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($service['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Service Detail Section -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Left Main Content -->
            <div class="col-lg-8">
                <div class="position-relative mb-4 rounded-4 overflow-hidden shadow-lg" style="max-height: 450px;">
                    <img src="<?= getImageUrl($service['image'], $service['title'], $service['category_name']) ?>" alt="<?= e($service['title']) ?>" class="w-100 h-100 object-fit-cover">
                </div>

                <h2 class="font-heading fw-bold mb-3"><?= e($service['title']) ?> Overview</h2>
                <p class="lead text-dark fw-medium mb-4"><?= e($service['short_description']) ?></p>
                <div class="text-muted lh-lg mb-5">
                    <?= nl2br(e($service['detailed_description'])) ?>
                </div>

                <!-- Features Section -->
                <?php if (!empty($features)): ?>
                    <div class="mb-5 p-4 bg-light rounded-4 border border-warning border-opacity-25">
                        <h4 class="font-heading fw-bold text-dark mb-3"><i class="fas fa-star text-warning me-2"></i> Key Technical Features</h4>
                        <div class="row g-3">
                            <?php foreach ($features as $feat): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="fas fa-check-circle text-warning mt-1"></i>
                                        <span class="text-dark font-medium"><?= e($feat) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Benefits Section -->
                <?php if (!empty($benefits)): ?>
                    <div class="mb-5">
                        <h4 class="font-heading fw-bold text-dark mb-3"><i class="fas fa-shield-alt text-warning me-2"></i> Value Benefits & Guarantees</h4>
                        <div class="row g-3">
                            <?php foreach ($benefits as $ben): ?>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3 shadow-sm h-100">
                                        <i class="fas fa-award text-warning me-2"></i> <strong><?= e($ben) ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Related Projects -->
                <?php if (!empty($relatedProjects)): ?>
                    <div class="mt-5 pt-4 border-top">
                        <h3 class="font-heading fw-bold mb-4">Related Executed Projects</h3>
                        <div class="row g-4">
                            <?php foreach ($relatedProjects as $proj): ?>
                                <div class="col-md-4">
                                    <div class="project-card">
                                        <div class="project-thumb" style="height: 160px;">
                                            <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], $service['category_name']) ?>" alt="<?= e($proj['title']) ?>">
                                        </div>
                                        <div class="project-body p-3">
                                            <h6 class="project-title mb-1 small fw-bold"><?= e($proj['title']) ?></h6>
                                            <small class="text-muted d-block mb-2"><i class="fas fa-map-marker-alt text-warning"></i> <?= e($proj['location']) ?></small>
                                            <a href="project-detail.php?id=<?= $proj['id'] ?>" class="btn btn-xs btn-outline-gold w-100">View Project</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Sidebar CTA -->
            <div class="col-lg-4">
                <div class="p-4 bg-dark text-white rounded-4 shadow-lg sticky-top" style="top: 100px;">
                    <span class="badge bg-warning text-dark font-heading fw-bold mb-2">SERVICE ENQUIRY</span>
                    <h3 class="font-heading text-white mb-3">Request a Quote for <?= e($service['title']) ?></h3>
                    <p class="text-secondary small mb-4">
                        Speak directly with our principal project engineers to receive an itemized BOQ, material samples, and 3D architectural consultation.
                    </p>
                    <a href="quote.php?service=<?= urlencode($service['title']) ?>" class="btn btn-gold w-100 btn-lg mb-3">
                        <i class="fas fa-calculator me-2"></i> Get Custom Quote
                    </a>
                    <a href="contact.php" class="btn btn-outline-gold w-100 mb-4">
                        <i class="fas fa-envelope me-2"></i> Send General Enquiry
                    </a>

                    <hr class="border-secondary mb-4">

                    <h6 class="text-warning font-heading mb-3"><i class="fas fa-headset me-2"></i> Direct Support</h6>
                    <p class="small text-secondary mb-2"><i class="fas fa-phone-alt text-warning me-2"></i> <?= e(getSiteSetting('phone')) ?></p>
                    <p class="small text-secondary mb-0"><i class="fas fa-envelope text-warning me-2"></i> <?= e(getSiteSetting('email')) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
