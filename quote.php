<?php
/**
 * Dedicated Quotation Request Form - Raman Group
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$errors = [];
$successMsg = '';

// Pre-fill service name if passed in URL
$prefilledService = isset($_GET['service']) ? sanitize($_GET['service']) : '';
$prefilledProject = isset($_GET['project']) ? sanitize($_GET['project']) : '';

// Customer pre-fill if logged in
$loggedInCustomer = getLoggedInCustomer();
$defaultName = $loggedInCustomer['full_name'] ?? '';
$defaultEmail = $loggedInCustomer['email'] ?? '';
$defaultPhone = $loggedInCustomer['phone'] ?? '';
$customerId = $loggedInCustomer['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        $errors[] = "Security token mismatch. Please refresh and try again.";
    } else {
        $fullName = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $serviceCategory = sanitize($_POST['service_category'] ?? '');
        $projectType = sanitize($_POST['project_type'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $estimatedBudget = sanitize($_POST['estimated_budget'] ?? '');
        $startDate = !empty($_POST['preferred_start_date']) ? $_POST['preferred_start_date'] : null;
        $projectDescription = sanitize($_POST['project_description'] ?? '');
        $additionalReq = sanitize($_POST['additional_requirements'] ?? '');

        // Validation
        if (empty($fullName)) $errors[] = "Full Name is required.";
        if (empty($phone)) $errors[] = "Phone Number is required.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid Email Address is required.";
        if (empty($projectDescription)) $errors[] = "Project Description is required.";

        // Handle Secure File Upload
        $uploadedFilePath = '';
        if (isset($_FILES['reference_file']) && $_FILES['reference_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = handleFileUpload($_FILES['reference_file'], 'quotes', ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'], 10485760);
            if ($uploadResult['success']) {
                $uploadedFilePath = $uploadResult['filepath'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $db->prepare("INSERT INTO quote_requests 
                    (customer_id, full_name, phone, email, service_category, project_type, location, estimated_budget, preferred_start_date, project_description, additional_requirements, reference_file, status) 
                    VALUES (:cust, :name, :phone, :email, :service, :ptype, :loc, :budget, :sdate, :desc, :addreq, :file, 'Pending')");
                
                $stmt->execute([
                    ':cust' => $customerId,
                    ':name' => $fullName,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':service' => $serviceCategory,
                    ':ptype' => $projectType,
                    ':loc' => $location,
                    ':budget' => $estimatedBudget,
                    ':sdate' => $startDate,
                    ':desc' => $projectDescription,
                    ':addreq' => $additionalReq,
                    ':file' => $uploadedFilePath
                ]);

                $reqId = $db->lastInsertId();
                $successMsg = "Your quotation request #REQ-" . $reqId . " has been submitted successfully! Our engineering team will review your specifications and update your quotation status.";
            } catch (Exception $e) {
                $errors[] = "System Error: " . $e->getMessage();
            }
        }
    }
}

$customPageTitle = "Get a Quote – Raman Group";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Breadcrumbs Header -->
<div class="breadcrumb-wrap text-center">
    <div class="container">
        <h1 class="text-white font-heading display-5 fw-bold mb-2">Request a Project Quotation</h1>
        <p class="text-light max-w-600 mx-auto">Upload architectural drawings, floor plans, or concept photos to receive a detailed BOQ estimate.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Get a Quote</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Quotation Form Section -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="p-4 p-md-5 bg-white rounded-4 border shadow-lg">
                    
                    <div class="text-center mb-4">
                        <span class="badge bg-warning text-dark font-heading fw-bold px-3 py-2 text-uppercase mb-2">FREE BOQ ESTIMATION</span>
                        <h2 class="font-heading fw-bold">Detailed Quotation Request Form</h2>
                        <p class="text-muted">Fill in your specifications below to receive an itemized rate card and architectural consultation.</p>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i> <?= implode('<br>', $errors) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($successMsg)): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4 p-4 shadow-sm">
                            <h4 class="alert-heading font-heading fw-bold"><i class="fas fa-check-circle me-2"></i> Quotation Request Received!</h4>
                            <p class="mb-2"><?= e($successMsg) ?></p>
                            <?php if ($customerId): ?>
                                <a href="customer/quotes.php" class="btn btn-gold btn-sm font-heading fw-bold mt-2"><i class="fas fa-user-circle me-1"></i> Track Status in Your Customer Portal</a>
                            <?php else: ?>
                                <p class="small text-muted mb-0">You can <a href="register.php" class="fw-bold text-dark">register a customer account</a> using email <strong><?= e($_POST['email'] ?? '') ?></strong> to track live quote progress online.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form action="quote.php" method="POST" enctype="multipart/form-data">
                        <?= getCSRFInput() ?>

                        <div class="row g-4">
                            <!-- Contact Details -->
                            <div class="col-12">
                                <h5 class="font-heading fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-user text-warning me-2"></i> 1. Contact Information</h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Full Name *</label>
                                <input type="text" name="full_name" class="form-control form-control-lg fs-6" value="<?= e($_POST['full_name'] ?? $defaultName) ?>" placeholder="e.g. Ananya Roy" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control form-control-lg fs-6" value="<?= e($_POST['phone'] ?? $defaultPhone) ?>" placeholder="e.g. +91 98765 43210" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Email Address *</label>
                                <input type="email" name="email" class="form-control form-control-lg fs-6" value="<?= e($_POST['email'] ?? $defaultEmail) ?>" placeholder="e.g. ananya@domain.com" required>
                            </div>

                            <!-- Project Details -->
                            <div class="col-12 mt-4">
                                <h5 class="font-heading fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-sliders text-warning me-2"></i> 2. Project Specifications</h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Service Category *</label>
                                <select name="service_category" class="form-select form-select-lg fs-6">
                                    <option value="Construction" <?= str_contains($prefilledService, 'Construction') ? 'selected' : '' ?>>Construction Services</option>
                                    <option value="Interior Design" <?= str_contains($prefilledService, 'Interior') ? 'selected' : '' ?>>Interior Design Services</option>
                                    <option value="Fabrication" <?= str_contains($prefilledService, 'Fabrication') ? 'selected' : '' ?>>Metal Fabrication Services</option>
                                    <option value="Turnkey Project">Turnkey (Civil + Interior + Metal)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Project Type / Service Name</label>
                                <input type="text" name="project_type" class="form-control form-control-lg fs-6" value="<?= e($prefilledService ?: $prefilledProject) ?>" placeholder="e.g. Modular Kitchen, Residential Villa, PEB Shed">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label font-heading fw-bold small">Site Location / City</label>
                                <input type="text" name="location" class="form-control form-control-lg fs-6" placeholder="e.g. Golf Course Road, Gurugram">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Estimated Budget Limit</label>
                                <select name="estimated_budget" class="form-select form-select-lg fs-6">
                                    <option value="Under ₹10 Lakhs">Under ₹10 Lakhs</option>
                                    <option value="₹10 Lakhs - ₹25 Lakhs">₹10 Lakhs - ₹25 Lakhs</option>
                                    <option value="₹25 Lakhs - ₹50 Lakhs">₹25 Lakhs - ₹50 Lakhs</option>
                                    <option value="₹50 Lakhs - ₹1 Crore">₹50 Lakhs - ₹1 Crore</option>
                                    <option value="Above ₹1 Crore">Above ₹1 Crore</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-heading fw-bold small">Preferred Start Date</label>
                                <input type="date" name="preferred_start_date" class="form-control form-control-lg fs-6">
                            </div>

                            <div class="col-12">
                                <label class="form-label font-heading fw-bold small">Detailed Scope / Project Specifications *</label>
                                <textarea name="project_description" rows="4" class="form-control fs-6" placeholder="Please outline plot dimensions, covered area (sq.ft), preferred material grades (e.g. SS 316, BWP Plywood, Fe-550 TMT)..." required></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-heading fw-bold small">Additional Requirements / Notes (Optional)</label>
                                <textarea name="additional_requirements" rows="2" class="form-control fs-6" placeholder="Any specific brand preferences or special architectural notes..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-heading fw-bold small">Reference Drawings / Blueprints / File (Optional)</label>
                                <input type="file" name="reference_file" class="form-control form-control-lg fs-6" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                                <div class="form-text small">Allowed: JPG, PNG, WEBP, PDF, DOCX (Max 10MB)</div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-gold btn-lg px-5 py-3 shadow-lg font-heading fw-bold fs-5">
                                    <i class="fas fa-calculator me-2"></i> Submit Quotation Request
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
