<?php
/**
 * Customer Dashboard Overview
 * Raman Group Platform
 */
$customerPageTitle = "Customer Dashboard";
require_once __DIR__ . '/includes/customer-header.php';

$db = getDB();
$customerId = $_SESSION['customer_id'];

// Customer Quotes
$stmtQuotes = $db->prepare("SELECT * FROM quote_requests WHERE customer_id = ? ORDER BY id DESC");
$stmtQuotes->execute([$customerId]);
$myQuotes = $stmtQuotes->fetchAll();

// Customer Projects
$stmtProjects = $db->prepare("SELECT * FROM projects WHERE customer_id = ? ORDER BY id DESC");
$stmtProjects->execute([$customerId]);
$myProjects = $stmtProjects->fetchAll();

// Customer Enquiries
$stmtEnq = $db->prepare("SELECT * FROM enquiries WHERE customer_id = ? ORDER BY id DESC");
$stmtEnq->execute([$customerId]);
$myEnquiries = $stmtEnq->fetchAll();

$totalQuotesCount = count($myQuotes);
$totalProjectsCount = count($myProjects);
$totalEnquiriesCount = count($myEnquiries);
?>

<!-- Welcome Banner -->
<div class="card portal-card mb-4 bg-navy-dark border-gold">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="badge bg-warning text-dark font-heading fw-bold px-3 py-1 mb-2">WELCOME BACK</span>
            <h4 class="font-heading text-white fw-bold mb-1"><?= e($customer['full_name']) ?></h4>
            <p class="text-secondary small mb-0">Email: <?= e($customer['email']) ?> | Phone: <?= e($customer['phone']) ?></p>
        </div>
        <div>
            <a href="quotes.php?action=new" class="btn btn-gold font-heading fw-bold shadow"><i class="fas fa-plus-circle me-1"></i> Submit New Quote Request</a>
        </div>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card-gold">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-warning small font-heading fw-bold text-uppercase">MY QUOTE REQUESTS</span>
                <i class="fas fa-calculator text-warning fs-3"></i>
            </div>
            <div class="display-6 font-heading fw-bold text-white mb-2"><?= $totalQuotesCount ?></div>
            <div class="small text-muted">
                Track status, review quotations & approve proposals.
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card-gold">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-warning small font-heading fw-bold text-uppercase">MY ASSIGNED PROJECTS</span>
                <i class="fas fa-hard-hat text-warning fs-3"></i>
            </div>
            <div class="display-6 font-heading fw-bold text-white mb-2"><?= $totalProjectsCount ?></div>
            <div class="small text-muted">
                Live construction progress, materials & milestones.
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card-gold">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-warning small font-heading fw-bold text-uppercase">MY ENQUIRIES</span>
                <i class="fas fa-comments text-warning fs-3"></i>
            </div>
            <div class="display-6 font-heading fw-bold text-white mb-2"><?= $totalEnquiriesCount ?></div>
            <div class="small text-muted">
                General consultations & support tickets.
            </div>
        </div>
    </div>
</div>

<!-- Tables Grid -->
<div class="row g-4">
    <!-- Recent Quotes -->
    <div class="col-lg-7">
        <div class="card portal-card">
            <div class="portal-card-header d-flex justify-content-between align-items-center">
                <h5 class="font-heading text-white mb-0"><i class="fas fa-file-invoice text-warning me-2"></i> Recent Quote Requests</h5>
                <a href="quotes.php" class="btn btn-outline-gold btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($myQuotes)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-calculator fs-1 mb-2 text-secondary opacity-50"></i>
                        <p class="mb-2">No quote requests submitted yet.</p>
                        <a href="quotes.php?action=new" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> Request Your First Quote</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Service</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Quote Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($myQuotes, 0, 5) as $q): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-white"><?= e($q['service_category'] ?: 'General') ?></div>
                                            <small class="text-muted"><?= e($q['project_type'] ?: 'Standard') ?></small>
                                        </td>
                                        <td><small class="text-warning"><?= e($q['estimated_budget'] ?: 'N/A') ?></small></td>
                                        <td><?= renderStatusBadge($q['status']) ?></td>
                                        <td>
                                            <?php if ($q['quotation_amount']): ?>
                                                <span class="fw-bold text-success"><?= formatCurrency($q['quotation_amount']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">Awaiting Review</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?= date('d M Y', strtotime($q['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="col-lg-5">
        <div class="card portal-card">
            <div class="portal-card-header d-flex justify-content-between align-items-center">
                <h5 class="font-heading text-white mb-0"><i class="fas fa-building text-warning me-2"></i> My Projects</h5>
                <a href="projects.php" class="btn btn-outline-gold btn-sm">View All</a>
            </div>
            <div class="card-body p-3">
                <?php if (empty($myProjects)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-hard-hat fs-1 mb-2 text-secondary opacity-50"></i>
                        <p class="mb-0">No projects currently assigned to your account.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach (array_slice($myProjects, 0, 3) as $proj): ?>
                            <div class="p-3 bg-dark rounded-3 border border-secondary border-opacity-25">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="font-heading text-warning fw-bold mb-0"><?= e($proj['title']) ?></h6>
                                    <?= renderStatusBadge($proj['status']) ?>
                                </div>
                                <div class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($proj['location']) ?></div>
                                <p class="small text-light mb-0"><?= e(mb_strimwidth($proj['short_description'], 0, 80, '...')) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/customer-footer.php'; ?>
