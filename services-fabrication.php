<?php
/**
 * Dedicated Fabrication Landing Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch Fabrication Category & Services (category_id = 3)
$stmtCat = $db->prepare("SELECT * FROM service_categories WHERE id = 3 LIMIT 1");
$stmtCat->execute();
$category = $stmtCat->fetch();

// 9 Core Fabrication Services list
$fabricationServices = [
    [
        'title' => 'Structural Steel Fabrication',
        'icon' => 'fa-industry',
        'image' => 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Heavy industrial steel framing, PEB warehouse sheds, crane gantry beams, and long-span steel trusses.',
        'badge' => 'PEB Steel Sheds'
    ],
    [
        'title' => 'Metal Fabrication',
        'icon' => 'fa-hammer',
        'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Mild steel industrial platforms, mezzanine floors, machinery chassis, fire escape towers, and heavy structural plates.',
        'badge' => 'Heavy MS Plates'
    ],
    [
        'title' => 'Gates & Grills',
        'icon' => 'fa-door-closed',
        'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Automated Italian motor sliding gates, 8mm CNC laser-cut privacy gates, security window grills, and composite entry doors.',
        'badge' => 'Automated Motors'
    ],
    [
        'title' => 'Railings',
        'icon' => 'fa-bars-staggered',
        'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Frameless 15mm toughened glass mounted on SS 316 spigots, PVD gold balustrades, and brass handrail channels.',
        'badge' => 'SS 316 Glass & PVD'
    ],
    [
        'title' => 'Staircases',
        'icon' => 'fa-stairs',
        'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Floating mono-stringer steel staircases with cantilevered teak treads, helical spiral stairs, and external fire escapes.',
        'badge' => 'Mono-Stringer Steel'
    ],
    [
        'title' => 'Industrial Fabrication',
        'icon' => 'fa-warehouse',
        'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Factory catwalks, pipe racks, petrol pump canopies, stadium seating trusses, and space-frame glass skylights.',
        'badge' => 'Space Frames'
    ],
    [
        'title' => 'Custom Metal Works',
        'icon' => 'fa-gem',
        'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Bespoke metal art sculptures, Corten steel patinated facades, laser-cut brass room dividers, and metal pergolas.',
        'badge' => 'Bespoke Facades'
    ],
    [
        'title' => 'Welding & Assembly',
        'icon' => 'fa-fire-burner',
        'image' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80',
        'desc' => 'AWS D1.1 certified MIG/TIG seam welding, argon purge TIG welding, and ultrasonic radiograph weld testing.',
        'badge' => 'AWS D1.1 Certified'
    ],
    [
        'title' => 'Installation',
        'icon' => 'fa-truck-pickup',
        'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80',
        'desc' => 'High-capacity mobile crane hoisting, high-tensile grade 8.8 bolt erection, and anchor bolt torque setting.',
        'badge' => 'Site Crane Erection'
    ]
];

// Fetch Featured Fabrication Projects
$stmtProj = $db->prepare("SELECT * FROM projects WHERE category_id = 3 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtProj->execute();
$projects = $stmtProj->fetchAll();

// Fetch Fabrication Gallery
$stmtGal = $db->prepare("SELECT * FROM gallery WHERE category_id = 3 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtGal->execute();
$galleryImages = $stmtGal->fetchAll();

// Fetch Fabrication Testimonials
$stmtTest = $db->prepare("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 3");
$stmtTest->execute();
$testimonials = $stmtTest->fetchAll();

$customPageTitle = "Fabrication Services – Raman Group";
$customMetaDesc = "Raman Group specializes in heavy PEB structural steel fabrication, automated laser gates, mirror SS 316 glass railings, and custom metalwork.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. FULL-WIDTH CINEMATIC FABRICATION HERO SECTION -->
<section class="fabrication-cinematic-hero">
    <!-- Visual Background Engine: Fabrication Video + 8-Slide Fabrication Ken Burns Visual Slideshow -->
    <div class="fabrication-bg-wrapper">
        <!-- Fabrication-Specific Background Video (Local) -->
        <video class="fabrication-video-bg" autoplay muted loop playsinline preload="metadata" onerror="this.style.display='none';">
            <source src="assets/videos/fabrication-services-hero.mp4" type="video/mp4">
        </video>

        <!-- 8-Slide Fabrication Ken Burns Visual Documentary Engine -->
        <div class="fabrication-slideshow">
            <div class="fabrication-slide f-slide-1" style="background-image: url('assets/images/services/steel-fabrication.jpg');" title="Heavy PEB Structural Steel Framing & Industrial Trusses"></div>
            <div class="fabrication-slide f-slide-2" style="background-image: url('assets/images/services/ms-fabrication.jpg');" title="Precision Mild Steel Fabrication & Heavy MS Plate Erection"></div>
            <div class="fabrication-slide f-slide-3" style="background-image: url('assets/images/services/ss-fabrication.jpg');" title="Mirror SS 316 Stainless Steel & Architectural PVD Gold Work"></div>
            <div class="fabrication-slide f-slide-4" style="background-image: url('assets/images/services/structural-fabrication.jpg');" title="AWS D1.1 Certified Seam Welding & Crane Gantry Beams"></div>
            <div class="fabrication-slide f-slide-5" style="background-image: url('assets/images/services/custom-metal-works.jpg');" title="Bespoke Metal Facades & CNC Laser Cut Engineering"></div>
            <div class="fabrication-slide f-slide-6" style="background-image: url('assets/images/services/designer-gates.jpg');" title="Automated Italian Motor Sliding Gates & CNC Laser Privacy Panels"></div>
            <div class="fabrication-slide f-slide-7" style="background-image: url('assets/images/services/staircase-structures.jpg');" title="Floating Mono-Stringer Steel Staircases & Helical Metal Architecture"></div>
            <div class="fabrication-slide f-slide-8" style="background-image: url('assets/images/services/architectural-railings.jpg');" title="Frameless Toughened Glass & SS 316 Architectural Handrails"></div>
        </div>
    </div>
    
    <!-- Multi-Layered Cinematic Dark Navy & Warm Gold Overlay -->
    <div class="fabrication-overlay"></div>

    <div class="container fabrication-hero-content">
        <div class="row align-items-center min-vh-75 g-4 py-4">
            <!-- Left Side: Content & Buttons -->
            <div class="col-lg-7 col-xl-7 text-start">
                <span class="hero-eyebrow animate-fade-in">
                    <i class="fas fa-industry me-2 text-warning"></i> FABRICATION SERVICES
                </span>
                <h1 class="hero-title-cinematic animate-fade-in delay-1">
                    <strong>PRECISION METALWORK.</strong><br>
                    BUILT TO <strong>PERFORM.</strong>
                </h1>
                <p class="hero-subtext-cinematic animate-fade-in delay-2">
                    Heavy PEB structural steel engineering, automated laser driveway gates, mirror SS 316 glass railings, and bespoke metal facades built under AWS D1.1 certified welding standards.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-fade-in delay-3">
                    <a href="quote.php?service=Fabrication" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-4 shadow-lg"><i class="fas fa-file-signature me-2"></i> GET A FABRICATION QUOTE</a>
                    <a href="projects.php?category=3" class="btn btn-outline-gold btn-lg font-heading fw-bold py-3 px-4"><i class="fas fa-industry me-2"></i> VIEW FABRICATION PROJECTS</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. ABOUT FABRICATION -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80" alt="Fabrication Excellence" class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 end-0 bg-dark text-white p-4 rounded-4 m-3 border border-warning shadow-lg d-none d-sm-block">
                        <div class="h2 font-heading text-warning mb-0">0.1 mm</div>
                        <div class="small text-uppercase fw-bold text-light">CNC Laser Cutting Accuracy</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-subtitle">HEAVY STRUCTURAL & ARCHITECTURAL METALWORK</span>
                <h2 class="section-title mb-4">Heavy Structural Steel & Precision Metalwork</h2>
                <p class="text-muted mb-4">
                    <strong>Raman Group’s Fabrication Division</strong> operates a heavy engineering metal workshop equipped with CNC fiber laser cutters, 300-ton NC press brakes, submerged arc welding machines, and hot-dip galvanizing baths. We fabricate 30-meter clear-span PEB trusses and ultra-luxury PVD gold stainless steel railings.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-fire-burner text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">AWS D1.1 Certified</h6>
                                <small class="text-muted">Radiograph weld tested</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-shield-halved text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Hot-Dip Galvanized</h6>
                                <small class="text-muted">20-Year anti-rust guarantee</small>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="quote.php?service=Fabrication" class="btn btn-gold font-heading fw-bold px-4 py-3"><i class="fas fa-comments me-2"></i> Discuss Fabrication Project</a>
            </div>
        </div>
    </div>
</section>

<!-- 3. OUR FABRICATION SERVICES GRID -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">FABRICATION CAPABILITIES</span>
            <h2 class="section-title">Structural & Decorative Metalwork Services</h2>
            <p class="text-muted max-w-600 mx-auto">Explore our heavy steel, SS 316 marine fabrication, safety grills, and automated entry gates.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($fabricationServices as $serv): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden service-card-wrap">
                        <div class="service-card-img-box">
                            <img src="<?= getImageUrl($serv['image'], $serv['title'], 'Fabrication') ?>" alt="<?= e($serv['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="service-card-category-badge">FABRICATION</span>
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

<!-- 4. FABRICATION PROCESS -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">WORKSHOP EXECUTION FLOW</span>
            <h2 class="section-title">Our Fabrication Process</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">01</div>
                    <h5 class="fw-bold font-heading text-dark">CAD & Tekla Detailing</h5>
                    <p class="small text-muted mb-0">3D structural steel modeling, node calculation, joint stress verification, and shop drawing extraction.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">02</div>
                    <h5 class="fw-bold font-heading text-dark">CNC Laser Cutting</h5>
                    <p class="small text-muted mb-0">Cutting steel plates up to 25mm thickness with 0.1mm accuracy using high-power fiber laser machinery.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">03</div>
                    <h5 class="fw-bold font-heading text-dark">AWS Certified Welding</h5>
                    <p class="small text-muted mb-0">MIG/TIG welding performed under AWS D1.1 protocols with continuous seam grinding and joint radiograph checks.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">04</div>
                    <h5 class="fw-bold font-heading text-dark">Surface Galvanizing</h5>
                    <p class="small text-muted mb-0">Immersion in molten zinc bath for complete anti-corrosion protection against weather and humidity.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">05</div>
                    <h5 class="fw-bold font-heading text-dark">Quality Inspection</h5>
                    <p class="small text-muted mb-0">Ultrasonic radiograph seam checks, coat thickness measurement, PVD gold polishing, and pre-assembly testing.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">06</div>
                    <h5 class="fw-bold font-heading text-dark">Site Crane Erection</h5>
                    <p class="small text-muted mb-0">Transport, mobile crane hoisting, high-tensile bolt tightening, automated motor commissioning, and handover.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. QUALITY CONTROL & COMPLIANCE -->
<section class="section-padding bg-dark text-white border-top border-warning border-opacity-25">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-subtitle text-warning">ZERO-DEFECT GUARANTEE</span>
                <h2 class="section-title text-white mb-4">Rigorous Quality Control & Inspection Protocols</h2>
                <p class="text-secondary mb-4">
                    Every structural component fabricated at Raman Group undergoes multi-stage non-destructive testing (NDT) to ensure structural integrity and compliance with AWS D1.1 and IS specifications.
                </p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-dark-card rounded-3 border border-secondary border-opacity-25">
                            <i class="fas fa-microscope text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-white mb-1">Ultrasonic Radiograph</h6>
                            <p class="small text-secondary mb-0">100% seam weld flaw detection</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-dark-card rounded-3 border border-secondary border-opacity-25">
                            <i class="fas fa-shield-alt text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-white mb-1">IS 2629 Galvanizing</h6>
                            <p class="small text-secondary mb-0">Hot-dip zinc coating thickness check</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-dark-card rounded-3 border border-secondary border-opacity-25">
                            <i class="fas fa-weight-hanging text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-white mb-1">Deflection Load Audit</h6>
                            <p class="small text-secondary mb-0">Live structural strain monitoring</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-dark-card rounded-3 border border-secondary border-opacity-25">
                            <i class="fas fa-certificate text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-white mb-1">AWS D1.1 Certified</h6>
                            <p class="small text-secondary mb-0">Qualified structural welders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5 bg-dark-card rounded-4 border border-warning border-opacity-25 text-center">
                    <i class="fas fa-award text-warning display-1 mb-3"></i>
                    <h3 class="font-heading text-white fw-bold mb-2">ISO 9001:2015 & AWS D1.1 Certified</h3>
                    <p class="text-secondary small mb-4">Our fabrication facility meets international mill test standards with full trace certificates provided for steel origin, tensile strength, and galvanizing thickness.</p>
                    <a href="quote.php?service=Fabrication" class="btn btn-gold font-heading fw-bold px-4 py-2"><i class="fas fa-file-contract me-2"></i> Request Quality Inspection Sample</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. WHY CHOOSE RAMAN GROUP -->
<section class="section-padding bg-dark-surface text-white">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle text-warning">THE FABRICATION ADVANTAGE</span>
            <h2 class="section-title text-white">Why Choose Raman Group</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-fire-burner"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">AWS Certified Welders</h5>
                    <p class="small text-secondary mb-0">Structural steel MIG/TIG seam welding inspected under AWS D1.1 standards.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-shield-halved"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">20-Year Anti-Rust Protection</h5>
                    <p class="small text-secondary mb-0">Hot-dip galvanizing protects exterior structural steel against extreme weather corrosion.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-microchip"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">0.1mm CNC Laser Precision</h5>
                    <p class="small text-secondary mb-0">Razor-sharp geometry cutting for security grills, privacy gates, and architectural art.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-truck-pickup"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Heavy Crane Site Erection</h5>
                    <p class="small text-secondary mb-0">In-house mobile crane fleet and certified riggers for rapid structural steel assembly.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-clipboard-check"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">ISO 9001 Quality Control</h5>
                    <p class="small text-secondary mb-0">Multi-stage NDT ultrasonic and radiograph inspections for every load-bearing joint.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-clock"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">On-Time Delivery</h5>
                    <p class="small text-secondary mb-0">Rapid shop drawing turnaround and guaranteed delivery schedules.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. FEATURED FABRICATION PROJECTS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="section-subtitle">METALWORK PORTFOLIO</span>
                <h2 class="section-title mb-0">Featured Fabrication Projects</h2>
            </div>
            <a href="projects.php?category=3" class="btn btn-outline-gold btn-sm font-heading fw-bold">View All Fabrication Projects <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($projects as $proj): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-thumb">
                            <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], 'Fabrication') ?>" alt="<?= e($proj['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="project-category-badge">Fabrication</span>
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

<!-- 8. FABRICATION GALLERY -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">WORKSHOP SHOWCASE</span>
            <h2 class="section-title">Fabrication Photo Gallery</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($galleryImages as $img): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="gallery-item shadow-sm lightbox-trigger" data-image="<?= getImageUrl($img['image_path'], $img['title'], 'Fabrication') ?>" data-title="<?= e($img['title']) ?> - <?= e($img['location']) ?>" style="cursor: pointer;">
                        <img src="<?= getImageUrl($img['image_path'], $img['title'], 'Fabrication') ?>" alt="<?= e($img['title']) ?>" class="w-100 h-100 object-fit-cover">
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

<!-- 9. STATISTICS -->
<section class="section-padding bg-dark-surface stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Tons Fabricated Annually</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">20 Years</div>
                    <div class="stat-label">Anti-Rust Warranty</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">0.1 mm</div>
                    <div class="stat-label">Laser Cutting Precision</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Radiograph Inspected</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. TESTIMONIALS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">CLIENT FEEDBACK</span>
            <h2 class="section-title">What Our Fabrication Clients Say</h2>
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

<!-- 11. FAQ -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">FREQUENTLY ASKED QUESTIONS</span>
            <h2 class="section-title">Fabrication FAQs</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion custom-accordion" id="fabricationFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFabH1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqFabC1" aria-expanded="true" aria-controls="faqFabC1">
                                What types of metal fabrication do you handle?
                            </button>
                        </h2>
                        <div id="faqFabC1" class="accordion-collapse collapse show" aria-labelledby="faqFabH1" data-bs-parent="#fabricationFAQ">
                            <div class="accordion-body text-muted">
                                We handle PEB structural steel framing, MS industrial platforms, SS 316 marine-grade railings, automated sliding gates, laser-cut window security screens, and custom metal art facades.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFabH2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFabC2" aria-expanded="false" aria-controls="faqFabC2">
                                What is the difference between SS 304 and Marine Grade SS 316?
                            </button>
                        </h2>
                        <div id="faqFabC2" class="accordion-collapse collapse" aria-labelledby="faqFabH2" data-bs-parent="#fabricationFAQ">
                            <div class="accordion-body text-muted">
                                Grade 304 is ideal for indoor balustrades, while Marine Grade SS 316 contains molybdenum for maximum anti-rust protection in exterior, high-moisture, and outdoor atrium environments.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFabH3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFabC3" aria-expanded="false" aria-controls="faqFabC3">
                                Are your automated driveway gates compatible with smart home systems?
                            </button>
                        </h2>
                        <div id="faqFabC3" class="accordion-collapse collapse" aria-labelledby="faqFabH3" data-bs-parent="#fabricationFAQ">
                            <div class="accordion-body text-muted">
                                Yes. Our sliding and swing gates integrate Italian automated motors (Beninca / BFT) with smartphone Wi-Fi controls, remote keyfobs, and biometric access integration.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFabH4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFabC4" aria-expanded="false" aria-controls="faqFabC4">
                                How fast can a PEB warehouse steel shed be erected?
                            </button>
                        </h2>
                        <div id="faqFabC4" class="accordion-collapse collapse" aria-labelledby="faqFabH4" data-bs-parent="#fabricationFAQ">
                            <div class="accordion-body text-muted">
                                Once shop drawings are approved and foundation pads are cast, steel fabrication and mobile crane site erection take approximately 35 to 45 days.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFabH5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFabC5" aria-expanded="false" aria-controls="faqFabC5">
                                Do you provide AWS welding certifications and NDT inspection reports?
                            </button>
                        </h2>
                        <div id="faqFabC5" class="accordion-collapse collapse" aria-labelledby="faqFabH5" data-bs-parent="#fabricationFAQ">
                            <div class="accordion-body text-muted">
                                Yes. All heavy structural steel welds come with AWS D1.1 welder qualification certificates, ultrasonic seam radiograph reports, and galvanizing thickness audit papers.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 12. FINAL CTA -->
<section class="section-padding bg-dark text-white position-relative">
    <div class="container text-center">
        <div class="cta-banner-box">
            <h2 class="display-5 font-heading text-white fw-bold mb-3">Ready for Precision Metal Fabrication?</h2>
            <p class="lead text-light max-w-700 mx-auto mb-4">Upload your Tekla / CAD drawings or request a structural fabrication estimate.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="quote.php?service=Fabrication" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-5 shadow-lg"><i class="fas fa-calculator me-2"></i> Request Fabrication Quote</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg font-heading fw-bold py-3 px-5"><i class="fas fa-envelope me-2"></i> Contact Raman Group</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
