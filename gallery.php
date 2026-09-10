<?php
/**
 * Interactive Photo Gallery Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$query = "SELECT g.*, c.name as category_name, c.slug as category_slug FROM gallery g JOIN service_categories c ON g.category_id = c.id WHERE g.is_active = 1";
$params = [];

if ($selectedCategory > 0) {
    $query .= " AND g.category_id = :cat_id";
    $params[':cat_id'] = $selectedCategory;
}

$query .= " ORDER BY g.id DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$galleryItems = $stmt->fetchAll();

$customPageTitle = "Photo Gallery – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Architectural Photo Gallery</h1>
        <p class="text-light max-w-600 mx-auto">Click any high-resolution image to activate the interactive zoomable lightbox.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gallery</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Gallery Grid & Lightbox Section -->
<section class="section-padding">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="gallery.php" class="filter-btn <?= $selectedCategory === 0 ? 'active' : '' ?>">All Photos (<?= count($galleryItems) ?>)</a>
            <a href="gallery.php?category=1" class="filter-btn <?= $selectedCategory === 1 ? 'active' : '' ?>"><i class="fas fa-hard-hat me-1"></i> Construction</a>
            <a href="gallery.php?category=2" class="filter-btn <?= $selectedCategory === 2 ? 'active' : '' ?>"><i class="fas fa-couch me-1"></i> Interior</a>
            <a href="gallery.php?category=3" class="filter-btn <?= $selectedCategory === 3 ? 'active' : '' ?>"><i class="fas fa-industry me-1"></i> Fabrication</a>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-4">
            <?php foreach ($galleryItems as $g): ?>
                <div class="col-lg-4 col-md-6 filter-item" data-category="<?= e($g['category_slug']) ?>">
                    <div class="gallery-item lightbox-trigger" data-image="<?= getImageUrl($g['image_path'], $g['title'], $g['category_name']) ?>" data-title="<?= e($g['title']) ?> (<?= e($g['location']) ?>)">
                        <img src="<?= getImageUrl($g['image_path'], $g['title'], $g['category_name']) ?>" alt="<?= e($g['title']) ?>">
                        <div class="gallery-overlay">
                            <span class="badge bg-warning text-dark font-heading fw-bold align-self-start mb-2"><?= e($g['category_name']) ?></span>
                            <div class="gallery-title"><?= e($g['title']) ?></div>
                            <small class="text-light opacity-75"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($g['location']) ?></small>
                        </div>
                        <div class="gallery-zoom-icon"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
