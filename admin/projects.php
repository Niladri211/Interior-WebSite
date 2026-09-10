<?php
/**
 * Admin Project Management Module
 * Raman Group
 */
$adminPageTitle = "Project Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$categories = $db->query("SELECT * FROM service_categories ORDER BY sort_order ASC")->fetchAll();
$services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY title ASC")->fetchAll();
$customers = $db->query("SELECT id, full_name, email FROM customers ORDER BY full_name ASC")->fetchAll();

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'Project deleted successfully.');
    } else {
        setFlash('danger', 'Security token validation failed.');
    }
    header("Location: projects.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation error.');
    } else {
        $customerId = !empty($_POST['customer_id']) ? (int)$_POST['customer_id'] : null;
        $categoryId = (int)$_POST['category_id'];
        $serviceId = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
        $title = sanitize($_POST['title'] ?? '');
        $slug = slugify($_POST['slug'] ?: $title);
        $location = sanitize($_POST['location'] ?? '');
        $clientName = sanitize($_POST['client_name'] ?? '');
        $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $completionDate = !empty($_POST['completion_date']) ? $_POST['completion_date'] : null;
        $budget = !empty($_POST['budget']) ? (float)$_POST['budget'] : null;
        $status = sanitize($_POST['status'] ?? 'Completed');
        $shortDesc = sanitize($_POST['short_description'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $scopeOfWork = sanitize($_POST['scope_of_work'] ?? '');
        $materialsUsed = sanitize($_POST['materials_used'] ?? '');
        $highlights = sanitize($_POST['highlights'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $existingImg = sanitize($_POST['existing_image'] ?? '');

        // Image Upload
        $featuredImage = $existingImg;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $up = handleFileUpload($_FILES['featured_image'], 'projects');
            if ($up['success']) {
                $featuredImage = $up['filepath'];
            }
        }

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE projects SET customer_id = :cust, category_id = :cat, service_id = :serv, title = :title, slug = :slug, location = :loc, client_name = :client, start_date = :sdate, completion_date = :cdate, budget = :budget, status = :status, short_description = :sdesc, description = :desc, scope_of_work = :scope, materials_used = :mat, highlights = :high, featured_image = :img, is_featured = :feat, is_active = :act WHERE id = :id");
            $stmt->execute([
                ':cust' => $customerId,
                ':cat' => $categoryId,
                ':serv' => $serviceId,
                ':title' => $title,
                ':slug' => $slug,
                ':loc' => $location,
                ':client' => $clientName,
                ':sdate' => $startDate,
                ':cdate' => $completionDate,
                ':budget' => $budget,
                ':status' => $status,
                ':sdesc' => $shortDesc,
                ':desc' => $description,
                ':scope' => $scopeOfWork,
                ':mat' => $materialsUsed,
                ':high' => $highlights,
                ':img' => $featuredImage,
                ':feat' => $isFeatured,
                ':act' => $isActive,
                ':id' => $editId
            ]);
            setFlash('success', 'Project updated successfully.');
        } else {
            $stmt = $db->prepare("INSERT INTO projects (customer_id, category_id, service_id, title, slug, location, client_name, start_date, completion_date, budget, status, short_description, description, scope_of_work, materials_used, highlights, featured_image, is_featured, is_active) VALUES (:cust, :cat, :serv, :title, :slug, :loc, :client, :sdate, :cdate, :budget, :status, :sdesc, :desc, :scope, :mat, :high, :img, :feat, :act)");
            $stmt->execute([
                ':cust' => $customerId,
                ':cat' => $categoryId,
                ':serv' => $serviceId,
                ':title' => $title,
                ':slug' => $slug,
                ':loc' => $location,
                ':client' => $clientName,
                ':sdate' => $startDate,
                ':cdate' => $completionDate,
                ':budget' => $budget,
                ':status' => $status,
                ':sdesc' => $shortDesc,
                ':desc' => $description,
                ':scope' => $scopeOfWork,
                ':mat' => $materialsUsed,
                ':high' => $highlights,
                ':img' => $featuredImage,
                ':feat' => $isFeatured,
                ':act' => $isActive
            ]);
            setFlash('success', 'New project created successfully.');
        }

        header("Location: projects.php");
        exit;
    }
}

// Fetch single project for editing
$projData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $projData = $stmt->fetch();
}

// Fetch Projects List with Category and Customer details
$stmtProjects = $db->query("
    SELECT p.*, sc.name as category_name, c.full_name as customer_name, c.email as customer_email 
    FROM projects p 
    JOIN service_categories sc ON p.category_id = sc.id 
    LEFT JOIN customers c ON p.customer_id = c.id 
    ORDER BY p.id DESC
");
$projectsList = $stmtProjects->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add / Edit Form -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0 text-dark"><?= $editId > 0 ? 'Edit Project' : 'Add New Project' ?></h4>
        <a href="projects.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Projects</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="projects.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST" enctype="multipart/form-data">
            <?= getCSRFInput() ?>
            <input type="hidden" name="existing_image" value="<?= e($projData['featured_image'] ?? '') ?>">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Assigned Customer Account</label>
                    <select name="customer_id" class="form-select">
                        <option value="">-- Unassigned / Public Showcase --</option>
                        <?php foreach ($customers as $cust): ?>
                            <option value="<?= $cust['id'] ?>" <?= (isset($projData['customer_id']) && $projData['customer_id'] == $cust['id']) ? 'selected' : '' ?>>
                                <?= e($cust['full_name']) ?> (<?= e($cust['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Service Vertical Category *</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($projData['category_id']) && $projData['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Related Service (Optional)</label>
                    <select name="service_id" class="form-select">
                        <option value="">-- None / General --</option>
                        <?php foreach ($services as $srv): ?>
                            <option value="<?= $srv['id'] ?>" <?= (isset($projData['service_id']) && $projData['service_id'] == $srv['id']) ? 'selected' : '' ?>>
                                <?= e($srv['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label font-heading fw-bold small">Project Title *</label>
                    <input type="text" name="title" class="form-control" value="<?= e($projData['title'] ?? '') ?>" required placeholder="e.g. Grand Horizon Luxury Villas">
                </div>

                <div class="col-md-6">
                    <label class="form-label font-heading fw-bold small">URL Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= e($projData['slug'] ?? '') ?>" placeholder="grand-horizon-luxury-villas">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= e($projData['location'] ?? '') ?>" placeholder="Sector 128, Noida">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Client / Organization Name</label>
                    <input type="text" name="client_name" class="form-control" value="<?= e($projData['client_name'] ?? '') ?>" placeholder="Apex Developers">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Project Budget (₹)</label>
                    <input type="number" step="0.01" name="budget" class="form-control" value="<?= e($projData['budget'] ?? '') ?>" placeholder="e.g. 1500000">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= e($projData['start_date'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Completion Date</label>
                    <input type="date" name="completion_date" class="form-control" value="<?= e($projData['completion_date'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Project Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="Planned" <?= (isset($projData['status']) && $projData['status'] === 'Planned') ? 'selected' : '' ?>>Planned</option>
                        <option value="In Progress" <?= (isset($projData['status']) && $projData['status'] === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="Completed" <?= (isset($projData['status']) && $projData['status'] === 'Completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="On Hold" <?= (isset($projData['status']) && $projData['status'] === 'On Hold') ? 'selected' : '' ?>>On Hold</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label font-heading fw-bold small">Short Description *</label>
                    <textarea name="short_description" rows="2" class="form-control" required><?= e($projData['short_description'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label font-heading fw-bold small">Detailed Description</label>
                    <textarea name="description" rows="4" class="form-control"><?= e($projData['description'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Scope of Work</label>
                    <textarea name="scope_of_work" rows="3" class="form-control"><?= e($projData['scope_of_work'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Materials Used</label>
                    <textarea name="materials_used" rows="3" class="form-control"><?= e($projData['materials_used'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-heading fw-bold small">Highlights / Key Features</label>
                    <textarea name="highlights" rows="3" class="form-control"><?= e($projData['highlights'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label font-heading fw-bold small">Cover / Featured Image</label>
                    <input type="file" name="featured_image" class="form-control">
                    <?php if (!empty($projData['featured_image'])): ?>
                        <img src="<?= getImageUrl($projData['featured_image'], 'Project', 'Project') ?>" style="max-height: 100px; border-radius: 8px;" class="mt-2" onerror="this.onerror=null; this.src='<?= getFallbackSvg('Project Preview', 'Project') ?>';">
                    <?php endif; ?>
                </div>

                <div class="col-md-6 d-flex align-items-center gap-4 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="pFeatured" <?= (!isset($projData['is_featured']) || $projData['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label font-heading fw-bold" for="pFeatured">Featured Showcase</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="pActive" <?= (!isset($projData['is_active']) || $projData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label font-heading fw-bold" for="pActive">Public Active Visibility</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold font-heading fw-bold px-4"><i class="fas fa-save me-1"></i> Save Project</button>
                    <a href="projects.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- Projects List View -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0 text-dark">Project Showcase Directory (<?= count($projectsList) ?>)</h4>
            <small class="text-muted">Manage portfolio projects across Construction, Interior, and Fabrication.</small>
        </div>
        <a href="projects.php?action=add" class="btn btn-gold btn-sm font-heading fw-bold"><i class="fas fa-plus me-1"></i> Add New Project</a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Project Title & Customer</th>
                        <th>Location & Budget</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projectsList as $p): ?>
                        <tr>
                            <td><span class="badge bg-dark text-warning"><?= e($p['category_name']) ?></span></td>
                            <td>
                                <strong class="text-dark d-block fs-6"><?= e($p['title']) ?></strong>
                                <?php if ($p['customer_name']): ?>
                                    <small class="text-warning fw-bold"><i class="fas fa-user-check me-1"></i> Account: <?= e($p['customer_name']) ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Client: <?= e($p['client_name'] ?: 'Corporate Showcase') ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small"><i class="fas fa-map-marker-alt text-warning me-1"></i> <?= e($p['location'] ?: 'N/A') ?></div>
                                <?php if ($p['budget']): ?>
                                    <small class="fw-bold text-success"><?= formatCurrency($p['budget']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= renderStatusBadge($p['status']) ?></td>
                            <td><?= $p['is_featured'] ? '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                            <td class="text-end">
                                <a href="project-images.php?project_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-info btn-action" title="Manage Extra Gallery Images"><i class="fas fa-images"></i> Images</a>
                                <a href="projects.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-dark btn-action" title="Edit Project"><i class="fas fa-edit"></i> Edit</a>
                                <a href="projects.php?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Delete project <?= e($p['title']) ?>?');" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
