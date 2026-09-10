<?php
/**
 * Home Page - Raman Group (Redesigned Public Client Showcase)
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch 6 Featured Projects
$stmtProj = $db->query("SELECT p.*, c.name as category_name FROM projects p JOIN service_categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.id DESC LIMIT 6");
$featuredProjects = $stmtProj->fetchAll();

// Fetch Gallery Images
$stmtGal = $db->query("SELECT * FROM gallery WHERE is_active = 1 ORDER BY id DESC LIMIT 6");
$galleryImages = $stmtGal->fetchAll();

// Fetch Testimonials
$stmtTest = $db->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 4");
$testimonials = $stmtTest->fetchAll();

// Fetch FAQs
$stmtFaq = $db->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 4");
$faqs = $stmtFaq->fetchAll();

$customPageTitle = "Raman Group – Construction, Interior & Fabrication Services";
$customMetaDesc = "Raman Group delivers high-end turnkey civil construction, luxury interior design, and precision metal fabrication across North India.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. FULL-WIDTH CINEMATIC HERO SECTION -->
<section class="hero-cinematic stats-section">
    <!-- Visual Background Engine: Full-Screen Multi-Vertical Ken Burns Slideshow -->
    <div class="hero-bg-visual-wrapper">
        <!-- 6-Slide Multi-Vertical Cinematic Ken Burns Slideshow (Construction -> Interior -> Fabrication) -->
        <div class="hero-kenburns-slideshow">
            <div class="hero-slide hero-slide-1" style="background-image: url('assets/images/projects/construction-1.jpg');"></div>
            <div class="hero-slide hero-slide-2" style="background-image: url('assets/images/projects/construction-2.jpg');"></div>
            <div class="hero-slide hero-slide-3" style="background-image: url('assets/images/projects/interior-1.jpg');"></div>
            <div class="hero-slide hero-slide-4" style="background-image: url('assets/images/projects/interior-2.jpg');"></div>
            <div class="hero-slide hero-slide-5" style="background-image: url('assets/images/projects/fabrication-1.jpg');"></div>
            <div class="hero-slide hero-slide-6" style="background-image: url('assets/images/projects/fabrication-2.jpg');"></div>
        </div>
    </div>
    
    <!-- Multi-Layered Cinematic Dark Navy & Warm Gold Ambient Overlay -->
    <div class="hero-cinematic-overlay"></div>

    <div class="container hero-content-container">
        <div class="row align-items-center hero-main-row g-4">
            <div class="col-lg-8 col-xl-7 text-start">
                <div class="hero-eyebrow animate-fade-in">
                    <i class="fas fa-shield-alt text-warning"></i> RAMAN GROUP CONSTRUCTION • INTERIOR • FABRICATION
                </div>
                <h1 class="hero-title-cinematic animate-fade-in delay-1">
                    <strong>BUILDING</strong> SPACES.<br>
                    <strong>DESIGNING</strong> EXPERIENCES.
                </h1>
                <p class="hero-subtext-cinematic animate-fade-in delay-2">
                    Complete construction, luxury interior design, and precision structural fabrication solutions delivered with single-source executive accountability, IS-standard engineering, and transparent fixed BOQ pricing.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-fade-in delay-3">
                    <a href="services.php" class="btn btn-gold btn-lg font-heading fw-bold shadow-lg py-3 px-4"><i class="fas fa-th-large me-2"></i> Explore Our Services</a>
                    <a href="quote.php" class="btn btn-outline-gold btn-lg font-heading fw-bold py-3 px-4"><i class="fas fa-calculator me-2"></i> Request a Quote</a>
                </div>
            </div>
        </div>

        <!-- Integrated Glassmorphism Statistics Strip -->
        <div class="hero-stats-glass animate-fade-in delay-4">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="stat-box-cinematic">
                        <div class="stat-number" data-target="450">450+</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="stat-box-cinematic">
                        <div class="stat-number" data-target="380">380+</div>
                        <div class="stat-label">Happy Corporate Clients</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="stat-box-cinematic">
                        <div class="stat-number" data-target="18">18+</div>
                        <div class="stat-label">Years of Experience</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="stat-box-cinematic">
                        <div class="stat-number" data-target="65">65+</div>
                        <div class="stat-label">Professional Engineers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. SERVICES SECTION: THREE SPECIALIZED SOLUTIONS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">WHAT WE DO</span>
            <h2 class="section-title">ONE GROUP. THREE SPECIALIZED SOLUTIONS.</h2>
            <p class="text-muted max-w-600 mx-auto">We combine turnkey civil engineering, bespoke luxury space design, and high-precision metal fabrication under one unified corporate brand.</p>
        </div>

        <div class="row g-4">
            <!-- Vertical 1: Construction -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden project-card">
                    <div class="position-relative" style="height: 240px;">
                        <img src="assets/images/services/residential-construction.jpg" alt="Construction Division" class="w-100 h-100 object-fit-cover">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark border border-warning text-warning font-heading">10+ Projects</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-hard-hat text-warning fs-3 me-2"></i>
                            <h4 class="font-heading fw-bold text-dark mb-0">CONSTRUCTION</h4>
                        </div>
                        <p class="small text-muted flex-grow-1 mb-3">
                            Turnkey residential luxury villas, commercial corporate towers, civil earthworks, and structural retrofitting built to Seismic Zone IV standards.
                        </p>
                        <a href="services-construction.php" class="btn btn-outline-gold w-100 font-heading fw-bold">Explore Construction Services <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>

            <!-- Vertical 2: Interior -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden project-card">
                    <div class="position-relative" style="height: 240px;">
                        <img src="assets/images/services/home-interiors.jpg" alt="Interior Design Division" class="w-100 h-100 object-fit-cover">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark border border-warning text-warning font-heading">10+ Projects</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-couch text-warning fs-3 me-2"></i>
                            <h4 class="font-heading fw-bold text-dark mb-0">INTERIOR DESIGN</h4>
                        </div>
                        <p class="small text-muted flex-grow-1 mb-3">
                            Architectural space planning, 3D VR renders, German modular kitchens, custom wardrobe joinery, and turnkey commercial office fit-outs.
                        </p>
                        <a href="services-interior.php" class="btn btn-outline-gold w-100 font-heading fw-bold">Explore Interior Services <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>

            <!-- Vertical 3: Fabrication -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden project-card">
                    <div class="position-relative" style="height: 240px;">
                        <img src="assets/images/services/steel-fabrication.jpg" alt="Fabrication Division" class="w-100 h-100 object-fit-cover">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark border border-warning text-warning font-heading">8+ Projects</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-industry text-warning fs-3 me-2"></i>
                            <h4 class="font-heading fw-bold text-dark mb-0">FABRICATION</h4>
                        </div>
                        <p class="small text-muted flex-grow-1 mb-3">
                            Heavy structural steel PEB sheds, mirror SS 316 architectural glass railings, automated laser gates, and custom metalwork engineering.
                        </p>
                        <a href="services-fabrication.php" class="btn btn-outline-gold w-100 font-heading fw-bold">Explore Fabrication Services <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. FEATURED PROJECTS PORTFOLIO -->
<section class="section-padding">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div>
                <span class="section-subtitle">EXECUTION SHOWCASE</span>
                <h2 class="section-title mb-0">SELECTED PROJECTS</h2>
            </div>
            <a href="projects.php" class="btn btn-outline-gold mt-3 mt-md-0 font-heading fw-bold">View All Projects <i class="fas fa-arrow-right ms-2"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredProjects as $proj): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-thumb">
                            <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], $proj['category_name']) ?>" alt="<?= e($proj['title']) ?>">
                            <span class="project-category-badge"><?= e($proj['category_name']) ?></span>
                        </div>
                        <div class="project-body">
                            <h4 class="project-title"><?= e($proj['title']) ?></h4>
                            <div class="project-meta text-muted mb-2">
                                <i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($proj['location']) ?> &bull; 
                                <span class="badge bg-success bg-opacity-25 text-success ms-1"><?= e($proj['status'] ?? 'Completed') ?></span>
                            </div>
                            <p class="small text-muted mb-3"><?= e(mb_strimwidth($proj['short_description'], 0, 110, '...')) ?></p>
                            <a href="project-detail.php?id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-gold w-100">View Detailed Showcase <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. ABOUT RAMAN GROUP SPLIT SECTION -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="assets/images/projects/construction-2.jpg" alt="Raman Group Architectural Corporate Headquarter" class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 end-0 bg-dark text-white p-4 rounded-4 m-3 border border-warning shadow-lg d-none d-sm-block">
                        <div class="h2 font-heading text-warning mb-0">18+ Years</div>
                        <div class="small text-uppercase fw-bold text-light">Integrated Excellence</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-subtitle">ABOUT RAMAN GROUP</span>
                <h2 class="section-title mb-4">Building with Purpose. Designing with Precision.</h2>
                <p class="text-muted mb-4">
                    Founded in 2006, <strong>Raman Group</strong> is a premier multi-disciplinary infrastructure conglomerate. By integrating Civil Construction, Interior Architecture, and Heavy Structural Metal Fabrication under one unified corporate umbrella, we eliminate multi-vendor friction, reduce project build times by up to 30%, and deliver single-source executive peace of mind.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-warning fs-3"></i>
                            <span class="fw-bold text-dark">Turnkey Construction</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-warning fs-3"></i>
                            <span class="fw-bold text-dark">Luxury Interior Architecture</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-warning fs-3"></i>
                            <span class="fw-bold text-dark">Precision Metalwork</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 shadow-sm">
                            <i class="fas fa-check-circle text-warning fs-3"></i>
                            <span class="fw-bold text-dark">End-to-End Management</span>
                        </div>
                    </div>
                </div>

                <a href="about.php" class="btn btn-gold font-heading fw-bold px-4 py-3"><i class="fas fa-compass me-2"></i> Discover Our Story</a>
            </div>
        </div>
    </div>
</section>

<!-- 6. PROCESS SECTION: 7-STEP TIMELINE -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">STRUCTURED WORKFLOW</span>
            <h2 class="section-title">Our 7-Step Execution Process</h2>
            <p class="text-muted max-w-600 mx-auto">From initial consultation to final handover, every stage is managed with institutional discipline and daily cloud milestone tracking.</p>
        </div>

        <div class="timeline-7step-wrap">
            <div class="step-7-card">
                <div class="step-7-num">01</div>
                <h5 class="step-7-title">Consultation</h5>
                <p class="small text-muted mb-0">Survey & requirements</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">02</div>
                <h5 class="step-7-title">Planning</h5>
                <p class="small text-muted mb-0">Structural ETABS</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">03</div>
                <h5 class="step-7-title">Design</h5>
                <p class="small text-muted mb-0">3D VR renders</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">04</div>
                <h5 class="step-7-title">Estimation</h5>
                <p class="small text-muted mb-0">Fixed BOQ rate lock</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">05</div>
                <h5 class="step-7-title">Execution</h5>
                <p class="small text-muted mb-0">On-site construction</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">06</div>
                <h5 class="step-7-title">Quality Check</h5>
                <p class="small text-muted mb-0">Ultrasonic radiograph</p>
            </div>
            <div class="step-7-card">
                <div class="step-7-num">07</div>
                <h5 class="step-7-title">Handover</h5>
                <p class="small text-muted mb-0">Occupancy & 10yr warranty</p>
            </div>
        </div>
    </div>
</section>

<!-- 7. GALLERY MASONRY SECTION -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">VISUAL ARCHIVE</span>
            <h2 class="section-title">Project Execution Gallery</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($galleryImages as $img): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="gallery-item shadow-sm lightbox-trigger" data-image="<?= getImageUrl($img['image_path'], $img['title'], 'Project') ?>" data-title="<?= e($img['title']) ?> - <?= e($img['location']) ?>" style="cursor: pointer;">
                        <img src="<?= getImageUrl($img['image_path'], $img['title'], 'Project') ?>" alt="<?= e($img['title']) ?>" class="w-100 h-100 object-fit-cover">
                        <div class="gallery-overlay">
                            <h5 class="text-white font-heading fw-bold mb-1"><?= e($img['title']) ?></h5>
                            <small class="text-warning"><i class="fas fa-map-marker-alt me-1"></i> <?= e($img['location']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="gallery.php" class="btn btn-outline-gold font-heading fw-bold"><i class="fas fa-images me-2"></i> View Complete Gallery Archive</a>
        </div>
    </div>
</section>

<!-- 8. WHY CHOOSE US -->
<section class="section-padding bg-dark text-white">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle text-warning">THE RAMAN ADVANTAGE</span>
            <h2 class="section-title text-white">Why Choose Raman Group</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-award"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Quality First</h5>
                    <p class="small text-secondary mb-0">IS-certified M25 concrete, FE550D structural TMT steel, and imported Blum German fittings exclusively.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-user-shield"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Experienced Professionals</h5>
                    <p class="small text-secondary mb-0">Led by principal civil structural engineers and senior architects with 18+ years of field experience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-layer-group"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">End-to-End Solutions</h5>
                    <p class="small text-secondary mb-0">From soil load testing and municipal clearances to custom interior furniture joinery and laser metalwork.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Transparent Process</h5>
                    <p class="small text-secondary mb-0">Fixed BOQ pricing rate locks protecting clients against mid-project cost inflation or hidden costs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-clock"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Timely Delivery</h5>
                    <p class="small text-secondary mb-0">Contractually guaranteed project milestone schedules backed by liquidated damage clauses.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-hard-hat"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Professional Execution</h5>
                    <p class="small text-secondary mb-0">Dedicated full-time resident civil engineers managing daily site logs and safety audits.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. TESTIMONIALS SECTION -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">CLIENT VERDICT</span>
            <h2 class="section-title">What Our Corporate & Residential Clients Say</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
                <div class="col-lg-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4">
                        <div class="mb-3"><?= renderRatingStars($t['rating']) ?></div>
                        <p class="small text-muted fst-italic mb-4">"<?= e($t['content']) ?>"</p>
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <div class="bg-navy text-warning p-2 rounded-circle fw-bold font-heading" style="width:48px; height:48px; display:flex; align-items:center; justify-content:center;">
                                <?= strtoupper(substr($t['client_name'], 0, 2)) ?>
                            </div>
                            <div>
                                <h6 class="font-heading fw-bold mb-0 text-dark"><?= e($t['client_name']) ?></h6>
                                <small class="text-muted"><?= e($t['client_title']) ?> - <?= e($t['company']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="testimonials.php" class="btn btn-outline-gold font-heading fw-bold"><i class="fas fa-star me-2"></i> Read All Client Reviews</a>
        </div>
    </div>
</section>

<!-- 10. FINAL CALL TO ACTION SECTION -->
<section class="section-padding bg-dark text-white position-relative overflow-hidden" style="background-image: url('assets/images/projects/construction-1.jpg'); background-size: cover; background-position: center;">
    <div class="position-absolute inset-0" style="background: rgba(11, 18, 32, 0.92); z-index: 1;"></div>
    <div class="container position-relative z-2 text-center py-4">
        <h2 class="display-4 font-heading text-white fw-bold mb-3">LET'S BUILD SOMETHING EXCEPTIONAL.</h2>
        <p class="lead text-light max-w-700 mx-auto mb-4 fs-5 opacity-90">
            Have a construction, interior design, or structural fabrication project in mind? Speak with our principal engineers and receive a transparent BOQ rate card.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="quote.php" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-5 shadow-lg"><i class="fas fa-calculator me-2"></i> Request a Quote</a>
            <a href="contact.php" class="btn btn-outline-light btn-lg font-heading fw-bold py-3 px-5"><i class="fas fa-envelope me-2"></i> Contact Us Direct</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
