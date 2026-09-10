<?php
/**
 * Admin Team Member Management Module
 * Raman Group
 */
$adminPageTitle = "Team Member Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete
if ($action === 'delete' && $editId > 0) {
    if (verifyCSRFToken($_GET['csrf_token'] ?? '')) {
        $stmt = $db->prepare("DELETE FROM team_members WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        setFlash('success', 'Team member removed.');
    }
    header("Location: team.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security validation failed.');
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $designation = sanitize($_POST['designation'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        $expYears = (int)$_POST['experience_years'];
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $sortOrder = (int)$_POST['sort_order'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $existingImg = sanitize($_POST['existing_image'] ?? '');

        $imagePath = $existingImg;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = handleFileUpload($_FILES['image'], 'team');
            if ($up['success']) {
                $imagePath = $up['filepath'];
            }
        }

        if ($editId > 0) {
            $stmt = $db->prepare("UPDATE team_members SET name = :name, designation = :desig, bio = :bio, experience_years = :exp, email = :email, phone = :phone, image = :img, sort_order = :sort, is_active = :act WHERE id = :id");
            $stmt->execute([':name' => $name, ':desig' => $designation, ':bio' => $bio, ':exp' => $expYears, ':email' => $email, ':phone' => $phone, ':img' => $imagePath, ':sort' => $sortOrder, ':act' => $isActive, ':id' => $editId]);
            setFlash('success', 'Team member details updated.');
        } else {
            $stmt = $db->prepare("INSERT INTO team_members (name, designation, bio, experience_years, email, phone, image, sort_order, is_active) VALUES (:name, :desig, :bio, :exp, :email, :phone, :img, :sort, :act)");
            $stmt->execute([':name' => $name, ':desig' => $designation, ':bio' => $bio, ':exp' => $expYears, ':email' => $email, ':phone' => $phone, ':img' => $imagePath, ':sort' => $sortOrder, ':act' => $isActive]);
            setFlash('success', 'New team member added.');
        }

        header("Location: team.php");
        exit;
    }
}

// Fetch single team member
$teamData = null;
if ($editId > 0 && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM team_members WHERE id = :id");
    $stmt->execute([':id' => $editId]);
    $teamData = $stmt->fetch();
}

$teamList = $db->query("SELECT * FROM team_members ORDER BY sort_order ASC")->fetchAll();
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-heading fw-bold mb-0"><?= $editId > 0 ? 'Edit Team Member' : 'Add Team Member' ?></h4>
        <a href="team.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Team</a>
    </div>

    <div class="admin-table-card p-4">
        <form action="team.php?action=<?= $action ?>&id=<?= $editId ?>" method="POST" enctype="multipart/form-data">
            <?= getCSRFInput() ?>
            <input type="hidden" name="existing_image" value="<?= e($teamData['image'] ?? '') ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= e($teamData['name'] ?? '') ?>" required placeholder="e.g. Ramanpreet Singh">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Designation *</label>
                    <input type="text" name="designation" class="form-control" value="<?= e($teamData['designation'] ?? '') ?>" required placeholder="e.g. Managing Director">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control" value="<?= e($teamData['experience_years'] ?? '10') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($teamData['email'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= e($teamData['phone'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small">Short Bio</label>
                    <textarea name="bio" rows="3" class="form-control"><?= e($teamData['bio'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Profile Image</label>
                    <input type="file" name="image" class="form-control image-upload-preview" data-preview="teamImgPrev">
                    <?php if (!empty($teamData['image'])): ?>
                        <img id="teamImgPrev" src="<?= getImageUrl($teamData['image'], 'Team', 'Team') ?>" style="max-height: 100px; border-radius: 8px;" class="mt-2">
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= e($teamData['sort_order'] ?? '1') ?>">
                </div>

                <div class="col-md-3 d-flex align-items-center mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="tmAct" <?= (!isset($teamData['is_active']) || $teamData['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="tmAct">Active Member</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-gold px-4"><i class="fas fa-save me-1"></i> Save Team Member</button>
                    <a href="team.php" class="btn btn-light ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-heading fw-bold mb-0">Leadership & Team (<?= count($teamList) ?>)</h4>
            <small class="text-muted">Manage executive leaders displayed on the About Us page.</small>
        </div>
        <a href="team.php?action=add" class="btn btn-gold btn-sm"><i class="fas fa-plus me-1"></i> Add Team Member</a>
    </div>

    <div class="admin-table-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Experience</th>
                        <th>Email & Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teamList as $tm): ?>
                        <tr>
                            <td><strong class="text-dark"><?= e($tm['name']) ?></strong></td>
                            <td><span class="badge bg-dark text-warning"><?= e($tm['designation']) ?></span></td>
                            <td><?= e($tm['experience_years']) ?>+ Years</td>
                            <td><small class="text-muted"><?= e($tm['email']) ?> | <?= e($tm['phone']) ?></small></td>
                            <td class="text-end">
                                <a href="team.php?action=edit&id=<?= $tm['id'] ?>" class="btn btn-sm btn-outline-dark btn-action"><i class="fas fa-edit"></i></a>
                                <a href="team.php?action=delete&id=<?= $tm['id'] ?>&csrf_token=<?= e(generateCSRFToken()) ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete-confirm"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
