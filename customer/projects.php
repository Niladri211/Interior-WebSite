<?php
/**
 * Customer Assigned Projects View
 * Raman Group Platform
 */
$customerPageTitle = "My Assigned Projects";
require_once __DIR__ . '/includes/customer-header.php';

$db = getDB();
$customerId = $_SESSION['customer_id'];

// Fetch projects assigned to this customer
$stmt = $db->prepare("
    SELECT p.*, sc.name as category_name 
    FROM projects p 
    LEFT JOIN service_categories sc ON p.category_id = sc.id 
    WHERE p.customer_id = ? 
    ORDER BY p.id DESC
");
$stmt->execute([$customerId]);
$projects = $stmt->fetchAll();
?>

<div class="mb-4">
    <h4 class="font-heading text-white fw-bold mb-1">My Construction & Interior Projects</h4>
    <p class="text-secondary small">Real-time status tracking for projects registered under your customer account.</p>
</div>

<?php if (empty($projects)): ?>
    <div class="card portal-card p-5 text-center text-muted">
        <i class="fas fa-hard-hat fs-1 mb-3 text-secondary opacity-50"></i>
        <h5 class="text-white">No Assigned Projects Yet</h5>
        <p class="text-muted">Once your quote request is approved and converted into an active build by Raman Group, your project will appear here.</p>
        <div class="mt-2">
            <a href="quotes.php" class="btn btn-gold btn-sm"><i class="fas fa-calculator me-1"></i> Check Quote Status</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($projects as $p): ?>
            <div class="col-lg-6">
                <div class="card portal-card border-gold h-100 overflow-hidden">
                    <div class="position-relative" style="height: 220px;">
                        <img src="<?= getImageUrl($p['featured_image'], $p['title'], $p['category_name']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= e($p['title']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= getFallbackSvg($p['title'], $p['category_name']) ?>';">
                        <span class="position-absolute top-0 end-0 m-3"><?= renderStatusBadge($p['status']) ?></span>
                        <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark border border-warning text-warning font-heading"><?= e($p['category_name']) ?></span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="font-heading text-warning fw-bold mb-2"><?= e($p['title']) ?></h4>
                        
                        <div class="d-flex flex-wrap gap-3 small text-muted mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                            <span><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($p['location']) ?></span>
                            <?php if ($p['start_date']): ?>
                                <span><i class="fas fa-calendar-alt text-warning me-1"></i> Start: <?= date('d M Y', strtotime($p['start_date'])) ?></span>
                            <?php endif; ?>
                            <?php if ($p['budget']): ?>
                                <span><i class="fas fa-wallet text-warning me-1"></i> Budget: <?= formatCurrency($p['budget']) ?></span>
                            <?php endif; ?>
                        </div>

                        <p class="text-light small flex-grow-1 mb-3"><?= e($p['short_description']) ?></p>

                        <?php if ($p['scope_of_work']): ?>
                            <div class="mb-3 p-3 bg-dark rounded-3 border border-secondary border-opacity-25">
                                <small class="text-warning fw-bold d-block mb-1"><i class="fas fa-tasks me-1"></i> Scope of Work:</small>
                                <small class="text-muted"><?= e($p['scope_of_work']) ?></small>
                            </div>
                        <?php endif; ?>

                        <div class="mt-auto">
                            <a href="../project-detail.php?id=<?= $p['id'] ?>" target="_blank" class="btn btn-outline-gold btn-sm w-100"><i class="fas fa-external-link-alt me-1"></i> View Full Project Blueprint & Gallery</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/customer-footer.php'; ?>
