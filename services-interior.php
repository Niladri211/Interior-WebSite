<?php
/**
 * Dedicated Interior Design Landing Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();

// Fetch Interior Category & Services (category_id = 2)
$stmtCat = $db->prepare("SELECT * FROM service_categories WHERE id = 2 LIMIT 1");
$stmtCat->execute();
$category = $stmtCat->fetch();

// 11 Core Interior Services list
$interiorServices = [
    [
        'title' => 'Residential Interiors',
        'icon' => 'fa-couch',
        'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Bespoke interior architecture for luxury villas, penthouses, and apartments with custom joinery and Italian marble styling.',
        'badge' => 'Luxury Homes'
    ],
    [
        'title' => 'Commercial Interiors',
        'icon' => 'fa-store',
        'image' => 'https://images.unsplash.com/photo-1567449303078-57ad995bd301?auto=format&fit=crop&w=800&q=80',
        'desc' => 'High-footfall retail boutiques, jewellery showrooms, hospitality lounges, and diagnostic clinic fit-outs.',
        'badge' => 'Retail & Clinics'
    ],
    [
        'title' => 'Office Interiors',
        'icon' => 'fa-briefcase',
        'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Agile tech corporate workspaces, acoustic conference cabins, glass partitions, and ergonomic workstation clusters.',
        'badge' => 'Corporate Setup'
    ],
    [
        'title' => 'Modular Kitchens',
        'icon' => 'fa-utensils',
        'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Marine-grade 710 BWP plywood kitchens equipped with Blum soft-close tandem drawers and quartz waterfall islands.',
        'badge' => '710 BWP Waterproof'
    ],
    [
        'title' => 'Living Room Design',
        'icon' => 'fa-tv',
        'image' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Back-lit translucent onyx marble bars, floating CNC louver media consoles, magnetic track lighting, and Italian sofas.',
        'badge' => 'Statement Lounges'
    ],
    [
        'title' => 'Bedroom Design',
        'icon' => 'fa-bed',
        'image' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Floor-to-ceiling tinted glass walk-in closets, diamond-tufted velvet headboard walls, and ambient sensor lighting.',
        'badge' => 'Master Suites'
    ],
    [
        'title' => 'False Ceiling',
        'icon' => 'fa-layer-group',
        'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Saint-Gobain gypsum ceilings, teak wood acoustic baffles, continuous COB LED profile channels, and magnetic tracks.',
        'badge' => 'Architectural Ceiling'
    ],
    [
        'title' => 'Lighting Design',
        'icon' => 'fa-lightbulb',
        'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80',
        'desc' => 'High-CRI (95+) architectural LED fixtures, DALI Zigbee dimming scene programming, chandeliers, and wall art washers.',
        'badge' => 'High-CRI Lighting'
    ],
    [
        'title' => 'Custom Furniture',
        'icon' => 'fa-boxes-stacked',
        'image' => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Factory-manufactured wardrobes, study desks, shoe consoles, and TV units produced on automated German CNC machinery.',
        'badge' => 'German CNC'
    ],
    [
        'title' => 'Space Planning',
        'icon' => 'fa-compass',
        'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Optimal spatial circulation layouts, ergonomic human factor engineering, and biophilic interior planning.',
        'badge' => 'Ergonomics'
    ],
    [
        'title' => '3D Design / Visualization',
        'icon' => 'fa-vr-cardboard',
        'image' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?auto=format&fit=crop&w=800&q=80',
        'desc' => 'Photorealistic 4K 3D renders and immersive Virtual Reality walkthroughs so you experience your home before construction.',
        'badge' => 'VR Walkthrough'
    ]
];

// Fetch Featured Interior Projects
$stmtProj = $db->prepare("SELECT * FROM projects WHERE category_id = 2 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtProj->execute();
$projects = $stmtProj->fetchAll();

// Fetch Interior Gallery
$stmtGal = $db->prepare("SELECT * FROM gallery WHERE category_id = 2 AND is_active = 1 ORDER BY id DESC LIMIT 6");
$stmtGal->execute();
$galleryImages = $stmtGal->fetchAll();

// Fetch Interior Testimonials
$stmtTest = $db->prepare("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id DESC LIMIT 3");
$stmtTest->execute();
$testimonials = $stmtTest->fetchAll();

$customPageTitle = "Interior Design Services – Raman Group";
$customMetaDesc = "Raman Group provides bespoke luxury home interior architecture, German modular kitchens, office fit-outs, and 3D VR walkthroughs.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- 1. FULL-WIDTH CINEMATIC INTERIOR DESIGN HERO SECTION -->
<section class="interior-cinematic-hero">
    <!-- Visual Background Engine: Interior Video + 8-Slide Interior Ken Burns Visual Slideshow -->
    <div class="interior-bg-wrapper">
        <!-- Interior-Specific Background Video (Local) -->
        <video class="interior-video-bg" autoplay muted loop playsinline preload="metadata" onerror="this.style.display='none';">
            <source src="assets/videos/interior-services-hero.mp4" type="video/mp4">
        </video>

        <!-- 8-Slide Interior-Only Ken Burns Visual Documentary Engine -->
        <div class="interior-slideshow">
            <div class="interior-slide i-slide-1" style="background-image: url('assets/images/services/living-room-interiors.jpg');" title="Luxury Living Room Lounge & Spatial Styling"></div>
            <div class="interior-slide i-slide-2" style="background-image: url('assets/images/services/kitchen-interiors.jpg');" title="Modern German Modular Kitchen & Quartz Island"></div>
            <div class="interior-slide i-slide-3" style="background-image: url('assets/images/services/bedroom-interiors.jpg');" title="Master Bedroom Suite & Tinted Glass Walk-In Closets"></div>
            <div class="interior-slide i-slide-4" style="background-image: url('assets/images/services/office-interiors.jpg');" title="Corporate Office Space & Acoustic Conference Architecture"></div>
            <div class="interior-slide i-slide-5" style="background-image: url('assets/images/services/modular-furniture.jpg');" title="Factory Crafted German CNC Custom Modular Furniture"></div>
            <div class="interior-slide i-slide-6" style="background-image: url('assets/images/services/false-ceiling.jpg');" title="Architectural Gypsum False Ceiling & COB LED Profile Channels"></div>
            <div class="interior-slide i-slide-7" style="background-image: url('assets/images/services/lighting-decor.jpg');" title="High-CRI Architectural Lighting & Ambient Chandelier Styling"></div>
            <div class="interior-slide i-slide-8" style="background-image: url('assets/images/services/home-interiors.jpg');" title="Turnkey Completed Luxury Home Interior Sanctuary"></div>
        </div>
    </div>
    
    <!-- Multi-Layered Cinematic Dark Navy & Warm Gold Overlay -->
    <div class="interior-overlay"></div>

    <div class="container interior-hero-content">
        <div class="row align-items-center min-vh-75 g-4 py-4">
            <!-- Left Side: Content & Buttons -->
            <div class="col-lg-7 col-xl-7 text-start">
                <span class="hero-eyebrow animate-fade-in">
                    <i class="fas fa-couch me-2 text-warning"></i> INTERIOR DESIGN
                </span>
                <h1 class="hero-title-cinematic animate-fade-in delay-1">
                    <strong>SPACES DESIGNED</strong><br>
                    AROUND YOUR <strong>LIFESTYLE.</strong>
                </h1>
                <p class="hero-subtext-cinematic animate-fade-in delay-2">
                    Bespoke spatial styling, photorealistic 3D VR walkthroughs, and factory-crafted modular joinery. We craft sanctuary-like luxury residences and high-performance corporate office environments.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-fade-in delay-3">
                    <a href="quote.php?service=Interior" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-4 shadow-lg"><i class="fas fa-file-signature me-2"></i> GET AN INTERIOR QUOTE</a>
                    <a href="projects.php?category=2" class="btn btn-outline-gold btn-lg font-heading fw-bold py-3 px-4"><i class="fas fa-couch me-2"></i> VIEW INTERIOR PROJECTS</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. ABOUT INTERIOR -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80" alt="Interior Architecture Excellence" class="img-fluid rounded-4 shadow-lg">
                    <div class="position-absolute bottom-0 end-0 bg-dark text-white p-4 rounded-4 m-3 border border-warning shadow-lg d-none d-sm-block">
                        <div class="h2 font-heading text-warning mb-0">30 Days</div>
                        <div class="small text-uppercase fw-bold text-light">Factory Modular Turnaround</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-subtitle">CREATIVE BRILLIANCE & FACTORY PRECISION</span>
                <h2 class="section-title mb-4">Bespoke Interior Architecture & Spatial Excellence</h2>
                <p class="text-muted mb-4">
                    At <strong>Raman Group Interior Studio</strong>, we replace traditional, dusty on-site carpentry with automated German CNC manufacturing. Every modular wardrobe, kitchen cabinet, and wall panel is engineered in our factory with 0.1mm precision and assembled on-site in days.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-vr-cardboard text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">3D VR Renders</h6>
                                <small class="text-muted">Experience designs before build</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-25 d-flex align-items-center gap-3">
                            <i class="fas fa-award text-warning fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">710 BWP Plywood</h6>
                                <small class="text-muted">100% Waterproof guarantee</small>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="quote.php?service=Interior" class="btn btn-gold font-heading fw-bold px-4 py-3"><i class="fas fa-comments me-2"></i> Discuss Interior Project</a>
            </div>
        </div>
    </div>
</section>

<!-- 3. OUR INTERIOR SERVICES GRID -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">OUR INTERIOR VERTICALS</span>
            <h2 class="section-title">Comprehensive Interior Solutions</h2>
            <p class="text-muted max-w-600 mx-auto">Raman Group provides end-to-end luxury residential, corporate workplace, and specialized spatial interior solutions.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($interiorServices as $serv): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden service-card-wrap">
                        <div class="service-card-img-box">
                            <img src="<?= getImageUrl($serv['image'], $serv['title'], 'Interior') ?>" alt="<?= e($serv['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="service-card-category-badge">INTERIOR</span>
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

<!-- 4. INTERIOR PROCESS -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">DESIGN EXECUTION FLOW</span>
            <h2 class="section-title">Our Interior Design Process</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">01</div>
                    <h5 class="fw-bold font-heading text-dark">Concept & Moodboarding</h5>
                    <p class="small text-muted mb-0">Initial consultation to map lifestyle requirements, aesthetic color palettes, material textures, and spatial layouts.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">02</div>
                    <h5 class="fw-bold font-heading text-dark">3D VR Visualization</h5>
                    <p class="small text-muted mb-0">Crafting photorealistic 4K 3D renders and interactive VR walkthroughs to refine lighting and furniture placement.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">03</div>
                    <h5 class="fw-bold font-heading text-dark">Material Selection</h5>
                    <p class="small text-muted mb-0">Curating veneer swatches, Italian marble slabs, laminates, fabrics, soft furnishings, and German hardware.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">04</div>
                    <h5 class="fw-bold font-heading text-dark">Factory Manufacturing</h5>
                    <p class="small text-muted mb-0">Modular cabinetry is CNC panel-cut and PUR laser edge-banded in our automated plant under 0.1mm precision.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">05</div>
                    <h5 class="fw-bold font-heading text-dark">On-Site Assembly</h5>
                    <p class="small text-muted mb-0">Dust-free modular assembly on site, false ceiling installation, lighting track wiring, and wall cladding fit-out.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="process-step-card">
                    <div class="process-number">06</div>
                    <h5 class="fw-bold font-heading text-dark">Styling & Handover</h5>
                    <p class="small text-muted mb-0">Final soft furnishings, drapery installation, deep cleaning, quality audit, and handover with a 10-year warranty.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. WHY CHOOSE RAMAN GROUP -->
<section class="section-padding bg-dark text-white">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle text-warning">THE INTERIOR DIFFERENCE</span>
            <h2 class="section-title text-white">Why Choose Raman Group</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-user-pen"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Experienced Interior Architects</h5>
                    <p class="small text-secondary mb-0">Award-winning spatial designers translating your vision into functional aesthetic reality.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-cogs"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">German CNC Precision</h5>
                    <p class="small text-secondary mb-0">Automated factory cutting and PUR laser edge-banding for seamless zero-joint cabinet finishes.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-droplet-slash"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">710 BWP Marine Plywood</h5>
                    <p class="small text-secondary mb-0">100% boiling waterproof marine plywood carcasses for kitchens and bathroom vanities.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-vr-cardboard"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">3D VR Walkthroughs</h5>
                    <p class="small text-secondary mb-0">Walk through your customized home in virtual reality before a single board is manufactured.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">Transparent Pricing</h5>
                    <p class="small text-secondary mb-0">Detailed rate-card billing with zero hidden costs or unannounced price increases.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-clock"></i></div>
                    <h5 class="font-heading fw-bold text-warning mb-2">30-Day Turnaround</h5>
                    <p class="small text-secondary mb-0">Off-site factory manufacturing guarantees quick 30-day on-site modular assembly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. FEATURED INTERIOR PROJECTS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="section-subtitle">DESIGN PORTFOLIO</span>
                <h2 class="section-title mb-0">Featured Interior Projects</h2>
            </div>
            <a href="projects.php?category=2" class="btn btn-outline-gold btn-sm font-heading fw-bold">View All Interior Projects <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($projects as $proj): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card">
                        <div class="project-thumb">
                            <img src="<?= getImageUrl($proj['featured_image'], $proj['title'], 'Interior Design') ?>" alt="<?= e($proj['title']) ?>" class="w-100 h-100 object-fit-cover">
                            <span class="project-category-badge">Interior Design</span>
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

<!-- 7. INTERIOR GALLERY -->
<section class="section-padding">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">CREATIVE PORTFOLIO</span>
            <h2 class="section-title">Interior Design Gallery</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($galleryImages as $img): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="gallery-item shadow-sm lightbox-trigger" data-image="<?= getImageUrl($img['image_path'], $img['title'], 'Interior Design') ?>" data-title="<?= e($img['title']) ?> - <?= e($img['location']) ?>" style="cursor: pointer;">
                        <img src="<?= getImageUrl($img['image_path'], $img['title'], 'Interior Design') ?>" alt="<?= e($img['title']) ?>" class="w-100 h-100 object-fit-cover">
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
                    <div class="stat-number">250+</div>
                    <div class="stat-label">Luxury Homes Completed</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">80+</div>
                    <div class="stat-label">Workspaces Fit-Out</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">30 Days</div>
                    <div class="stat-label">Factory Turnaround</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">10 Years</div>
                    <div class="stat-label">Hardware Warranty</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. TESTIMONIALS -->
<section class="section-padding bg-light-surface">
    <div class="container">
        <div class="text-center section-title-wrap">
            <span class="section-subtitle">CLIENT PRAISE</span>
            <h2 class="section-title">What Our Interior Clients Say</h2>
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
            <h2 class="section-title">Interior Design FAQs</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion custom-accordion" id="interiorFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqIntH1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqIntC1" aria-expanded="true" aria-controls="faqIntC1">
                                How does the interior design process work?
                            </button>
                        </h2>
                        <div id="faqIntC1" class="accordion-collapse collapse show" aria-labelledby="faqIntH1" data-bs-parent="#interiorFAQ">
                            <div class="accordion-body text-muted">
                                We begin with a spatial consultation and moodboard creation, followed by 3D photorealistic VR renderings, material sampling, German CNC factory fabrication, dust-free site assembly, and final styling.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqIntH2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqIntC2" aria-expanded="false" aria-controls="faqIntC2">
                                What material grade do you use for modular kitchens?
                            </button>
                        </h2>
                        <div id="faqIntC2" class="accordion-collapse collapse" aria-labelledby="faqIntH2" data-bs-parent="#interiorFAQ">
                            <div class="accordion-body text-muted">
                                We exclusively use certified Grade 710 BWP (Boiling Water Proof) marine plywood for kitchen carcasses paired with Blum / Hettich soft-close German hardware mechanisms.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqIntH3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqIntC3" aria-expanded="false" aria-controls="faqIntC3">
                                Do you provide 3D photorealistic renderings before work starts?
                            </button>
                        </h2>
                        <div id="faqIntC3" class="accordion-collapse collapse" aria-labelledby="faqIntH3" data-bs-parent="#interiorFAQ">
                            <div class="accordion-body text-muted">
                                Yes. Every interior project includes 3D photorealistic renderings and VR walkthroughs. You review exact finishes, lighting warmth, and furniture proportions before factory cutting begins.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqIntH4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqIntC4" aria-expanded="false" aria-controls="faqIntC4">
                                What is the typical timeline for a 3BHK home interior?
                            </button>
                        </h2>
                        <div id="faqIntC4" class="accordion-collapse collapse" aria-labelledby="faqIntH4" data-bs-parent="#interiorFAQ">
                            <div class="accordion-body text-muted">
                                Factory production takes 30 days, followed by 10 to 14 days of clean on-site assembly, false ceiling fit-out, lighting installation, and soft furnishing styling.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqIntH5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqIntC5" aria-expanded="false" aria-controls="faqIntC5">
                                What is the warranty on modular wardrobes and furniture?
                            </button>
                        </h2>
                        <div id="faqIntC5" class="accordion-collapse collapse" aria-labelledby="faqIntH5" data-bs-parent="#interiorFAQ">
                            <div class="accordion-body text-muted">
                                All factory-manufactured modular furniture comes with a 5-year warranty on cabinet joinery and a 10-year warranty on German hardware fittings.
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
            <h2 class="display-5 font-heading text-white fw-bold mb-3">Ready to Transform Your Space?</h2>
            <p class="lead text-light max-w-700 mx-auto mb-4">Book a consultation with our senior interior architect or request a custom estimation.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="quote.php?service=Interior" class="btn btn-gold btn-lg font-heading fw-bold py-3 px-5 shadow-lg"><i class="fas fa-calculator me-2"></i> Request an Interior Quote</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg font-heading fw-bold py-3 px-5"><i class="fas fa-envelope me-2"></i> Contact Raman Group</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
