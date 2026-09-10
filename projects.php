<?php
/**
 * Projects Portfolio Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch all active projects
$query = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM projects p JOIN service_categories c ON p.category_id = c.id WHERE p.is_active = 1";
$params = [];

if ($selectedCategory > 0) {
    $query .= " AND p.category_id = :cat_id";
    $params[':cat_id'] = $selectedCategory;
}

$query .= " ORDER BY p.id DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$projects = $stmt->fetchAll();

$customPageTitle = "Projects Portfolio – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Our Execution Portfolio</h1>
        <p class="text-light max-w-600 mx-auto">Explore over 28+ showcased projects spanning Turnkey Civil Builds, Luxury Interior Architectures, and Structural Metalwork.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Portfolio Filter Tabs & Grid -->
<section class="section-padding">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="projects.php" class="filter-btn <?= $selectedCategory === 0 ? 'active' : '' ?>">All Projects (28)</a>
            <a href="projects.php?category=1" class="filter-btn <?= $selectedCategory === 1 ? 'active' : '' ?>"><i class="fas fa-hard-hat me-1"></i> Construction (10)</a>
            <a href="projects.php?category=2" class="filter-btn <?= $selectedCategory === 2 ? 'active' : '' ?>"><i class="fas fa-couch me-1"></i> Interior (10)</a>
            <a href="projects.php?category=3" class="filter-btn <?= $selectedCategory === 3 ? 'active' : '' ?>"><i class="fas fa-industry me-1"></i> Fabrication (8)</a>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4">
            <?php if (empty($projects)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open text-muted display-1 mb-3"></i>
                    <h4>No Projects Found</h4>
                    <p class="text-muted">There are no showcase projects under this category yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $proj): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="project-card">
                            <div class="project-thumb">
                                <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], $proj['category_name']) ?>" alt="<?= e($proj['title']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= getFallbackSvg($proj['title'], $proj['category_name']) ?>';">
                                <span class="project-category-badge"><?= e($proj['category_name']) ?></span>
                            </div>
                            <div class="project-body">
                                <h4 class="project-title"><?= e($proj['title']) ?></h4>
                                <div class="project-meta">
                                    <i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($proj['location']) ?> | 
                                    <i class="fas fa-check-circle text-success me-1"></i> <?= e($proj['status']) ?>
                                </div>
                                <p class="small text-muted mb-3"><?= e(mb_strimwidth($proj['short_description'], 0, 110, '...')) ?></p>
                                <a href="project-detail.php?id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-gold w-100"><i class="fas fa-eye me-1"></i> View Detailed Showcase</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
