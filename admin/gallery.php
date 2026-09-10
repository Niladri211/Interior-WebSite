<?php
/**
 * Admin Photo Gallery Management Module
 * Raman Group
 */
$adminPageTitle = "Gallery Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$categories = $db->query("SELECT * FROM service_categories ORDER BY sort_order ASC")->fetchAll();

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'Gallery item deleted successfully.');
    }
    header("Location: gallery.php");
    exit;
}

// Handle Add/Edit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation failed.');
    } else {
        $categoryId = (int)$_POST['category_id'];
        $title = sanitize($_POST['title'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $existingImg = sanitize($_POST['existing_image'] ?? '');

        $imagePath = $existingImg;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handleFileUpload($_FILES['image'], 'gallery');
            if ($up['success']) {
                $imagePath = $up['filepath'];
            }
        }

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE gallery SET category_id = :cat, title = :title, location = :loc, image_path = :img, is_active = :act WHERE id = :id");
            $stmt->execute([':cat' => $categoryId, ':title' => $title, ':loc' => $location, ':img' => $imagePath, ':act' => $isActive, ':id' => $editId]);
            setFlash('success', 'Gallery item updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO gallery (category_id, title, location, image_path, is_active) VALUES (:cat, :title, :loc, :img, :act)");
            $stmt->execute([':cat' => $categoryId, ':title' => $title, ':loc' => $location, ':img' => $imagePath, ':act' => $isActive]);
            setFlash('success', 'New gallery photo added.');
        }

        header("Location: gallery.php");
        exit;
    }
}

// Fetch single gallery item
$galleryData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $galleryData = $stmt->fetch();
}

// Fetch Gallery List
$stmtGallery = $db->query("SELECT g.*, c.name as category_name FROM gallery g JOIN service_categories c ON g.category_id = c.id ORDER BY g.id DESC");
$galleryList = $stmtGallery->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0"><?= $editId > 0 ? 'Edit Gallery Photo' : 'Upload New Gallery Photo' ?></h4>
        <a href="gallery.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Gallery</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="gallery.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST" enctype="multipart/form-data">
            <?= getCSRFInput() ?>
            <input type="hidden" name="existing_image" value="<?= e($galleryData['image_path'] ?? '') ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($galleryData['category_id']) && $galleryData['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Photo Title / Caption *</label>
                    <input type="text" name="title" class="form-control" value="<?= e($galleryData['title'] ?? '') ?>" required placeholder="e.g. Modern Island Kitchen">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= e($galleryData['location'] ?? '') ?>" placeholder="e.g. Noida">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Photo File *</label>
                    <input type="file" name="image" class="form-control image-upload-preview" data-preview="galImgPrev" <?= $editId > 0 ? '' : 'required' ?>>
                    <?php if (!empty($galleryData['image_path'])): ?>
                        <img id="galImgPrev" src="<?= getImageUrl($galleryData['image_path'], 'Gallery', 'Gallery') ?>" style="max-height: 100px; border-radius: 8px;" class="mt-2">
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="gActive" <?= (!isset($galleryData['is_active']) || $galleryData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="gActive">Active Visibility</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4"><i class="fas fa-save me-1"></i> Save Gallery Item</button>
                    <a href="gallery.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0">Gallery Items (<?= count($galleryList) ?>)</h4>
            <small class="text-muted">Manage high-resolution showcase photos for public Lightbox viewing.</small>
        </div>
        <a href="gallery.php?action=add" class="btn btn-gold btn-sm"><i class="fas fa-plus me-1"></i> Upload New Photo</a>
    </div>

    <div class="row g-4">
        <?php foreach ($galleryList as $g): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="bg-white border rounded-4 overflow-hidden shadow-sm h-100">
                    <div style="height: 180px; overflow:hidden;">
                        <img src="<?= getImageUrl($g['image_path'], $g['title'], $g['category_name']) ?>" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="p-3">
                        <span class="badge bg-dark text-warning small mb-1"><?= e($g['category_name']) ?></span>
                        <h6 class="font-heading fw-bold text-dark mb-1 text-truncate"><?= e($g['title']) ?></h6>
                        <small class="text-muted d-block mb-3"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($g['location']) ?></small>
                        <div class="d-flex justify-content-between">
                            <a href="gallery.php?action=edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-dark btn-action"><i class="fas fa-edit"></i></a>
                            <a href="gallery.php?action=delete&id=<?= $g['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
