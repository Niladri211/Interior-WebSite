<?php
/**
 * Admin Service Management Module
 * Raman Group
 */
$adminPageTitle = "Service Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Categories
$categories = $db->query("SELECT * FROM service_categories ORDER BY sort_order ASC")->fetchAll();

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'Service deleted successfully.');
    } else {
        setFlash('danger', 'Security validation failed.');
    }
    header("Location: services.php");
    exit;
}

// Handle Add/Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security token mismatch.');
    } else {
        $categoryId = (int)$_POST['category_id'];
        $title = sanitize($_POST['title'] ?? '');
        $slug = slugify($_POST['slug'] ?: $title);
        $shortDesc = sanitize($_POST['short_description'] ?? '');
        $detailedDesc = sanitize($_POST['detailed_description'] ?? '');
        $features = sanitize($_POST['features'] ?? '');
        $benefits = sanitize($_POST['benefits'] ?? '');
        $icon = sanitize($_POST['icon'] ?? 'fa-tools');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $existingImagePath = sanitize($_POST['existing_image'] ?? '');

        // Image Upload
        $imagePath = $existingImagePath;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = handleFileUpload($_FILES['image'], 'services');
            if ($up['success']) {
                $imagePath = $up['filepath'];
            }
        }

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE services SET category_id = :cat, title = :title, slug = :slug, short_description = :sdesc, detailed_description = :ddesc, features = :feat, benefits = :ben, icon = :icon, image = :img, is_featured = :feat_flag, is_active = :act_flag WHERE id = :id");
            $stmt->execute([
                ':cat' => $categoryId,
                ':title' => $title,
                ':slug' => $slug,
                ':sdesc' => $shortDesc,
                ':ddesc' => $detailedDesc,
                ':feat' => $features,
                ':ben' => $benefits,
                ':icon' => $icon,
                ':img' => $imagePath,
                ':feat_flag' => $isFeatured,
                ':act_flag' => $isActive,
                ':id' => $editId
            ]);
            setFlash('success', 'Service updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO services (category_id, title, slug, short_description, detailed_description, features, benefits, icon, image, is_featured, is_active) VALUES (:cat, :title, :slug, :sdesc, :ddesc, :feat, :ben, :icon, :img, :feat_flag, :act_flag)");
            $stmt->execute([
                ':cat' => $categoryId,
                ':title' => $title,
                ':slug' => $slug,
                ':sdesc' => $shortDesc,
                ':ddesc' => $detailedDesc,
                ':feat' => $features,
                ':ben' => $benefits,
                ':icon' => $icon,
                ':img' => $imagePath,
                ':feat_flag' => $isFeatured,
                ':act_flag' => $isActive
            ]);
            setFlash('success', 'New service added successfully.');
        }

        header("Location: services.php");
        exit;
    }
}

// Fetch single service for edit mode
$serviceData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $serviceData = $stmt->fetch();
}

// Fetch Services List
$stmtServices = $db->query("SELECT s.*, c.name as category_name FROM services s JOIN service_categories c ON s.category_id = c.id ORDER BY s.category_id ASC, s.id ASC");
$servicesList = $stmtServices->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Service Add/Edit Form -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0"><?= $editId > 0 ? 'Edit Service Details' : 'Add New Service' ?></h4>
        <a href="services.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="services.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST" enctype="multipart/form-data">
            <?= getCSRFInput() ?>
            <input type="hidden" name="existing_image" value="<?= e($serviceData['image'] ?? '') ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Service Category *</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($serviceData['category_id']) && $serviceData['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Service Title *</label>
                    <input type="text" name="title" class="form-control" value="<?= e($serviceData['title'] ?? '') ?>" required placeholder="e.g. Structural Works">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">URL Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= e($serviceData['slug'] ?? '') ?>" placeholder="structural-works (auto-generated if empty)">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">FontAwesome Icon Class</label>
                    <input type="text" name="icon" class="form-control" value="<?= e($serviceData['icon'] ?? 'fa-tools') ?>" placeholder="fa-hard-hat">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Short Description *</label>
                    <textarea name="short_description" rows="2" class="form-control" required><?= e($serviceData['short_description'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Detailed Description</label>
                    <textarea name="detailed_description" rows="5" class="form-control"><?= e($serviceData['detailed_description'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Features (Semicolon ';' separated)</label>
                    <textarea name="features" rows="3" class="form-control" placeholder="High-grade M30 concrete; Seismic compliant; 10-year warranty"><?= e($serviceData['features'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Benefits (Semicolon ';' separated)</label>
                    <textarea name="benefits" rows="3" class="form-control" placeholder="Zero delay guarantee; Fixed rate card; Dedicated site engineer"><?= e($serviceData['benefits'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Service Banner Image</label>
                    <input type="file" name="image" class="form-control image-upload-preview" data-preview="serviceImgPreview">
                    <?php if (!empty($serviceData['image'])): ?>
                        <div class="mt-2">
                            <img id="serviceImgPreview" src="<?= getImageUrl($serviceData['image'], 'Service', 'Service') ?>" style="max-height: 100px; border-radius: 8px;">
                        </div>
                    <?php else: ?>
                        <img id="serviceImgPreview" style="max-height: 100px; display:none; border-radius: 8px;" class="mt-2">
                    <?php endif; ?>
                </div>

                <div class="col-md-6 d-flex align-items-center gap-4 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" <?= (!isset($serviceData['is_featured']) || $serviceData['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isFeatured">Featured on Homepage</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" <?= (!isset($serviceData['is_active']) || $serviceData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isActive">Active Status</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4"><i class="fas fa-save me-1"></i> Save Service</button>
                    <a href="services.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- Services List View -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0">Services Directory (<?= count($servicesList) ?>)</h4>
            <small class="text-muted">Manage service offerings across Construction, Interior, and Fabrication verticals.</small>
        </div>
        <a href="services.php?action=add" class="btn btn-gold btn-sm"><i class="fas fa-plus me-1"></i> Add New Service</a>
    </div>

    <div class="admin-table-card">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <input type="text" id="adminTableSearch" class="form-control form-control-sm w-auto" placeholder="Filter services...">
        </div>
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Vertical</th>
                        <th>Service Title</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicesList as $s): ?>
                        <tr>
                            <td><span class="badge bg-dark text-warning"><?= e($s['category_name']) ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas <?= e($s['icon']) ?> text-warning"></i>
                                    <strong class="text-dark"><?= e($s['title']) ?></strong>
                                </div>
                            </td>
                            <td>
                                <?= $s['is_featured'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                            </td>
                            <td>
                                <?= $s['is_active'] ? '<span class="badge bg-primary">Active</span>' : '<span class="badge bg-danger">Disabled</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="services.php?action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-dark btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="services.php?action=delete&id=<?= $s['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
