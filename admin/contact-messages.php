<?php
/**
 * Admin Contact Messages & Enquiries Lead Management
 * Raman Group Management Portal
 */
$adminPageTitle = "Contact Messages & Enquiries";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $enqId = (int)$_POST['enquiry_id'];
        $newStatus = sanitize($_POST['status'] ?? 'New');
        $adminNotes = sanitize($_POST['admin_notes'] ?? '');

        $stmtUp = $db->prepare("UPDATE enquiries SET status = :st, admin_notes = :notes WHERE id = :id");
        $stmtUp->execute([':st' => $newStatus, ':notes' => $adminNotes, ':id' => $enqId]);
        setFlash('success', 'Enquiry status updated successfully.');
    }
    header("Location: contact-messages.php");
    exit;
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $delId = (int)$_GET['id'];
        $stmtDel = $db->prepare("DELETE FROM enquiries WHERE id = :id");
        $stmtDel->execute([':id' => $delId]);
        setFlash('success', 'Enquiry lead deleted.');
    }
    header("Location: contact-messages.php");
    exit;
}

// Fetch Enquiries List
$stmtEnq = $db->query("SELECT * FROM enquiries ORDER BY id DESC");
$enquiriesList = $stmtEnq->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-heading fw-bold mb-0 text-dark">Website Lead Enquiries & Contact Messages (<?= count($enquiriesList) ?>)</h4>
        <small class="text-muted">Manage customer enquiries, consultations, and respond to support messages.</small>
    </div>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Ref # & Client Info</th>
                    <th>Vertical & Type</th>
                    <th>Budget Range</th>
                    <th>Preferred Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enquiriesList)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fs-2 mb-2 d-block text-secondary"></i>
                            No enquiries or contact messages found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($enquiriesList as $enq): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">#ENQ-<?= $enq['id'] ?> - <?= e($enq['full_name']) ?></div>
                                <small class="text-muted"><i class="fas fa-phone-alt me-1"></i> <a href="tel:<?= e($enq['phone']) ?>"><?= e($enq['phone']) ?></a></small><br>
                                <small class="text-muted"><i class="fas fa-envelope me-1"></i> <a href="mailto:<?= e($enq['email']) ?>"><?= e($enq['email']) ?></a></small>
                            </td>
                            <td>
                                <span class="badge bg-dark text-warning"><?= e($enq['service_category'] ?: 'General') ?></span><br>
                                <small class="text-muted"><?= e($enq['project_type'] ?: 'N/A') ?></small>
                            </td>
                            <td><small class="fw-bold text-success"><?= e($enq['budget_range'] ?: 'N/A') ?></small></td>
                            <td><small class="text-muted"><?= $enq['preferred_contact_date'] ? date('d M Y', strtotime($enq['preferred_contact_date'])) : 'Immediate' ?></small></td>
                            <td><?= renderStatusBadge($enq['status']) ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-dark btn-action" data-bs-toggle="modal" data-bs-target="#enqModal<?= $enq['id'] ?>" title="View & Update"><i class="fas fa-edit"></i></button>
                                <a href="contact-messages.php?action=delete&id=<?= $enq['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Delete enquiry lead #ENQ-<?= $enq['id'] ?>?');" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Enquiry Detail & Update Modal -->
                        <div class="modal fade text-start" id="enqModal<?= $enq['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title font-heading fw-bold text-warning"><i class="fas fa-inbox me-2"></i> Lead Details #ENQ-<?= $enq['id'] ?>: <?= e($enq['full_name']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="contact-messages.php" method="POST">
                                        <?= getCSRFInput() ?>
                                        <input type="hidden" name="enquiry_id" value="<?= $enq['id'] ?>">
                                        <input type="hidden" name="update_status" value="1">

                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <strong>Client Name:</strong> <?= e($enq['full_name']) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Email:</strong> <?= e($enq['email']) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Phone:</strong> <?= e($enq['phone']) ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Location:</strong> <?= e($enq['location'] ?: 'N/A') ?>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <strong class="font-heading">Client Message:</strong>
                                                <div class="p-3 bg-light rounded border mt-1 small"><?= nl2br(e($enq['message'])) ?></div>
                                            </div>

                                            <hr>

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label font-heading fw-bold">Update Lead Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="New" <?= $enq['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                                        <option value="Contacted" <?= $enq['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                                        <option value="In Discussion" <?= $enq['status'] === 'In Discussion' ? 'selected' : '' ?>>In Discussion</option>
                                                        <option value="Quotation Sent" <?= $enq['status'] === 'Quotation Sent' ? 'selected' : '' ?>>Quotation Sent</option>
                                                        <option value="Converted" <?= $enq['status'] === 'Converted' ? 'selected' : '' ?>>Converted</option>
                                                        <option value="Closed" <?= $enq['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label font-heading fw-bold">Admin Internal Notes / Reply</label>
                                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Enter follow-up notes, phone call summaries, or customer responses..."><?= e($enq['admin_notes']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-gold font-heading fw-bold">Save Status & Notes</button>
                                        </div>
                                    </form>
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
