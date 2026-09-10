<?php
/**
 * Dynamic Project Showcase Detail Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($projectId > 0) {
    $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM projects p JOIN service_categories c ON p.category_id = c.id WHERE p.id = :id AND p.is_active = 1 LIMIT 1");
    $stmt->execute([':id' => $projectId]);
} else {
    // Default to first project
    $stmt = $db->query("SELECT p.*, c.name as category_name, c.slug as category_slug FROM projects p JOIN service_categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.id ASC LIMIT 1");
}

$project = $stmt->fetch();

if (!$project) {
    header("Location: projects.php");
    exit;
}

// Fetch additional project images
$stmtImgs = $db->prepare("SELECT * FROM project_images WHERE project_id = :proj_id");
$stmtImgs->execute([':proj_id' => $project['id']]);
$extraImages = $stmtImgs->fetchAll();

// Fetch Related Projects
$stmtRel = $db->prepare("SELECT * FROM projects WHERE category_id = :cat_id AND id != :proj_id AND is_active = 1 ORDER BY id DESC LIMIT 3");
$stmtRel->execute([':cat_id' => $project['category_id'], ':proj_id' => $project['id']]);
$relatedProjects = $stmtRel->fetchAll();

$customPageTitle = $project['title'] . " Showcase – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <span class="badge bg-warning text-dark font-heading fw-bold px-3 py-2 mb-2 text-uppercase"><?= e($project['category_name']) ?> SHOWCASE</span>
        <h1 class="text-white font-heading display-5 fw-bold mb-2"><?= e($project['title']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="projects.php">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($project['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Project Details Section -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Left Main Details -->
            <div class="col-lg-8">
                <div class="position-relative mb-4 rounded-4 overflow-hidden shadow-lg" style="max-height: 480px;">
                    <img src="<?= getImageUrl($project['featured_image'], $project['title'], $project['category_name']) ?>" alt="<?= e($project['title']) ?>" class="w-100 h-100 object-fit-cover">
                </div>

                <h2 class="font-heading fw-bold mb-3"><?= e($project['title']) ?></h2>
                <p class="lead text-dark fw-medium mb-4"><?= e($project['short_description']) ?></p>

                <h4 class="font-heading fw-bold text-dark mb-3"><i class="fas fa-file-alt text-warning me-2"></i> Project Overview</h4>
                <div class="text-muted lh-lg mb-5">
                    <?= nl2br(e($project['description'])) ?>
                </div>

                <!-- Scope of Work -->
                <?php if (!empty($project['scope_of_work'])): ?>
                    <div class="p-4 bg-light rounded-4 border border-warning border-opacity-25 mb-4">
                        <h4 class="font-heading fw-bold text-dark mb-2"><i class="fas fa-list-check text-warning me-2"></i> Scope of Work Executed</h4>
                        <p class="text-muted mb-0"><?= e($project['scope_of_work']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Materials Used -->
                <?php if (!empty($project['materials_used'])): ?>
                    <div class="p-4 bg-light rounded-4 border mb-4">
                        <h4 class="font-heading fw-bold text-dark mb-2"><i class="fas fa-cubes text-warning me-2"></i> Materials & Specification Standards</h4>
                        <p class="text-muted mb-0"><?= e($project['materials_used']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Project Highlights -->
                <?php if (!empty($project['highlights'])): ?>
                    <div class="p-4 bg-dark text-white rounded-4 mb-5 border border-warning border-opacity-25">
                        <h4 class="font-heading text-warning mb-2"><i class="fas fa-trophy text-warning me-2"></i> Key Project Highlights</h4>
                        <p class="text-light mb-0"><?= e($project['highlights']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Project Gallery Images -->
                <h4 class="font-heading fw-bold mb-3"><i class="fas fa-images text-warning me-2"></i> Project Image Gallery</h4>
                <div class="row g-3 mb-5">
                    <div class="col-md-4 col-6">
                        <div class="gallery-item lightbox-trigger" data-image="<?= getImageUrl($project['featured_image'], $project['title'], $project['category_name']) ?>" data-title="<?= e($project['title']) ?> - Main View">
                            <img src="<?= getImageUrl($project['featured_image'], $project['title'], $project['category_name']) ?>" alt="Main View">
                            <div class="gallery-zoom-icon"><i class="fas fa-search-plus"></i></div>
                        </div>
                    </div>
                    <?php if (!empty($extraImages)): ?>
                        <?php foreach ($extraImages as $img): ?>
                            <div class="col-md-4 col-6">
                                <div class="gallery-item lightbox-trigger" data-image="<?= getImageUrl($img['image_path'], $project['title'], $project['category_name']) ?>" data-title="<?= e($img['caption'] ?: $project['title']) ?>">
                                    <img src="<?= getImageUrl($img['image_path'], $project['title'], $project['category_name']) ?>" alt="<?= e($img['caption']) ?>">
                                    <div class="gallery-zoom-icon"><i class="fas fa-search-plus"></i></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback Gallery Slots -->
                        <div class="col-md-4 col-6">
                            <div class="gallery-item lightbox-trigger" data-image="<?= getImageUrl('', $project['title'] . ' Site Work', $project['category_name']) ?>" data-title="Site Execution View">
                                <img src="<?= getImageUrl('', $project['title'] . ' Site Work', $project['category_name']) ?>" alt="Site Work">
                                <div class="gallery-zoom-icon"><i class="fas fa-search-plus"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="gallery-item lightbox-trigger" data-image="<?= getImageUrl('', $project['title'] . ' Final Finish', $project['category_name']) ?>" data-title="Final Finishing View">
                                <img src="<?= getImageUrl('', $project['title'] . ' Final Finish', $project['category_name']) ?>" alt="Final Finish">
                                <div class="gallery-zoom-icon"><i class="fas fa-search-plus"></i></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Related Projects -->
                <?php if (!empty($relatedProjects)): ?>
                    <div class="pt-4 border-top">
                        <h3 class="font-heading fw-bold mb-4">Explore Similar Executed Projects</h3>
                        <div class="row g-4">
                            <?php foreach ($relatedProjects as $rel): ?>
                                <div class="col-md-4">
                                    <div class="project-card">
                                        <div class="project-thumb" style="height: 160px;">
                                            <img src="<?= getImageUrl($rel['featured_image'], $rel['title'], $project['category_name']) ?>" alt="<?= e($rel['title']) ?>">
                                        </div>
                                        <div class="project-body p-3">
                                            <h6 class="project-title mb-1 small fw-bold"><?= e($rel['title']) ?></h6>
                                            <small class="text-muted d-block mb-2"><i class="fas fa-map-marker-alt text-warning"></i> <?= e($rel['location']) ?></small>
                                            <a href="project-detail.php?id=<?= $rel['id'] ?>" class="btn btn-xs btn-outline-gold w-100">View Project</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Meta Sidebar -->
            <div class="col-lg-4">
                <div class="p-4 bg-white rounded-4 border shadow-lg sticky-top" style="top: 100px;">
                    <h4 class="font-heading fw-bold text-dark border-bottom pb-3 mb-4">Project Meta Data</h4>
                    
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-warning"><i class="fas fa-tag"></i></div>
                            <div>
                                <small class="text-muted d-block">Vertical Category</small>
                                <span class="fw-bold text-dark"><?= e($project['category_name']) ?></span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-warning"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <small class="text-muted d-block">Project Location</small>
                                <span class="fw-bold text-dark"><?= e($project['location'] ?: 'Delhi NCR') ?></span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-warning"><i class="fas fa-user-tie"></i></div>
                            <div>
                                <small class="text-muted d-block">Client Name</small>
                                <span class="fw-bold text-dark"><?= e($project['client_name'] ?: 'Corporate Estate Client') ?></span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-warning"><i class="fas fa-calendar-check"></i></div>
                            <div>
                                <small class="text-muted d-block">Completion Date</small>
                                <span class="fw-bold text-dark"><?= e($project['completion_date'] ?: 'Recently Completed') ?></span>
                            </div>
                        </li>
                        <li class="mb-4 d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-warning"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <small class="text-muted d-block">Current Status</small>
                                <span class="badge bg-success px-3 py-1"><?= e($project['status']) ?></span>
                            </div>
                        </li>
                    </ul>

                    <a href="quote.php?project=<?= urlencode($project['title']) ?>" class="btn btn-gold w-100 btn-lg mb-2">
                        <i class="fas fa-calculator me-2"></i> Request Similar Project
                    </a>
                    <a href="contact.php" class="btn btn-outline-gold w-100">
                        <i class="fas fa-envelope me-2"></i> General Inquiry
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
