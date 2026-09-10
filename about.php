<?php
/**
 * About Us Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch Team Members
$stmtTeam = $db->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC");
$teamMembers = $stmtTeam->fetchAll();

$customPageTitle = "About Us – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">About Raman Group</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main About Content -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="section-subtitle">OUR LEGACY & VISION</span>
                <h2 class="section-title mb-4">Unified Infrastructure Excellence Since 2006</h2>
                <p class="text-muted">
                    <strong>Raman Group</strong> was established with a singular vision: to bridge the gap between heavy civil construction engineering, high-end interior design, and industrial structural metal fabrication.
                </p>
                <p class="text-muted">
                    Traditionally, property developers and homeowners were forced to coordinate with multiple disjointed vendors—civil contractors, interior designers, steel fabricators, and MEP technicians—leading to cost overruns, quality mismatch, and project delays. Raman Group solves this by bringing all three disciplines under one unified roof with single-source accountability.
                </p>
                
                <div class="p-4 bg-light rounded-4 border border-warning border-opacity-25 mt-4">
                    <h5 class="font-heading fw-bold text-dark mb-2"><i class="fas fa-quote-left text-warning me-2"></i> Our Core Philosophy</h5>
                    <p class="small text-muted mb-0">"We don't just build structures; we craft durable, functional, and aesthetically captivating environments engineered to endure for generations."</p>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= getImageUrl('assets/images/about-vision.jpg', 'Engineering Excellence', 'Construction') ?>" alt="About Raman Group" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>

        <!-- Vision & Mission Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="p-5 bg-dark text-white rounded-4 h-100 position-relative overflow-hidden shadow-lg">
                    <div class="display-3 text-warning opacity-25 position-absolute top-0 end-0 m-3"><i class="fas fa-eye"></i></div>
                    <h3 class="font-heading text-warning mb-3">Our Vision</h3>
                    <p class="text-light lead fs-6">
                        To be recognized as India's most trusted integrated service group for Construction, Interior Architecture, and Heavy Fabrication, known for zero structural compromise and timely delivery.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-5 bg-dark-card text-white rounded-4 h-100 position-relative overflow-hidden shadow-lg border border-secondary border-opacity-25">
                    <div class="display-3 text-warning opacity-25 position-absolute top-0 end-0 m-3"><i class="fas fa-bullseye"></i></div>
                    <h3 class="font-heading text-warning mb-3">Our Mission</h3>
                    <p class="text-light lead fs-6">
                        To deliver world-class infrastructure and spatial solutions through IS-spec materials, advanced automated manufacturing, photorealistic 3D visualization, and transparent pricing.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">THE RAMAN GROUP ADVANTAGE</span>
            <h2 class="section-title">Why Industry Leaders Choose Us</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-layer-group"></i></div>
                    <h5 class="font-heading fw-bold">3 Verticals, 1 Accountability</h5>
                    <p class="text-muted small">No multi-vendor friction or blame games. We handle civil build, interior fit-outs, and metal fabrication under one contract.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-microchip"></i></div>
                    <h5 class="font-heading fw-bold">In-House Manufacturing</h5>
                    <p class="text-muted small">German CNC panel processing and PUR laser edge-banding for dust-free, razor-sharp modular interior execution.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-file-contract"></i></div>
                    <h5 class="font-heading fw-bold">10-Year Structural Warranty</h5>
                    <p class="text-muted small">Backing our civil engineering and structural steel welding with a certified 10-year structural warranty.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-calculator"></i></div>
                    <h5 class="font-heading fw-bold">Transparent Itemized BOQ</h5>
                    <p class="text-muted small">Zero hidden costs. Fixed price lock agreements protect you against raw material inflation during construction.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-user-shield"></i></div>
                    <h5 class="font-heading fw-bold">Certified Engineers</h5>
                    <p class="text-muted small">Dedicated M.Tech structural consultants, certified AWS MIG/TIG welders, and senior site engineers on every site.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                    <div class="text-warning fs-1 mb-3"><i class="fas fa-clock"></i></div>
                    <h5 class="font-heading fw-bold">On-Time Completion Penalty</h5>
                    <p class="text-muted small">We guarantee fixed completion dates with liquidated damage penalty clauses for guaranteed peace of mind.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">MEET OUR LEADERS</span>
            <h2 class="section-title">The Minds Behind Raman Group</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($teamMembers as $team): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden text-center h-100">
                        <div class="position-relative" style="height: 280px;">
                            <img src="<?= getImageUrl($team['image'], $team['name'], 'Team') ?>" alt="<?= e($team['name']) ?>" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 start-0 end-0 bg-gradient p-2 text-white bg-dark bg-opacity-75">
                                <small class="text-warning font-heading fw-bold"><?= e($team['experience_years']) ?>+ Yrs Experience</small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="font-heading fw-bold mb-1"><?= e($team['name']) ?></h5>
                            <p class="text-warning small fw-semibold mb-2"><?= e($team['designation']) ?></p>
                            <p class="small text-muted mb-0"><?= e($team['bio']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
