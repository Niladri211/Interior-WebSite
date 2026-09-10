<?php
/**
 * Customer Enquiries Management
 * Raman Group Platform
 */
$customerPageTitle = "My Enquiries & Consultations";
require_once __DIR__ . '/includes/customer-header.php';

$db = getDB();
$customerId = $_SESSION['customer_id'];

// Fetch enquiries linked to customer
$stmt = $db->prepare("SELECT * FROM enquiries WHERE customer_id = ? ORDER BY id DESC");
$stmt->execute([$customerId]);
$enquiries = $stmt->fetchAll();
?>

<div class="mb-4">
    <h4 class="font-heading text-white fw-bold mb-1">General Enquiries & Support Tickets</h4>
    <p class="text-secondary small">View messages and general consultations sent to the Raman Group team.</p>
</div>

<div class="card portal-card">
    <div class="card-body p-0">
        <?php if (empty($enquiries)): ?>
            <div class="p-5 text-center text-muted">
                <i class="fas fa-comments fs-1 mb-3 text-secondary opacity-50"></i>
                <h5 class="text-white">No Enquiries Found</h5>
                <p class="text-muted">You haven't submitted any general contact enquiries yet.</p>
                <a href="../contact.php" target="_blank" class="btn btn-gold btn-sm"><i class="fas fa-paper-plane me-1"></i> Contact Us</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Enquiry ID</th>
                            <th>Category</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Admin Response</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enquiries as $e): ?>
                            <tr>
                                <td><span class="fw-bold text-warning">#ENQ-<?= $e['id'] ?></span></td>
                                <td>
                                    <span class="badge bg-light text-dark fw-bold"><?= e($e['service_category'] ?: 'General') ?></span>
                                </td>
                                <td>
                                    <div class="small text-light max-w-400"><?= e(mb_strimwidth($e['message'], 0, 100, '...')) ?></div>
                                </td>
                                <td><?= renderStatusBadge($e['status']) ?></td>
                                <td>
                                    <small class="text-info"><?= e($e['admin_notes'] ?: 'Pending response') ?></small>
                                </td>
                                <td class="small text-muted"><?= date('d M Y', strtotime($e['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/customer-footer.php'; ?>
