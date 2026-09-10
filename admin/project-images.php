<?php
/**
 * Admin Project Additional Images Manager
 * Raman Group
 */
$adminPageTitle = "Project Gallery Images";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$projectId = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 0;

if ($projectId <= 0) {
    header("Location: projects.php");
    exit;
}

// Fetch Project Details
$stmtP = $db->prepare("SELECT * FROM projects WHERE id = :id");
$stmtP->execute([':id' => $projectId]);
$project = $stmtP->fetch();

if (!$project) {
    header("Location: projects.php");
    exit;
}

// Handle Delete Image
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['img_id'])) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $imgId = (int)$_GET['img_id'];
        $stmtDel = $db->prepare("DELETE FROM project_images WHERE id = :id AND project_id = :pid");
        $stmtDel->execute([':id' => $imgId, ':pid' => $projectId]);
        setFlash('success', 'Image removed from project gallery.');
    }
    header("Location: project-images.php?project_id=" . $projectId);
    exit;
}

// Handle Upload New Image
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation failed.');
    } else {
        $caption = sanitize($_POST['caption'] ?? '');
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handleFileUpload($_FILES['image'], 'projects');
            if ($up['success']) {
                $stmtIns = $db->prepare("INSERT INTO project_images (project_id, image_path, caption) VALUES (:pid, :img, :cap)");
                $stmtIns->execute([':pid' => $projectId, ':img' => $up['filepath'], ':cap' => $caption]);
                setFlash('success', 'New image added to project gallery.');
            } else {
                setFlash('danger', $up['error']);
            }
        } else {
            setFlash('danger', 'Please select an image file to upload.');
        }
        header("Location: project-images.php?project_id=" . $projectId);
        exit;
    }
}

// Fetch Gallery Images
$stmtImgs = $db->prepare("SELECT * FROM project_images WHERE project_id = :pid ORDER BY id DESC");
$stmtImgs->execute([':pid' => $projectId]);
$imagesList = $stmtImgs->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-heading fw-bold mb-0">Gallery Images: <?= e($project['title']) ?></h4>
        <small class="text-muted">Manage additional high-resolution images for this project showcase.</small>
    </div>
    <a href="projects.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Projects</a>
</div>

<!-- Upload New Image Form -->
<div class="admin-table-card p-4 mb-4">
    <h5 class="font-heading fw-bold mb-3"><i class="fas fa-upload text-warning me-2"></i> Upload Gallery Image</h5>
    <form action="project-images.php?project_id=<?= $projectId ?>" method="POST" enctype="multipart/form-data">
        <?= getCSRFInput() ?>
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold small">Select Image File *</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold small">Caption / Title (Optional)</label>
                <input type="text" name="caption" class="form-control" placeholder="e.g. Master Bedroom View">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-gold w-100"><i class="fas fa-plus me-1"></i> Upload</button>
            </div>
        </div>
    </form>
</div>

<!-- Existing Images Grid -->
<div class="row g-4">
    <?php if (empty($imagesList)): ?>
        <div class="col-12 text-center py-5 bg-white rounded-4 border">
            <i class="fas fa-images text-muted display-3 mb-3"></i>
            <h5>No Additional Images Uploaded</h5>
            <p class="text-muted small">Upload secondary photos above to display inside the project details lightbox.</p>
        </div>
    <?php else: ?>
        <?php foreach ($imagesList as $img): ?>
            <div class="col-md-3 col-6">
                <div class="bg-white border rounded-4 overflow-hidden shadow-sm h-100">
                    <div style="height: 160px; overflow:hidden;">
                        <img src="<?= getImageUrl($img['image_path'], $img['caption'] ?: 'Gallery Image', 'Project') ?>" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted text-truncate"><?= e($img['caption'] ?: 'No Caption') ?></small>
                        <a href="project-images.php?project_id=<?= $projectId ?>&action=delete&img_id=<?= $img['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
