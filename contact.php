<?php
/**
 * Contact Us & Direct Enquiry Page - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$errors = [];
$successMsg = '';

$loggedInCustomer = getLoggedInCustomer();
$customerId = $loggedInCustomer['id'] ?? null;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        $errors[] = "Security validation failed. Please refresh the page and try again.";
    } else {
        $fullName = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $serviceCategory = sanitize($_POST['service_category'] ?? '');
        $projectType = sanitize($_POST['project_type'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $budgetRange = sanitize($_POST['budget_range'] ?? '');
        $message = sanitize($_POST['message'] ?? '');
        $preferredDate = !empty($_POST['preferred_contact_date']) ? $_POST['preferred_contact_date'] : null;

        // Validations
        if (empty($fullName)) $errors[] = "Full Name is required.";
        if (empty($phone)) $errors[] = "Phone number is required.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid Email address is required.";
        if (empty($message)) $errors[] = "Project Message/Requirement is required.";

        if (empty($errors)) {
            try {
                $stmt = $db->prepare("INSERT INTO enquiries 
                    (customer_id, full_name, phone, email, service_category, project_type, location, budget_range, message, preferred_contact_date, status) 
                    VALUES (:cust, :name, :phone, :email, :service, :ptype, :loc, :budget, :msg, :pdate, 'New')");
                
                $stmt->execute([
                    ':cust' => $customerId,
                    ':name' => $fullName,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':service' => $serviceCategory,
                    ':ptype' => $projectType,
                    ':loc' => $location,
                    ':budget' => $budgetRange,
                    ':msg' => $message,
                    ':pdate' => $preferredDate
                ]);

                // Also insert into contact_messages for admin tracking
                $stmtMsg = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (:name, :email, :phone, :subj, :msg, 'Unread')");
                $stmtMsg->execute([
                    ':name' => $fullName,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':subj' => "Website Enquiry - " . ($serviceCategory ?: 'General'),
                    ':msg' => $message
                ]);

                // AJAX Response or Standard Page Flash
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Thank you! Your enquiry has been registered successfully. Our senior engineer will contact you shortly.']);
                    exit;
                }

                $successMsg = "Thank you! Your enquiry has been submitted successfully. Our senior engineer will get in touch with you shortly.";
            } catch (Exception $ex) {
                $errors[] = "Database save error: " . $ex->getMessage();
            }
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
                exit;
            }
        }
    }
}

$customPageTitle = "Contact Us – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Contact & Project Enquiries</h1>
        <p class="text-light max-w-600 mx-auto">Get in touch with our executive management, schedule a site inspection, or send us a direct message.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Contact Info & Form Section -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Left Info Col -->
            <div class="col-lg-5">
                <span class="section-subtitle">GET IN TOUCH</span>
                <h2 class="section-title mb-4">We are Ready to Build Your Vision</h2>
                <p class="text-muted mb-4">
                    Have an upcoming residential villa, commercial office complex, or heavy steel fabrication project? Visit our corporate headquarters or connect directly via phone/email.
                </p>

                <div class="d-flex align-items-start gap-3 mb-4 p-3 bg-light rounded-4 border">
                    <div class="bg-warning text-dark p-3 rounded-circle"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                    <div>
                        <h6 class="font-heading fw-bold mb-1">Corporate Headquarters</h6>
                        <p class="small text-muted mb-0"><?= e(getSiteSetting('address')) ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4 p-3 bg-light rounded-4 border">
                    <div class="bg-warning text-dark p-3 rounded-circle"><i class="fas fa-phone-alt fa-lg"></i></div>
                    <div>
                        <h6 class="font-heading fw-bold mb-1">Direct Phone Lines</h6>
                        <p class="small text-muted mb-0">Primary: <a href="tel:<?= e(getSiteSetting('phone')) ?>"><?= e(getSiteSetting('phone')) ?></a></p>
                        <p class="small text-muted mb-0">Secondary: <a href="tel:<?= e(getSiteSetting('secondary_phone')) ?>"><?= e(getSiteSetting('secondary_phone')) ?></a></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4 p-3 bg-light rounded-4 border">
                    <div class="bg-warning text-dark p-3 rounded-circle"><i class="fas fa-envelope fa-lg"></i></div>
                    <div>
                        <h6 class="font-heading fw-bold mb-1">Email Desks</h6>
                        <p class="small text-muted mb-0">General: <a href="mailto:<?= e(getSiteSetting('email')) ?>"><?= e(getSiteSetting('email')) ?></a></p>
                        <p class="small text-muted mb-0">Projects: <a href="mailto:<?= e(getSiteSetting('support_email')) ?>"><?= e(getSiteSetting('support_email')) ?></a></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-4 border">
                    <div class="bg-warning text-dark p-3 rounded-circle"><i class="fas fa-clock fa-lg"></i></div>
                    <div>
                        <h6 class="font-heading fw-bold mb-1">Business Operating Hours</h6>
                        <p class="small text-muted mb-0"><?= e(getSiteSetting('business_hours')) ?></p>
                    </div>
                </div>
            </div>

            <!-- Right Form Col -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 bg-white rounded-4 border shadow-lg">
                    <h3 class="font-heading fw-bold mb-2">Send an Enquiry</h3>
                    <p class="text-muted small mb-4">Fill out the required fields below. All fields marked with (*) are mandatory.</p>

                    <div id="formAlert">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> <?= implode('<br>', $errors) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($successMsg)): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                                <i class="fas fa-check-circle me-2"></i> <?= e($successMsg) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form action="contact.php" method="POST" class="ajax-form" data-ajax="true">
                        <?= getCSRFInput() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" placeholder="e.g. Vikram Sharma" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 98765 43210" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. vikram@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Service Vertical</label>
                                <select name="service_category" class="form-select">
                                    <option value="Construction">Construction</option>
                                    <option value="Interior Design">Interior Design</option>
                                    <option value="Fabrication">Fabrication</option>
                                    <option value="All Verticals">Turnkey (All Verticals)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Project Type</label>
                                <input type="text" name="project_type" class="form-control" placeholder="e.g. Luxury Villa, Modular Kitchen, PEB Shed">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Project Location</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Sector 128, Noida">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Estimated Budget Range</label>
                                <select name="budget_range" class="form-select">
                                    <option value="Under ₹10 Lakhs">Under ₹10 Lakhs</option>
                                    <option value="₹10 Lakhs - ₹25 Lakhs">₹10 Lakhs - ₹25 Lakhs</option>
                                    <option value="₹25 Lakhs - ₹50 Lakhs">₹25 Lakhs - ₹50 Lakhs</option>
                                    <option value="₹50 Lakhs - ₹1 Cr">₹50 Lakhs - ₹1 Cr</option>
                                    <option value="Above ₹1 Cr">Above ₹1 Cr</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Preferred Contact Date</label>
                                <input type="date" name="preferred_contact_date" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-heading fw-bold small">Message / Requirement Details *</label>
                                <textarea name="message" rows="4" class="form-control" placeholder="Describe plot size, scope of work, timeline expectations..." required></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gold btn-lg w-100"><i class="fas fa-paper-plane me-2"></i> Submit Enquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Embedded Google Map -->
<section class="w-100">
    <iframe src="<?= e(getSiteSetting('google_map_embed')) ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Google Map Location"></iframe>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
