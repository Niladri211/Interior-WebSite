<?php
/**
 * Admin Testimonials Management Module
 * Raman Group
 */
$adminPageTitle = "Testimonials Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM testimonials WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'Testimonial deleted successfully.');
    }
    header("Location: testimonials.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation failed.');
    } else {
        $clientName = sanitize($_POST['client_name'] ?? '');
        $clientTitle = sanitize($_POST['client_title'] ?? '');
        $company = sanitize($_POST['company'] ?? '');
        $rating = (int)$_POST['rating'];
        $content = sanitize($_POST['content'] ?? '');
        $projectType = sanitize($_POST['project_type'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE testimonials SET client_name = :name, client_title = :title, company = :comp, rating = :rat, content = :content, project_type = :ptype, is_featured = :feat, is_active = :act WHERE id = :id");
            $stmt->execute([':name' => $clientName, ':title' => $clientTitle, ':comp' => $company, ':rat' => $rating, ':content' => $content, ':ptype' => $projectType, ':feat' => $isFeatured, ':act' => $isActive, ':id' => $editId]);
            setFlash('success', 'Testimonial updated.');
        } else {
            $stmt = $db->prepare("INSERT INTO testimonials (client_name, client_title, company, rating, content, project_type, is_featured, is_active) VALUES (:name, :title, :comp, :rat, :content, :ptype, :feat, :act)");
            $stmt->execute([':name' => $clientName, ':title' => $clientTitle, ':comp' => $company, ':rat' => $rating, ':content' => $content, ':ptype' => $projectType, ':feat' => $isFeatured, ':act' => $isActive]);
            setFlash('success', 'New testimonial added.');
        }

        header("Location: testimonials.php");
        exit;
    }
}

// Fetch single testimonial
$testData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $testData = $stmt->fetch();
}

// Fetch Testimonials
$testimonialsList = $db->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0"><?= $editId > 0 ? 'Edit Testimonial' : 'Add Testimonial' ?></h4>
        <a href="testimonials.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Testimonials</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="testimonials.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST">
            <?= getCSRFInput() ?>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Client Name *</label>
                    <input type="text" name="client_name" class="form-control" value="<?= e($testData['client_name'] ?? '') ?>" required placeholder="e.g. Rajesh Singhania">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Client Title / Designation</label>
                    <input type="text" name="client_title" class="form-control" value="<?= e($testData['client_title'] ?? '') ?>" placeholder="e.g. Managing Director">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Company Name</label>
                    <input type="text" name="company" class="form-control" value="<?= e($testData['company'] ?? '') ?>" placeholder="e.g. Singhania Logistics">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Project Category / Type</label>
                    <input type="text" name="project_type" class="form-control" value="<?= e($testData['project_type'] ?? '') ?>" placeholder="e.g. Industrial Steel Shed">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Star Rating (1 - 5)</label>
                    <select name="rating" class="form-select">
                        <option value="5" <?= (isset($testData['rating']) && $testData['rating'] == 5) ? 'selected' : '' ?>>5 Stars (Excellent)</option>
                        <option value="4" <?= (isset($testData['rating']) && $testData['rating'] == 4) ? 'selected' : '' ?>>4 Stars (Good)</option>
                        <option value="3" <?= (isset($testData['rating']) && $testData['rating'] == 3) ? 'selected' : '' ?>>3 Stars</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Review Content *</label>
                    <textarea name="content" rows="4" class="form-control" required><?= e($testData['content'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6 d-flex align-items-center gap-4 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="tFeat" <?= (!isset($testData['is_featured']) || $testData['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="tFeat">Featured on Homepage</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="tAct" <?= (!isset($testData['is_active']) || $testData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="tAct">Active Visibility</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4"><i class="fas fa-save me-1"></i> Save Testimonial</button>
                    <a href="testimonials.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0">Testimonials & Reviews (<?= count($testimonialsList) ?>)</h4>
            <small class="text-muted">Manage client feedback displayed across the website.</small>
        </div>
        <a href="testimonials.php?action=add" class="btn btn-gold btn-sm"><i class="fas fa-plus me-1"></i> Add Testimonial</a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Project Type</th>
                        <th>Rating</th>
                        <th>Review Snippet</th>
                        <th>Featured</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonialsList as $t): ?>
                        <tr>
                            <td>
                                <strong class="text-dark"><?= e($t['client_name']) ?></strong><br>
                                <small class="text-muted"><?= e($t['client_title']) ?> (<?= e($t['company']) ?>)</small>
                            </td>
                            <td><span class="badge bg-dark text-warning"><?= e($t['project_type'] ?: 'General') ?></span></td>
                            <td><?= renderRatingStars($t['rating']) ?></td>
                            <td><small class="text-muted"><?= e(mb_strimwidth($t['content'], 0, 80, '...')) ?></small></td>
                            <td><?= $t['is_featured'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                            <td class="text-end">
                                <a href="testimonials.php?action=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-dark btn-action"><i class="fas fa-edit"></i></a>
                                <a href="testimonials.php?action=delete&id=<?= $t['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
