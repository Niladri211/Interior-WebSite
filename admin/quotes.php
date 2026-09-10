<?php
/**
 * Admin Quote Management Module
 * Raman Group Management Portal
 */
$adminPageTitle = "Quotation Requests Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$error = '';
$success = '';

// Handle Convert Quote to Project Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'convert_to_project') {
    if (verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $quoteId = (int)$_POST['quote_id'];
        $stmtQ = $db->prepare("SELECT * FROM quote_requests WHERE id = ? LIMIT 1");
        $stmtQ->execute([$quoteId]);
        $q = $stmtQ->fetch();

        if ($q) {
            // Map category_id
            $catId = 1; // Default Construction
            $sc = strtolower($q['service_category']);
            if (str_contains($sc, 'interior')) {
                $catId = 2;
            } elseif (str_contains($sc, 'fabrication')) {
                $catId = 3;
            }

            $projectTitle = !empty($q['project_type']) ? $q['project_type'] . " - " . $q['full_name'] : $q['service_category'] . " Project - " . $q['full_name'];
            $slug = slugify($projectTitle) . '-' . time();
            $budget = $q['quotation_amount'] ?: null;
            $location = $q['location'] ?: 'Delhi NCR';

            try {
                $stmtProj = $db->prepare("
                    INSERT INTO projects 
                    (customer_id, category_id, title, slug, location, client_name, start_date, budget, status, short_description, description, scope_of_work, is_featured, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'In Progress', ?, ?, ?, 1, 1)
                ");
                $stmtProj->execute([
                    $q['customer_id'],
                    $catId,
                    $projectTitle,
                    $slug,
                    $location,
                    $q['full_name'],
                    $q['preferred_start_date'] ?: date('Y-m-d'),
                    $budget,
                    mb_strimwidth($q['project_description'], 0, 150, '...'),
                    $q['project_description'],
                    $q['additional_requirements'] ?: 'As per approved quotation proposal.'
                ]);

                // Update quote status to In Progress
                $stmtUpQuote = $db->prepare("UPDATE quote_requests SET status = 'In Progress', admin_notes = CONCAT(IFNULL(admin_notes,''), ' [Converted to Project #', LAST_INSERT_ID(), ']') WHERE id = ?");
                $stmtUpQuote->execute([$quoteId]);

                setFlash('success', "Quote #{$quoteId} has been successfully converted into an active Project!");
                header('Location: quotes.php');
                exit;
            } catch (PDOException $e) {
                $error = "Failed to convert quote to project: " . $e->getMessage();
            }
        }
    }
}

// Handle Update Quote Status & Amount
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_quote') {
    if (verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $quoteId = (int)$_POST['quote_id'];
        $newStatus = sanitize($_POST['status'] ?? 'Pending');
        $quotationAmount = !empty($_POST['quotation_amount']) ? (float)$_POST['quotation_amount'] : null;
        $adminNotes = sanitize($_POST['admin_notes'] ?? '');

        $stmtUp = $db->prepare("UPDATE quote_requests SET status = ?, quotation_amount = ?, admin_notes = ? WHERE id = ?");
        $stmtUp->execute([$newStatus, $quotationAmount, $adminNotes, $quoteId]);
        setFlash('success', "Quotation #{$quoteId} updated successfully.");
    }
    header("Location: quotes.php");
    exit;
}

// Handle Delete Quote
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $delId = (int)$_GET['id'];
        $stmtDel = $db->prepare("DELETE FROM quote_requests WHERE id = ?");
        $stmtDel->execute([$delId]);
        setFlash('success', "Quote request #{$delId} deleted.");
    }
    header("Location: quotes.php");
    exit;
}

// Fetch Filters
$search = sanitize($_GET['search'] ?? '');
$filterVertical = sanitize($_GET['vertical'] ?? '');
$filterStatus = sanitize($_GET['status'] ?? '');

$query = "SELECT q.*, c.full_name as customer_account_name, c.email as customer_account_email FROM quote_requests q LEFT JOIN customers c ON q.customer_id = c.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (q.full_name LIKE ? OR q.email LIKE ? OR q.phone LIKE ? OR q.location LIKE ? OR q.id = ?)";
    $params = ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", (int)$search];
}

if (!empty($filterVertical)) {
    $query .= " AND q.service_category LIKE ?";
    $params[] = "%{$filterVertical}%";
}

if (!empty($filterStatus)) {
    $query .= " AND q.status = ?";
    $params[] = $filterStatus;
}

$query .= " ORDER BY q.id DESC";
$stmtQ = $db->prepare($query);
$stmtQ->execute($params);
$quotesList = $stmtQ->fetchAll();
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-heading fw-bold mb-0 text-dark">Quotation Requests (<?= count($quotesList) ?>)</h4>
        <small class="text-muted">Review technical requirements, calculate estimations, send proposals, and convert into active projects.</small>
    </div>
</div>

<!-- Filters Bar -->
<div class="admin-table-card mb-4 p-3">
    <form method="GET" action="quotes.php" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone, location, quote ID..." value="<?= e($search) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="vertical" class="form-select">
                <option value="">-- All Service Verticals --</option>
                <option value="Construction" <?= $filterVertical === 'Construction' ? 'selected' : '' ?>>Construction</option>
                <option value="Interior" <?= str_contains($filterVertical, 'Interior') ? 'selected' : '' ?>>Interior Design</option>
                <option value="Fabrication" <?= $filterVertical === 'Fabrication' ? 'selected' : '' ?>>Fabrication</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- All Statuses --</option>
                <option value="Pending" <?= $filterStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Reviewing" <?= $filterStatus === 'Reviewing' ? 'selected' : '' ?>>Reviewing</option>
                <option value="Quotation Sent" <?= $filterStatus === 'Quotation Sent' ? 'selected' : '' ?>>Quotation Sent</option>
                <option value="Accepted" <?= $filterStatus === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                <option value="Rejected" <?= $filterStatus === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                <option value="In Progress" <?= $filterStatus === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= $filterStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="fas fa-filter"></i> Filter</button>
            <?php if (!empty($search) || !empty($filterVertical) || !empty($filterStatus)): ?>
                <a href="quotes.php" class="btn btn-outline-secondary" title="Reset Filters"><i class="fas fa-undo"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Quotes Table Card -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Ref # & Client</th>
                    <th>Vertical & Scope</th>
                    <th>Budget & Start Date</th>
                    <th>Quotation Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quotesList)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calculator fs-2 mb-2 d-block text-secondary"></i>
                            No quotation requests found matching your criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($quotesList as $q): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">#REQ-<?= $q['id'] ?> - <?= e($q['full_name']) ?></div>
                                <div class="small text-muted"><i class="fas fa-envelope text-warning me-1"></i> <?= e($q['email']) ?></div>
                                <div class="small text-muted"><i class="fas fa-phone text-warning me-1"></i> <?= e($q['phone']) ?></div>
                                <?php if ($q['customer_id']): ?>
                                    <span class="badge bg-success text-white mt-1"><i class="fas fa-user-check me-1"></i> Linked Account</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-dark text-warning"><?= e($q['service_category'] ?: 'General') ?></span><br>
                                <small class="text-muted"><?= e($q['project_type'] ?: 'Standard') ?></small>
                                <div class="small text-muted"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($q['location'] ?: 'N/A') ?></div>
                            </td>
                            <td>
                                <small class="fw-bold text-success d-block">Est: <?= e($q['estimated_budget'] ?: 'N/A') ?></small>
                                <small class="text-muted">Start: <?= e($q['preferred_start_date'] ?: 'Flexible') ?></small>
                            </td>
                            <td>
                                <?php if ($q['quotation_amount']): ?>
                                    <span class="fw-bold text-success fs-6"><?= formatCurrency($q['quotation_amount']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">Not Quoted</span>
                                <?php endif; ?>
                            </td>
                            <td><?= renderStatusBadge($q['status']) ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-dark btn-action" data-bs-toggle="modal" data-bs-target="#quoteModal<?= $q['id'] ?>" title="Manage Quotation"><i class="fas fa-edit"></i> Edit</button>
                                <a href="quotes.php?action=delete&id=<?= $q['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Delete quote request #REQ-<?= $q['id'] ?>?');" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Quote Detail & Workflow Modal -->
                        <div class="modal fade text-start" id="quoteModal<?= $q['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title font-heading text-warning">Manage Quotation #REQ-<?= $q['id'] ?> - <?= e($q['full_name']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <strong>Client Name:</strong> <?= e($q['full_name']) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Email:</strong> <?= e($q['email']) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Phone:</strong> <?= e($q['phone']) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Location:</strong> <?= e($q['location'] ?: 'N/A') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Service Vertical:</strong> <span class="badge bg-warning text-dark"><?= e($q['service_category']) ?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Est. Budget:</strong> <?= e($q['estimated_budget'] ?: 'N/A') ?>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <strong class="font-heading">Project Description:</strong>
                                            <div class="p-3 bg-light rounded border mt-1 small"><?= nl2br(e($q['project_description'])) ?></div>
                                        </div>

                                        <?php if ($q['additional_requirements']): ?>
                                            <div class="mb-3">
                                                <strong class="font-heading">Additional Requirements:</strong>
                                                <div class="p-3 bg-light rounded border mt-1 small"><?= nl2br(e($q['additional_requirements'])) ?></div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($q['reference_file'])): ?>
                                            <div class="mb-3">
                                                <strong class="font-heading me-2">Attachment File:</strong>
                                                <a href="../<?= e($q['reference_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i> Download Reference Attachment</a>
                                            </div>
                                        <?php endif; ?>

                                        <hr>

                                        <!-- Update Form -->
                                        <form method="POST" action="quotes.php" class="bg-light p-3 rounded border">
                                            <?= getCSRFInput() ?>
                                            <input type="hidden" name="action" value="update_quote">
                                            <input type="hidden" name="quote_id" value="<?= $q['id'] ?>">

                                            <h6 class="font-heading fw-bold text-dark mb-3"><i class="fas fa-sliders text-warning me-1"></i> Update Quotation Workflow & Proposal</h6>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label font-heading fw-bold">Status *</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Pending" <?= $q['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="Reviewing" <?= $q['status'] === 'Reviewing' ? 'selected' : '' ?>>Reviewing</option>
                                                        <option value="Quotation Sent" <?= $q['status'] === 'Quotation Sent' ? 'selected' : '' ?>>Quotation Sent</option>
                                                        <option value="Accepted" <?= $q['status'] === 'Accepted' ? 'selected' : '' ?>>Accepted (Client Approved)</option>
                                                        <option value="Rejected" <?= $q['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                                        <option value="In Progress" <?= $q['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress (Converted to Build)</option>
                                                        <option value="Completed" <?= $q['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label font-heading fw-bold">Quotation Amount (₹)</label>
                                                    <input type="number" step="0.01" name="quotation_amount" class="form-control" placeholder="e.g. 450000" value="<?= e($q['quotation_amount']) ?>">
                                                    <div class="form-text">Will be visible in Customer Portal once status is set to 'Quotation Sent'.</div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label font-heading fw-bold">Admin Remarks / Proposal Notes</label>
                                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Enter engineering estimation breakdown, payment terms, or response notes..."><?= e($q['admin_notes']) ?></textarea>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <button type="submit" class="btn btn-gold font-heading fw-bold"><i class="fas fa-save me-1"></i> Update Quotation</button>
                                            </div>
                                        </form>

                                        <!-- Convert to Project Button -->
                                        <?php if (in_array($q['status'], ['Accepted', 'Quotation Sent', 'Reviewing'])): ?>
                                            <div class="mt-4 p-3 bg-dark text-white rounded border border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="font-heading text-warning mb-1"><i class="fas fa-rocket me-1"></i> Convert to Active Project</h6>
                                                        <p class="small text-muted mb-0">Instantly create a new live project entry in the portfolio assigned to this customer account.</p>
                                                    </div>
                                                    <form method="POST" action="quotes.php" onsubmit="return confirm('Convert quote #REQ-<?= $q['id'] ?> into an active Project?');">
                                                        <?= getCSRFInput() ?>
                                                        <input type="hidden" name="action" value="convert_to_project">
                                                        <input type="hidden" name="quote_id" value="<?= $q['id'] ?>">
                                                        <button type="submit" class="btn btn-success font-heading fw-bold"><i class="fas fa-plus-circle me-1"></i> Convert to Project</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
