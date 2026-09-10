<?php
/**
 * Admin FAQ Management Module
 * Raman Group
 */
$adminPageTitle = "FAQ Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$categories = $db->query("SELECT * FROM service_categories ORDER BY sort_order ASC")->fetchAll();

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'FAQ deleted.');
    }
    header("Location: faqs.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation failed.');
    } else {
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $question = sanitize($_POST['question'] ?? '');
        $answer = sanitize($_POST['answer'] ?? '');
        $sortOrder = (int)$_POST['sort_order'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE faqs SET category_id = :cat, question = :q, answer = :a, sort_order = :sort, is_active = :act WHERE id = :id");
            $stmt->execute([':cat' => $categoryId, ':q' => $question, ':a' => $answer, ':sort' => $sortOrder, ':act' => $isActive, ':id' => $editId]);
            setFlash('success', 'FAQ updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO faqs (category_id, question, answer, sort_order, is_active) VALUES (:cat, :q, :a, :sort, :act)");
            $stmt->execute([':cat' => $categoryId, ':q' => $question, ':a' => $answer, ':sort' => $sortOrder, ':act' => $isActive]);
            setFlash('success', 'New FAQ added.');
        }

        header("Location: faqs.php");
        exit;
    }
}

// Fetch single FAQ
$faqData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM faqs WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $faqData = $stmt->fetch();
}

$faqsList = $db->query("SELECT f.*, c.name as category_name FROM faqs f LEFT JOIN service_categories c ON f.category_id = c.id ORDER BY f.sort_order ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0"><?= $editId > 0 ? 'Edit FAQ' : 'Add FAQ' ?></h4>
        <a href="faqs.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to FAQs</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="faqs.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST">
            <?= getCSRFInput() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Category (Optional)</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- General FAQ --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($faqData['category_id']) && $faqData['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= e($faqData['sort_order'] ?? '1') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Question *</label>
                    <input type="text" name="question" class="form-control" value="<?= e($faqData['question'] ?? '') ?>" required placeholder="e.g. What structural warranty do you offer?">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Answer *</label>
                    <textarea name="answer" rows="4" class="form-control" required><?= e($faqData['answer'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqAct" <?= (!isset($faqData['is_active']) || $faqData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="faqAct">Active Visibility</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4"><i class="fas fa-save me-1"></i> Save FAQ</button>
                    <a href="faqs.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0">FAQs Directory (<?= count($faqsList) ?>)</h4>
            <small class="text-muted">Manage questions and answers displayed in accordion lists.</small>
        </div>
        <a href="faqs.php?action=add" class="btn btn-gold btn-sm"><i class="fas fa-plus me-1"></i> Add New FAQ</a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Question</th>
                        <th>Answer Preview</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqsList as $f): ?>
                        <tr>
                            <td><span class="badge bg-dark text-warning"><?= e($f['category_name'] ?: 'General') ?></span></td>
                            <td><strong class="text-dark"><?= e($f['question']) ?></strong></td>
                            <td><small class="text-muted"><?= e(mb_strimwidth($f['answer'], 0, 90, '...')) ?></small></td>
                            <td class="text-end">
                                <a href="faqs.php?action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-dark btn-action"><i class="fas fa-edit"></i></a>
                                <a href="faqs.php?action=delete&id=<?= $f['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
