<?php
/**
 * Customer Quotes Management
 * Raman Group Platform
 */
$customerPageTitle = "My Quote Requests";
require_once __DIR__ . '/includes/customer-header.php';

$db = getDB();
$customerId = $_SESSION['customer_id'];
$customer = getLoggedInCustomer();

$message = '';
$error = '';

// Handle Accept / Reject Quote Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'update_quote_status') {
    $quoteId = (int)($_POST['quote_id'] ?? 0);
    $newStatus = sanitize($_POST['new_status'] ?? '');

    if ($quoteId > 0 && in_array($newStatus, ['Accepted', 'Rejected'])) {
        // Verify quote belongs to logged-in customer
        $stmtVerify = $db->prepare("SELECT id FROM quote_requests WHERE id = ? AND customer_id = ?");
        $stmtVerify->execute([$quoteId, $customerId]);
        if ($stmtVerify->fetch()) {
            $stmtUpd = $db->prepare("UPDATE quote_requests SET status = ? WHERE id = ?");
            $stmtUpd->execute([$newStatus, $quoteId]);
            setFlash('success', "Quote #{$quoteId} status has been updated to {$newStatus}.");
            header('Location: quotes.php');
            exit;
        } else {
            $error = "Unauthorized attempt to modify quote.";
        }
    }
}

// Handle Submit New Quote Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'submit_quote') {
    $serviceCategory = sanitize($_POST['service_category'] ?? '');
    $projectType = sanitize($_POST['project_type'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $estimatedBudget = sanitize($_POST['estimated_budget'] ?? '');
    $startDate = !empty($_POST['preferred_start_date']) ? $_POST['preferred_start_date'] : null;
    $projectDesc = sanitize($_POST['project_description'] ?? '');
    $additionalReq = sanitize($_POST['additional_requirements'] ?? '');

    if (empty($serviceCategory) || empty($projectDesc)) {
        $error = "Please select a service vertical and provide a project description.";
    } else {
        $referenceFilePath = '';
        if (isset($_FILES['reference_file']) && $_FILES['reference_file']['error'] === UPLOAD_ERR_OK) {
            $uploadRes = handleFileUpload($_FILES['reference_file'], 'quotes');
            if ($uploadRes['success']) {
                $referenceFilePath = $uploadRes['filepath'];
            }
        }

        try {
            $stmtIns = $db->prepare("INSERT INTO quote_requests (customer_id, full_name, phone, email, service_category, project_type, location, estimated_budget, preferred_start_date, project_description, additional_requirements, reference_file, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
            $stmtIns->execute([
                $customerId,
                $customer['full_name'],
                $customer['phone'],
                $customer['email'],
                $serviceCategory,
                $projectType,
                $location,
                $estimatedBudget,
                $startDate,
                $projectDesc,
                $additionalReq,
                $referenceFilePath
            ]);

            setFlash('success', 'Your quote request has been submitted successfully! Our engineering team will review and respond shortly.');
            header('Location: quotes.php');
            exit;
        } catch (PDOException $e) {
            $error = "Failed to submit quote: " . $e->getMessage();
        }
    }
}

// Fetch all quotes for customer
$stmt = $db->prepare("SELECT * FROM quote_requests WHERE customer_id = ? ORDER BY id DESC");
$stmt->execute([$customerId]);
$quotes = $stmt->fetchAll();

$showForm = isset($_GET['action']) && $_GET['action'] === 'new';
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-heading text-white fw-bold mb-0">Quote Requests History</h4>
    <button class="btn btn-gold font-heading fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#newQuoteCollapse" aria-expanded="<?= $showForm ? 'true' : 'false' ?>">
        <i class="fas fa-plus-circle me-1"></i> <?= $showForm ? 'Close Form' : 'Request New Quote' ?>
    </button>
</div>

<!-- New Quote Request Form (Collapsible) -->
<div class="collapse <?= $showForm ? 'show' : '' ?> mb-5" id="newQuoteCollapse">
    <div class="card portal-card border-gold">
        <div class="portal-card-header bg-navy border-bottom border-gold">
            <h5 class="font-heading text-warning mb-0"><i class="fas fa-calculator me-2"></i> Submit Construction / Interior / Fabrication Quote Request</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="quotes.php" enctype="multipart/form-data">
                <input type="hidden" name="action_type" value="submit_quote">

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="service_category" class="form-label text-warning small font-heading fw-bold">Service Vertical *</label>
                        <select class="form-select bg-dark text-white border-secondary" id="service_category" name="service_category" required>
                            <option value="">-- Select Service --</option>
                            <option value="Construction">Construction Services</option>
                            <option value="Interior Design">Interior Design Services</option>
                            <option value="Fabrication">Fabrication Services</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="project_type" class="form-label text-warning small font-heading fw-bold">Project Scope / Type</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" id="project_type" name="project_type" placeholder="e.g. 4-BHK Villa Construction, Kitchen Interior, PEB Shed">
                    </div>
                    <div class="col-md-4">
                        <label for="estimated_budget" class="form-label text-warning small font-heading fw-bold">Estimated Budget Range</label>
                        <select class="form-select bg-dark text-white border-secondary" id="estimated_budget" name="estimated_budget">
                            <option value="Under ₹5 Lakhs">Under ₹5 Lakhs</option>
                            <option value="₹5 Lakhs - ₹15 Lakhs">₹5 Lakhs - ₹15 Lakhs</option>
                            <option value="₹15 Lakhs - ₹50 Lakhs">₹15 Lakhs - ₹50 Lakhs</option>
                            <option value="₹50 Lakhs - ₹1 Crore">₹50 Lakhs - ₹1 Crore</option>
                            <option value="₹1 Crore+">₹1 Crore+</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="location" class="form-label text-warning small font-heading fw-bold">Project Site Location</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" id="location" name="location" placeholder="e.g. Sector 128, Noida">
                    </div>
                    <div class="col-md-6">
                        <label for="preferred_start_date" class="form-label text-warning small font-heading fw-bold">Preferred Start Date</label>
                        <input type="date" class="form-control bg-dark text-white border-secondary" id="preferred_start_date" name="preferred_start_date">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="project_description" class="form-label text-warning small font-heading fw-bold">Project Description & Specifications *</label>
                    <textarea class="form-control bg-dark text-white border-secondary" id="project_description" name="project_description" rows="4" placeholder="Describe your plot area, material preferences, architectural guidelines, structural requirements..." required></textarea>
                </div>

                <div class="mb-3">
                    <label for="additional_requirements" class="form-label text-warning small font-heading fw-bold">Additional Notes / Special Instructions</label>
                    <textarea class="form-control bg-dark text-white border-secondary" id="additional_requirements" name="additional_requirements" rows="2" placeholder="Any specific brand requirements (e.g. Tata Steel, Blum hardware, Saint-Gobain glass)..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="reference_file" class="form-label text-warning small font-heading fw-bold">Upload Plan / Layout / Reference File (PDF, Images, DOC)</label>
                    <input type="file" class="form-control bg-dark text-white border-secondary" id="reference_file" name="reference_file">
                    <div class="form-text text-muted">Max file size: 10MB</div>
                </div>

                <button type="submit" class="btn btn-gold font-heading fw-bold px-4 py-2"><i class="fas fa-paper-plane me-2"></i> Submit Request</button>
            </form>
        </div>
    </div>
</div>

<!-- Quotes List Table -->
<div class="card portal-card">
    <div class="card-body p-0">
        <?php if (empty($quotes)): ?>
            <div class="p-5 text-center text-muted">
                <i class="fas fa-calculator fs-1 mb-3 text-secondary opacity-50"></i>
                <h5 class="text-white">No Quote Requests Found</h5>
                <p class="text-muted">You haven't submitted any quote requests yet. Click the button above to request a proposal.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Quote ID</th>
                            <th>Service Vertical</th>
                            <th>Location & Budget</th>
                            <th>Current Status</th>
                            <th>Quotation Amount</th>
                            <th>Admin Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotes as $q): ?>
                            <tr>
                                <td><span class="fw-bold text-warning">#REQ-<?= $q['id'] ?></span></td>
                                <td>
                                    <div class="fw-bold text-white"><?= e($q['service_category'] ?: 'General') ?></div>
                                    <small class="text-muted"><?= e($q['project_type'] ?: 'Standard Build') ?></small>
                                </td>
                                <td>
                                    <div class="small"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($q['location'] ?: 'N/A') ?></div>
                                    <small class="text-muted">Est: <?= e($q['estimated_budget'] ?: 'N/A') ?></small>
                                </td>
                                <td><?= renderStatusBadge($q['status']) ?></td>
                                <td>
                                    <?php if ($q['quotation_amount']): ?>
                                        <span class="fw-bold text-success fs-5"><?= formatCurrency($q['quotation_amount']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">Pending Quote</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-light"><?= e($q['admin_notes'] ?: 'No notes yet.') ?></small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- View Details Modal Trigger -->
                                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#quoteModal<?= $q['id'] ?>" title="View Full Request">
                                            <i class="fas fa-eye"></i> Details
                                        </button>

                                        <!-- Accept / Reject Buttons if Quotation Sent -->
                                        <?php if ($q['status'] === 'Quotation Sent'): ?>
                                            <form method="POST" action="quotes.php" class="d-inline">
                                                <input type="hidden" name="action_type" value="update_quote_status">
                                                <input type="hidden" name="quote_id" value="<?= $q['id'] ?>">
                                                <input type="hidden" name="new_status" value="Accepted">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accept this quotation proposal?')" title="Accept Proposal"><i class="fas fa-check"></i> Accept</button>
                                            </form>
                                            <form method="POST" action="quotes.php" class="d-inline">
                                                <input type="hidden" name="action_type" value="update_quote_status">
                                                <input type="hidden" name="quote_id" value="<?= $q['id'] ?>">
                                                <input type="hidden" name="new_status" value="Rejected">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Decline this quotation proposal?')" title="Decline Proposal"><i class="fas fa-times"></i> Reject</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Details Modal -->
                                    <div class="modal fade text-dark" id="quoteModal<?= $q['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border-gold">
                                                <div class="modal-header bg-navy border-bottom border-gold">
                                                    <h5 class="modal-title text-warning font-heading fw-bold">Quote Request #REQ-<?= $q['id'] ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Service Vertical:</strong> <?= e($q['service_category']) ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Project Scope:</strong> <?= e($q['project_type'] ?: 'N/A') ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Location:</strong> <?= e($q['location'] ?: 'N/A') ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Estimated Budget:</strong> <?= e($q['estimated_budget'] ?: 'N/A') ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Preferred Start Date:</strong> <?= e($q['preferred_start_date'] ?: 'N/A') ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Current Status:</strong> <?= renderStatusBadge($q['status']) ?>
                                                        </div>
                                                    </div>

                                                    <hr class="border-secondary">

                                                    <div class="mb-3">
                                                        <strong class="text-warning">Project Description:</strong>
                                                        <p class="p-3 bg-navy rounded-3 border border-secondary text-light mt-1"><?= nl2br(e($q['project_description'])) ?></p>
                                                    </div>

                                                    <?php if ($q['additional_requirements']): ?>
                                                        <div class="mb-3">
                                                            <strong class="text-warning">Additional Requirements:</strong>
                                                            <p class="p-3 bg-navy rounded-3 border border-secondary text-light mt-1"><?= nl2br(e($q['additional_requirements'])) ?></p>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($q['reference_file']): ?>
                                                        <div class="mb-3">
                                                            <strong class="text-warning">Attachment File:</strong>
                                                            <div class="mt-1">
                                                                <a href="../<?= e($q['reference_file']) ?>" target="_blank" class="btn btn-outline-gold btn-sm"><i class="fas fa-download me-1"></i> Download Reference Attachment</a>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="p-3 bg-navy border border-warning rounded-3 mt-4">
                                                        <h6 class="text-warning fw-bold font-heading mb-1"><i class="fas fa-user-shield me-1"></i> Raman Group Engineering Quotation Response</h6>
                                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                                            <div>
                                                                <span class="small text-muted">Quoted Amount:</span>
                                                                <div class="fs-4 text-success fw-bold"><?= $q['quotation_amount'] ? formatCurrency($q['quotation_amount']) : 'Pending Evaluation' ?></div>
                                                            </div>
                                                        </div>
                                                        <p class="small text-light mt-2 mb-0"><strong>Remarks:</strong> <?= e($q['admin_notes'] ?: 'Under technical review by our engineering department.') ?></p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top border-secondary">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/customer-footer.php'; ?>
