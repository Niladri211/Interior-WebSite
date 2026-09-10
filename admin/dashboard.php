<?php
/**
 * Admin Dashboard - Raman Group Management Portal
 */
$adminPageTitle = "Executive Dashboard Overview";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

// Statistics Queries
$totalCustomers = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();

$totalProjects = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$constructionProjects = $db->query("SELECT COUNT(*) FROM projects WHERE category_id = 1")->fetchColumn();
$interiorProjects = $db->query("SELECT COUNT(*) FROM projects WHERE category_id = 2")->fetchColumn();
$fabricationProjects = $db->query("SELECT COUNT(*) FROM projects WHERE category_id = 3")->fetchColumn();

$totalEnquiries = $db->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
$newEnquiries = $db->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();

$totalQuotes = $db->query("SELECT COUNT(*) FROM quote_requests")->fetchColumn();
$pendingQuotes = $db->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'Pending'")->fetchColumn();

$totalTestimonials = $db->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
$totalGallery = $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn();

// Fetch Recent 5 Enquiries
$stmtRecentEnq = $db->query("SELECT * FROM enquiries ORDER BY id DESC LIMIT 5");
$recentEnquiries = $stmtRecentEnq->fetchAll();

// Fetch Recent 5 Quote Requests
$stmtRecentQuote = $db->query("SELECT * FROM quote_requests ORDER BY id DESC LIMIT 5");
$recentQuotes = $stmtRecentQuote->fetchAll();

// Fetch Recent 5 Customers
$stmtRecentCust = $db->query("SELECT * FROM customers ORDER BY id DESC LIMIT 5");
$recentCustomers = $stmtRecentCust->fetchAll();
?>

<!-- Stat Cards Grid -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="small text-muted font-heading fw-bold text-uppercase">Total Customers</div>
                    <div class="display-6 font-heading fw-bold text-dark mt-1"><?= $totalCustomers ?></div>
                </div>
                <div class="stat-icon-wrapper stat-icon-gold"><i class="fas fa-users"></i></div>
            </div>
            <div class="small text-muted">
                <a href="customers.php" class="text-warning fw-bold text-decoration-none">Manage Customer Accounts <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="small text-muted font-heading fw-bold text-uppercase">Quote Requests</div>
                    <div class="display-6 font-heading fw-bold text-dark mt-1"><?= $totalQuotes ?></div>
                </div>
                <div class="stat-icon-wrapper stat-icon-purple"><i class="fas fa-calculator"></i></div>
            </div>
            <div class="small text-muted">
                <span class="badge bg-danger me-1"><?= $pendingQuotes ?> Pending</span> Quotation Action Required
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="small text-muted font-heading fw-bold text-uppercase">Total Projects</div>
                    <div class="display-6 font-heading fw-bold text-dark mt-1"><?= $totalProjects ?></div>
                </div>
                <div class="stat-icon-wrapper stat-icon-blue"><i class="fas fa-building"></i></div>
            </div>
            <div class="small text-muted">
                <span class="text-warning fw-bold"><?= $constructionProjects ?></span> Const. | 
                <span class="text-primary fw-bold"><?= $interiorProjects ?></span> Int. | 
                <span class="text-info fw-bold"><?= $fabricationProjects ?></span> Fab.
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="small text-muted font-heading fw-bold text-uppercase">Enquiries & Messages</div>
                    <div class="display-6 font-heading fw-bold text-dark mt-1"><?= $totalEnquiries ?></div>
                </div>
                <div class="stat-icon-wrapper stat-icon-green"><i class="fas fa-inbox"></i></div>
            </div>
            <div class="small text-muted">
                <span class="badge bg-warning text-dark me-1"><?= $newEnquiries ?> New</span> Unread Enquiries
            </div>
        </div>
    </div>
</div>

<!-- Recent Leads & Enquiries Section -->
<div class="row g-4">
    <!-- Recent Quotations -->
    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h5 class="font-heading fw-bold mb-0 text-dark"><i class="fas fa-file-invoice text-warning me-2"></i> Quote Requests</h5>
                <a href="quotes.php" class="btn btn-sm btn-outline-dark">View All Quotes</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentQuotes as $q): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($q['full_name']) ?></div>
                                    <small class="text-muted"><?= e($q['email']) ?></small>
                                </td>
                                <td><small class="fw-bold text-success"><?= e($q['estimated_budget'] ?: 'N/A') ?></small></td>
                                <td><?= renderStatusBadge($q['status']) ?></td>
                                <td>
                                    <a href="quotes.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-gold btn-action" title="Manage Quote"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Customers -->
    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h5 class="font-heading fw-bold mb-0 text-dark"><i class="fas fa-users text-warning me-2"></i> Registered Customers</h5>
                <a href="customers.php" class="btn btn-sm btn-outline-dark">View All Customers</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCustomers as $c): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($c['full_name']) ?></div>
                                    <small class="text-muted"><?= e($c['email']) ?></small>
                                </td>
                                <td><small class="text-dark fw-bold"><?= e($c['phone']) ?></small></td>
                                <td class="small text-muted"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                                <td>
                                    <a href="customers.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-user-gear"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
