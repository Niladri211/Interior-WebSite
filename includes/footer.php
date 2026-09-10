<?php
/**
 * Footer Component
 * Raman Group Website
 */
require_once __DIR__ . '/functions.php';
$phone = getSiteSetting('phone', '+91 98765 43210');
$email = getSiteSetting('email', 'info@ramangroup.com');
$address = getSiteSetting('address', 'Raman Towers, Plot No. 45, Sector 62, Noida, UP - 201309');
$whatsapp = getSiteSetting('whatsapp_number', '919876543210');
?>

<!-- Site Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: Brand Info -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <i class="fas fa-building text-warning me-2"></i> RAMAN GROUP
                </div>
                <p class="text-secondary mb-4">
                    Building Spaces. Designing Experiences. We deliver end-to-end Construction, Bespoke Interior Architecture, and Heavy Precision Metal Fabrication under one unified corporate umbrella.
                </p>
                <div class="d-flex gap-2">
                    <a href="tel:<?= e($phone) ?>" class="btn btn-outline-gold btn-sm"><i class="fas fa-phone-alt me-1"></i> Call Us</a>
                    <a href="quote.php" class="btn btn-gold btn-sm"><i class="fas fa-file-signature me-1"></i> Request Quote</a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-angle-right text-warning"></i> Home</a></li>
                    <li><a href="about.php"><i class="fas fa-angle-right text-warning"></i> About Us</a></li>
                    <li><a href="services.php"><i class="fas fa-angle-right text-warning"></i> Services</a></li>
                    <li><a href="projects.php"><i class="fas fa-angle-right text-warning"></i> Portfolio</a></li>
                    <li><a href="gallery.php"><i class="fas fa-angle-right text-warning"></i> Photo Gallery</a></li>
                    <li><a href="testimonials.php"><i class="fas fa-angle-right text-warning"></i> Testimonials</a></li>
                    <li><a href="contact.php"><i class="fas fa-angle-right text-warning"></i> Contact Us</a></li>
                </ul>
            </div>

            <!-- Col 3: Our Services -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Our Verticals</h5>
                <ul class="footer-links">
                    <li><a href="services-construction.php"><i class="fas fa-hard-hat text-warning"></i> Turnkey Construction</a></li>
                    <li><a href="services-construction.php"><i class="fas fa-building text-warning"></i> Commercial Buildings</a></li>
                    <li><a href="services-interior.php"><i class="fas fa-couch text-warning"></i> Luxury Home Interiors</a></li>
                    <li><a href="services-interior.php"><i class="fas fa-utensils text-warning"></i> Modular Kitchens</a></li>
                    <li><a href="services-fabrication.php"><i class="fas fa-industry text-warning"></i> Steel & PEB Structures</a></li>
                    <li><a href="services-fabrication.php"><i class="fas fa-door-closed text-warning"></i> Automated Laser Gates</a></li>
                    <li><a href="services-fabrication.php"><i class="fas fa-bars-staggered text-warning"></i> SS 316 Glass Railings</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Headquarters</h5>
                <ul class="footer-links text-secondary">
                    <li class="d-flex gap-2">
                        <i class="fas fa-map-marker-alt text-warning mt-1"></i>
                        <span><?= e($address) ?></span>
                    </li>
                    <li class="d-flex gap-2 mt-2">
                        <i class="fas fa-phone-alt text-warning mt-1"></i>
                        <span><a href="tel:<?= e($phone) ?>"><?= e($phone) ?></a></span>
                    </li>
                    <li class="d-flex gap-2 mt-2">
                        <i class="fas fa-envelope text-warning mt-1"></i>
                        <span><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></span>
                    </li>
                    <li class="d-flex gap-2 mt-2">
                        <i class="fas fa-clock text-warning mt-1"></i>
                        <span>Mon - Sat: 9:00 AM - 7:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom text-center">
            <p class="mb-0">
                &copy; <?= date('Y') ?> <strong>Raman Group</strong>. All Rights Reserved. Designed & Engineered for High Performance.
            </p>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/<?= e($whatsapp) ?>?text=Hello%20Raman%20Group,%20I%20would%20like%20to%20enquire%20about%20your%20services." 
   class="whatsapp-float" 
   target="_blank" 
   title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to Top Button -->
<button id="scrollTopBtn" class="scroll-top-btn" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Bootstrap 5 JS Bundle -->
<script href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Custom Scripts -->
<script src="assets/js/main.js"></script>
<script src="assets/js/gallery-lightbox.js"></script>

</body>
</html>
