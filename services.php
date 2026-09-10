<?php
/**
 * Services Overview Hub Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch categories with service counts
$stmtCats = $db->query("SELECT c.*, COUNT(s.id) as total_services FROM service_categories c LEFT JOIN services s ON c.id = s.category_id AND s.is_active = 1 GROUP BY c.id ORDER BY c.sort_order ASC");
$categories = $stmtCats->fetchAll();

// Fetch all active services grouped by category
$stmtServices = $db->query("SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN service_categories c ON s.category_id = c.id WHERE s.is_active = 1 ORDER BY s.category_id ASC, s.id ASC");
$allServices = $stmtServices->fetchAll();

// Get active category filter from URL parameter
$activeCategory = strtolower($_GET['category'] ?? 'all');
if (!in_array($activeCategory, ['all', 'construction', 'interior', 'fabrication'])) {
    $activeCategory = 'all';
}

// Calculate individual counts
$countAll = count($allServices);
$countConstruction = 0;
$countInterior = 0;
$countFabrication = 0;

foreach ($allServices as $s) {
    $slug = strtolower($s['category_slug']);
    if ($slug === 'construction') $countConstruction++;
    elseif ($slug === 'interior') $countInterior++;
    elseif ($slug === 'fabrication') $countFabrication++;
}

$customPageTitle = "Services – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Our Comprehensive Services</h1>
        <p class="text-light max-w-600 mx-auto">Full-spectrum Civil Construction, Luxury Interior Architecture, and Heavy Structural Metal Fabrication under one unified corporate brand.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Verticals Navigation Cards -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <a href="services-construction.php" class="text-decoration-none">
                    <div class="p-4 bg-white rounded-4 border shadow-sm h-100 d-flex align-items-center gap-3 hover-shadow transition">
                        <div class="bg-warning text-dark p-3 rounded-4 fs-2"><i class="fas fa-hard-hat"></i></div>
                        <div>
                            <h4 class="font-heading fw-bold text-dark mb-1">Construction</h4>
                            <p class="small text-muted mb-0"><?= $countConstruction ?> Specialized Services</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href="services-interior.php" class="text-decoration-none">
                    <div class="p-4 bg-white rounded-4 border shadow-sm h-100 d-flex align-items-center gap-3 hover-shadow transition">
                        <div class="bg-warning text-dark p-3 rounded-4 fs-2"><i class="fas fa-couch"></i></div>
                        <div>
                            <h4 class="font-heading fw-bold text-dark mb-1">Interior Design</h4>
                            <p class="small text-muted mb-0"><?= $countInterior ?> Specialized Services</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href="services-fabrication.php" class="text-decoration-none">
                    <div class="p-4 bg-white rounded-4 border shadow-sm h-100 d-flex align-items-center gap-3 hover-shadow transition">
                        <div class="bg-warning text-dark p-3 rounded-4 fs-2"><i class="fas fa-industry"></i></div>
                        <div>
                            <h4 class="font-heading fw-bold text-dark mb-1">Fabrication</h4>
                            <p class="small text-muted mb-0"><?= $countFabrication ?> Specialized Services</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs mb-5 d-flex flex-wrap justify-content-center gap-2" id="servicesFilterTabs">
            <a href="services.php" class="filter-btn <?= $activeCategory === 'all' ? 'active' : '' ?>" data-filter="all">
                <i class="fas fa-th-large me-1"></i> ALL SERVICES (<?= $countAll ?>)
            </a>
            <a href="services.php?category=construction" class="filter-btn <?= $activeCategory === 'construction' ? 'active' : '' ?>" data-filter="construction">
                <i class="fas fa-hard-hat me-1"></i> CONSTRUCTION (<?= $countConstruction ?>)
            </a>
            <a href="services.php?category=interior" class="filter-btn <?= $activeCategory === 'interior' ? 'active' : '' ?>" data-filter="interior">
                <i class="fas fa-couch me-1"></i> INTERIOR (<?= $countInterior ?>)
            </a>
            <a href="services.php?category=fabrication" class="filter-btn <?= $activeCategory === 'fabrication' ? 'active' : '' ?>" data-filter="fabrication">
                <i class="fas fa-industry me-1"></i> FABRICATION (<?= $countFabrication ?>)
            </a>
        </div>

        <!-- Services Grid -->
        <div class="row g-4" id="servicesGrid">
            <?php foreach ($allServices as $serv): 
                $slug = strtolower($serv['category_slug']);
                // Determine Category Badge text & target landing page
                if ($slug === 'construction') {
                    $badgeText = 'CONSTRUCTION';
                    $landingUrl = 'services-construction.php';
                } elseif ($slug === 'interior') {
                    $badgeText = 'INTERIOR';
                    $landingUrl = 'services-interior.php';
                } else {
                    $badgeText = 'FABRICATION';
                    $landingUrl = 'services-fabrication.php';
                }

                $isVisible = ($activeCategory === 'all' || $activeCategory === $slug);
            ?>
                <div class="col-lg-4 col-md-6 filter-item" data-category="<?= e($slug) ?>" style="<?= $isVisible ? '' : 'display: none;' ?>">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden service-card-wrap">
                        <div class="service-card-img-box">
                            <img src="<?= getImageUrl($serv['image'], $serv['title'], $serv['category_name']) ?>" alt="<?= e($serv['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="service-card-category-badge">
                                <?= $badgeText ?>
                            </span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas <?= e($serv['icon']) ?> text-warning fs-4 flex-shrink-0"></i>
                                <h4 class="font-heading fw-bold mb-0 text-dark fs-5"><?= e($serv['title']) ?></h4>
                            </div>
                            <p class="small text-muted flex-grow-1 mb-4" style="line-height: 1.6;"><?= e($serv['short_description']) ?></p>
                            <div class="d-flex gap-2">
                                <a href="<?= $landingUrl ?>" class="btn btn-gold btn-sm font-heading fw-bold py-2 px-3 flex-grow-1 shadow-sm"><i class="fas fa-th-large me-1"></i> Explore Service</a>
                                <a href="quote.php?service=<?= urlencode($serv['title']) ?>" class="btn btn-outline-gold btn-sm font-heading fw-bold py-2 px-3"><i class="fas fa-calculator me-1"></i> Quote</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Filter Script for Seamless URL & Client-Side Tab Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('#servicesFilterTabs .filter-btn');
    const items = document.querySelectorAll('#servicesGrid .filter-item');

    function applyFilter(filterVal, updateUrl = true) {
        tabs.forEach(t => {
            if (t.getAttribute('data-filter') === filterVal) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });

        items.forEach(item => {
            const cat = item.getAttribute('data-category');
            if (filterVal === 'all' || cat === filterVal) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 30);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.96)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 200);
            }
        });

        if (updateUrl && window.history && window.history.pushState) {
            const newUrl = filterVal === 'all' ? 'services.php' : 'services.php?category=' + filterVal;
            window.history.pushState({ category: filterVal }, '', newUrl);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const filterVal = this.getAttribute('data-filter');
            applyFilter(filterVal, true);
        });
    });

    window.addEventListener('popstate', function(e) {
        const urlParams = new URLSearchParams(window.location.search);
        const catParam = urlParams.get('category') || 'all';
        applyFilter(catParam, false);
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
