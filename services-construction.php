<?php
/**
 * Dedicated Construction Landing Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch Construction Category & Services (category_id = 1)
$stmtCat = $db->prepare("SELECT * FROM service_categories WHERE id = 1 LIMIT 1");
$stmtCat->execute();
$category = $stmtCat->fetch();

// 8 Core Construction Services list
$constructionServices = [
    [
        'title' => 'Residential Construction',
        'icon' => 'fa-home',
        'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Custom luxury villas, high-rise apartments, and independent residences engineered to Seismic Zone IV structural standards.',
        'badge' => 'Turnkey Villas'
    ],
    [
        'title' => 'Commercial Construction',
        'icon' => 'fa-building',
        'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Corporate headquarters, shopping plazas, tech parks, and commercial towers built with post-tensioned slab technology.',
        'badge' => 'Corporate Towers'
    ],
    [
        'title' => 'Industrial Construction',
        'icon' => 'fa-industry',
        'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Heavy manufacturing plants, logistics hubs, specialized cold storage facilities, and industrial PEB steel sheds.',
        'badge' => 'Heavy Industry'
    ],
    [
        'title' => 'Civil Works',
        'icon' => 'fa-trowel-bricks',
        'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Site development, heavy earthmoving, micro-piling, drainage networks, asphalt paving, and retaining wall structures.',
        'badge' => 'Infrastructure'
    ],
    [
        'title' => 'Structural Works',
        'icon' => 'fa-cubes',
        'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?auto=format&fit=crop&w=800&q=80',
        'desc' => 'RCC frame superstructures, pre-stressed concrete beams, composite steel decking, shear walling, and load-bearing framing.',
        'badge' => 'RCC Superstructures'
    ],
    [
        'title' => 'Foundation & Earthwork',
        'icon' => 'fa-layer-group',
        'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Geotechnical soil load testing, deep raft foundations, diaphragm walling, foundation piling, and subterranean excavation.',
        'badge' => 'Deep Piling'
    ],
    [
        'title' => 'Renovation & Restoration',
        'icon' => 'fa-tools',
        'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Structural retrofitting, column jacketing, carbon fiber wrapping, architectural modernization, and structural expansion.',
        'badge' => 'Retrofitting'
    ],
    [
        'title' => 'Project Management',
        'icon' => 'fa-clipboard-check',
        'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?auto=format&fit=crop&w=800&q=80',
        'desc' => 'End-to-end site engineering, daily cloud progress reporting, quality audits, material price locking, and timeline scheduling.',
        'badge' => 'EPC Management'
    ]
];

// Fetch Featured Construction Projects
$stmtProj = $db->prepare("SELECT * FROM projects WHERE category_id = 1 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtProj->execute();
$projects = $stmtProj->fetchAll();

// Fetch Construction Gallery
$stmtGal = $db->prepare("SELECT * FROM gallery WHERE category_id = 1 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtGal->execute();
$galleryImages = $stmtGal->fetchAll();

// Fetch Construction Testimonials
$stmtTest = $db->prepare("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 3");
$stmtTest->execute();
$testimonials = $stmtTest->fetchAll();

$customPageTitle = "Construction Services – Raman Group";
$customMetaDesc = "Raman Group delivers turnkey residential villa construction, commercial towers, civil works, and structural retrofitting with 10-year warranties.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. FULL-WIDTH CINEMATIC CONSTRUCTION HERO SECTION -->
<section class="construction-cinematic-hero">
    <!-- Visual Background Engine: Construction Video + 8-Slide Construction Ken Burns Slideshow -->
    <div class="construction-bg-wrapper">
        <!-- Construction-Specific Background Video (Local) -->
        <video class="construction-video-bg" autoplay muted loop playsinline preload="metadata" onerror="this.style.display='none';">
            <source src="assets/videos/construction-services-hero.mp4" type="video/mp4">
        </video>

        <!-- 8-Slide Active Construction Ken Burns Visual Documentary Engine -->
        <div class="construction-slideshow">
            <div class="construction-slide c-slide-1" style="background-image: url('assets/images/services/turnkey-construction.jpg');" title="Active Construction Site & Turnkey Execution"></div>
            <div class="construction-slide c-slide-2" style="background-image: url('assets/images/services/civil-works.jpg');" title="Civil Foundation, Piling & Heavy Earthmoving"></div>
            <div class="construction-slide c-slide-3" style="background-image: url('assets/images/services/structural-works.jpg');" title="Structural Steel Decking & Superstructure Framing"></div>
            <div class="construction-slide c-slide-4" style="background-image: url('assets/images/projects/construction-1.jpg');" title="Site Engineers with Safety Helmets Inspecting Work"></div>
            <div class="construction-slide c-slide-5" style="background-image: url('assets/images/projects/construction-2.jpg');" title="Workers Pouring Concrete & Installing Steel Rebar"></div>
            <div class="construction-slide c-slide-6" style="background-image: url('assets/images/projects/construction-5.jpg');" title="Civil Engineers Reviewing Architectural Blueprints On-Site"></div>
            <div class="construction-slide c-slide-7" style="background-image: url('assets/images/projects/construction-6.jpg');" title="Multi-Story Building Taking Shape Under Active Construction"></div>
            <div class="construction-slide c-slide-8" style="background-image: url('assets/images/projects/construction-7.jpg');" title="Completed Architectural Building Handover"></div>
        </div>
    </div>
    
    <!-- Multi-Layered Cinematic Dark Navy & Warm Gold Overlay -->
    <div class="construction-overlay"></div>

    <div class="container construction-hero-content">
        <div class="row align-items-center min-vh-75 g-4 py-4">
            <!-- Left Side: Content & Buttons -->
            <div class="col-lg-8 col-xl-7 text-start">
                <span class="hero-eyebrow animate-fade-in">
                    <i class="fas fa-hard-hat me-2 text-warning"></i> CONSTRUCTION SERVICES
                </span>
                <h1 class="hero-title-cinematic animate-fade-in delay-1">
                    <strong>BUILDING</strong> STRONGER.<br>
                    <strong>BUILDING</strong> SMARTER.
                </h1>
                <p class="hero-subtext-cinematic animate-fade-in delay-2">
                    Professional construction solutions from planning and foundation to final handover. Raman Group brings 18+ years of civil engineering excellence, IS-spec material standards, and 10-year structural warranties.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-fade-in delay-3">
                    <a href="quote.php?service=Construction" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-4 shadow-lg"><i class="fas fa-file-signature me-2"></i> GET A CONSTRUCTION QUOTE</a>
                    <a href="projects.php?category=1" class="btn btn-outline-gold btn-lg font-heading fw-bold py-3 px-4"><i class="fas fa-building me-2"></i> VIEW CONSTRUCTION PROJECTS</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. ABOUT CONSTRUCTION -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=800&q=80" alt="Construction Excellence" class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 end-0 bg-dark text-white p-4 rounded-4 m-3 border border-warning shadow-lg d-none d-sm-block">
                        <div class="h2 font-heading text-warning mb-0">18+ Years</div>
                        <div class="small text-uppercase fw-bold text-light">Civil Engineering Excellence</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-subtitle">MASTERING CIVIL & STRUCTURAL ENGINEERING</span>
                <h2 class="section-title mb-4">Construction Excellence From Ground to Completion</h2>
                <p class="text-muted mb-4">
                    Founded with a commitment to zero-compromise structural safety, <strong>Raman Group’s Construction Division</strong> manages turnkey civil infrastructure, residential villas, commercial complexes, and structural engineering across North India.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-shield-alt text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">10-Year Warranty</h6>
                                <small class="text-muted">On all RCC frames</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-award text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Seismic Zone IV</h6>
                                <small class="text-muted">Earthquake resistant</small>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="quote.php?service=Construction" class="btn btn-gold font-heading fw-bold px-4 py-3"><i class="fas fa-comments me-2"></i> Discuss Your Project</a>
            </div>
        </div>
    </div>
</section>

<!-- 3. OUR CONSTRUCTION SERVICES -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">OUR CAPABILITIES</span>
            <h2 class="section-title">Complete Construction Solutions</h2>
            <p class="text-muted max-w-600 mx-auto">Raman Group provides complete end-to-end civil construction solutions engineered for long-term safety, durability, and aesthetics.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($constructionServices as $serv): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden service-card-wrap">
                        <div class="service-card-img-box">
                            <img src="<?= getImageUrl($serv['image'], $serv['title'], 'Construction') ?>" alt="<?= e($serv['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="service-card-category-badge">CONSTRUCTION</span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas <?= e($serv['icon']) ?> text-warning fs-4"></i>
                                <h5 class="font-heading fw-bold mb-0 text-dark"><?= e($serv['title']) ?></h5>
                            </div>
                            <p class="small text-muted flex-grow-1 mb-3"><?= e($serv['desc']) ?></p>
                            <a href="quote.php?service=<?= urlencode($serv['title']) ?>" class="btn btn-gold btn-sm w-100"><i class="fas fa-calculator me-1"></i> Request Quote</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 4. CONSTRUCTION PROCESS -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">HOW WE BUILD</span>
            <h2 class="section-title">Our Construction Process</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">01</div>
                    <h5 class="fw-bold font-heading text-dark">Consultation</h5>
                    <p class="small text-muted mb-0">Initial project scope alignment, spatial requirements gathering, site zoning analysis, and preliminary budget modeling.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">02</div>
                    <h5 class="fw-bold font-heading text-dark">Site Assessment</h5>
                    <p class="small text-muted mb-0">Geotechnical soil testing, load bearing calculations, contour mapping, land surveying, and municipal clearance audits.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">03</div>
                    <h5 class="fw-bold font-heading text-dark">Planning & Estimation</h5>
                    <p class="small text-muted mb-0">Architectural blueprint drafting, structural ETABS engineering, material rate locking, and itemized transparent BOQ.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">04</div>
                    <h5 class="fw-bold font-heading text-dark">Execution</h5>
                    <p class="small text-muted mb-0">Ground excavation, foundation piling, RCC superstructure casting, brickwork masonry, and MEP utility layout.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">05</div>
                    <h5 class="fw-bold font-heading text-dark">Quality Inspection</h5>
                    <p class="small text-muted mb-0">Ultrasonic radiograph beam audits, concrete cube compression testing, non-destructive checks, and waterproofing audits.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">06</div>
                    <h5 class="fw-bold font-heading text-dark">Handover</h5>
                    <p class="small text-muted mb-0">Final architectural finishing, site deep cleaning, occupancy certificate clearance, and 10-year structural warranty delivery.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. WHY CHOOSE RAMAN GROUP -->
<section class="section-padding bg-dark text-white">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle text-warning">THE RAMAN ADVANTAGE</span>
            <h2 class="section-title text-white">Why Choose Raman Group</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-user-tie"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Experienced Professionals</h5>
                    <p class="small text-secondary mb-0">Led by principal civil engineers with 18+ years of high-rise and villa construction experience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-boxes-stacked"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Quality Materials</h5>
                    <p class="small text-secondary mb-0">We use IS-certified M25/M30 grade concrete and high-yield FE550D TMT steel bars exclusively.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-hard-hat"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Skilled Execution</h5>
                    <p class="small text-secondary mb-0">Dedicated resident civil engineers supervise every pour, shuttering placement, and structural joint.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-shield-halved"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Safety First</h5>
                    <p class="small text-secondary mb-0">Zero-accident safety policy on site with mandatory PPE, scaffold audits, and safety nets.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-file-contract"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Transparent Process</h5>
                    <p class="small text-secondary mb-0">Fixed rate-card pricing lock protecting you against raw material cost escalations during build.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-clock"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">On-Time Delivery</h5>
                    <p class="small text-secondary mb-0">Strict milestone timelines backed by contractually guaranteed delivery timelines.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. FEATURED CONSTRUCTION PROJECTS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="section-subtitle">CIVIL PORTFOLIO</span>
                <h2 class="section-title mb-0">Featured Construction Projects</h2>
            </div>
            <a href="projects.php?category=1" class="btn btn-outline-gold btn-sm font-heading fw-bold">View All Construction Projects <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($projects as $proj): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-thumb">
                            <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], 'Construction') ?>" alt="<?= e($proj['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="project-category-badge">Construction</span>
                        </div>
                        <div class="project-body">
                            <h4 class="project-title"><?= e($proj['title']) ?></h4>
                            <div class="project-meta text-muted mb-2"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($proj['location']) ?> &bull; <span class="badge bg-success bg-opacity-25 text-success ms-1"><?= e($proj['status'] ?? 'Completed') ?></span></div>
                            <p class="small text-muted mb-3"><?= e(mb_strimwidth($proj['short_description'], 0, 110, '...')) ?></p>
                            <a href="project-detail.php?id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-gold w-100">View Detailed Showcase <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 7. CONSTRUCTION GALLERY -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">VISUAL PROOF</span>
            <h2 class="section-title">Construction Site Gallery</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($galleryImages as $img): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="gallery-item shadow-sm lightbox-trigger" data-image="<?= getImageUrl($img['image_path'], $img['title'], 'Construction') ?>" data-title="<?= e($img['title']) ?> - <?= e($img['location']) ?>" style="cursor: pointer;">
                        <img src="<?= getImageUrl($img['image_path'], $img['title'], 'Construction') ?>" alt="<?= e($img['title']) ?>" class="w-100 h-100 object-fit-cover">
                        <div class="gallery-overlay">
                            <h5 class="text-white font-heading fw-bold mb-1"><?= e($img['title']) ?></h5>
                            <small class="text-warning"><i class="fas fa-map-marker-alt me-1"></i> <?= e($img['location']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. STATISTICS -->
<section class="section-padding bg-dark-surface stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">450+</div>
                    <div class="stat-label">Projects Completed</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">18+</div>
                    <div class="stat-label">Years of Experience</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">85+</div>
                    <div class="stat-label">Professional Team</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">99.8%</div>
                    <div class="stat-label">Client Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. TESTIMONIALS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">CLIENT VERDICT</span>
            <h2 class="section-title">What Our Construction Clients Say</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm p-4">
                        <div class="mb-3"><?= renderRatingStars($t['rating']) ?></div>
                        <p class="small text-muted fst-italic mb-4">"<?= e($t['content']) ?>"</p>
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <div class="bg-navy text-warning p-2 rounded-circle fw-bold font-heading"><?= strtoupper(substr($t['client_name'], 0, 2)) ?></div>
                            <div>
                                <h6 class="font-heading fw-bold mb-0 text-dark"><?= e($t['client_name']) ?></h6>
                                <small class="text-muted"><?= e($t['client_title']) ?> - <?= e($t['company']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 10. FAQ -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">FREQUENTLY ASKED QUESTIONS</span>
            <h2 class="section-title">Construction FAQs</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion custom-accordion" id="constructionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqC1" aria-expanded="true" aria-controls="faqC1">
                                How does the construction process work?
                            </button>
                        </h2>
                        <div id="faqC1" class="accordion-collapse collapse show" aria-labelledby="faqH1" data-bs-parent="#constructionFAQ">
                            <div class="accordion-body text-muted">
                                We follow a structured 6-step workflow starting with architectural consultation and soil audit, progressing to ETABS structural calculation, itemized BOQ estimation, RCC frame execution, ultrasonic quality audits, and turnkey handover with occupancy certification.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqC2" aria-expanded="false" aria-controls="faqC2">
                                How is a construction project estimated?
                            </button>
                        </h2>
                        <div id="faqC2" class="accordion-collapse collapse" aria-labelledby="faqH2" data-bs-parent="#constructionFAQ">
                            <div class="accordion-body text-muted">
                                Estimates are generated based on structural blueprints, soil load test data, total square footage, and chosen specification grade (Premium Villa, Commercial Grade, Heavy Industrial). We provide transparent, itemized Bill of Quantities (BOQ) with fixed rate locks.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqC3" aria-expanded="false" aria-controls="faqC3">
                                What types of construction do you handle?
                            </button>
                        </h2>
                        <div id="faqC3" class="accordion-collapse collapse" aria-labelledby="faqH3" data-bs-parent="#constructionFAQ">
                            <div class="accordion-body text-muted">
                                Raman Group handles residential luxury villas, multi-family apartment complexes, commercial corporate office towers, industrial factories, PEB warehouse sheds, and civil infrastructure earthworks.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqC4" aria-expanded="false" aria-controls="faqC4">
                                How long does a typical project take?
                            </button>
                        </h2>
                        <div id="faqC4" class="accordion-collapse collapse" aria-labelledby="faqH4" data-bs-parent="#constructionFAQ">
                            <div class="accordion-body text-muted">
                                A standard 4,000 to 6,000 sq.ft luxury villa takes 10 to 14 months from excavation to turnkey finishing. Commercial towers and industrial sheds are delivered on custom milestone timelines.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqH5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqC5" aria-expanded="false" aria-controls="faqC5">
                                Do you provide end-to-end construction services?
                            </button>
                        </h2>
                        <div id="faqC5" class="accordion-collapse collapse" aria-labelledby="faqH5" data-bs-parent="#constructionFAQ">
                            <div class="accordion-body text-muted">
                                Yes. We manage full EPC turnkey contracts covering soil testing, municipal approvals, architectural engineering, structural build, MEP utility wiring, plumbing, and interior handover.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. FINAL CTA -->
<section class="section-padding bg-dark text-white position-relative">
    <div class="container text-center">
        <div class="cta-banner-box">
            <h2 class="display-5 font-heading text-white fw-bold mb-3">Ready to Build Your Next Project?</h2>
            <p class="lead text-light max-w-700 mx-auto mb-4">Request a free itemized BOQ rate card or speak with our principal civil structural engineer today.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="quote.php?service=Construction" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-5 shadow-lg"><i class="fas fa-calculator me-2"></i> Request a Construction Quote</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg font-heading fw-bold py-3 px-5"><i class="fas fa-envelope me-2"></i> Contact Raman Group</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
