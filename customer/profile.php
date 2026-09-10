<?php
/**
 * Customer Profile & Security Management
 * Raman Group Platform
 */
$customerPageTitle = "My Profile & Password";
require_once __DIR__ . '/includes/customer-header.php';

$db = getDB();
$customerId = $_SESSION['customer_id'];
$customer = getLoggedInCustomer();

$profileMsg = '';
$profileErr = '';
$passMsg = '';
$passErr = '';

// Handle Profile Details Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'update_profile') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    if (empty($fullName) || empty($phone)) {
        $profileErr = "Full Name and Phone Number are required.";
    } else {
        $stmtUpd = $db->prepare("UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?");
        $stmtUpd->execute([$fullName, $phone, $address, $customerId]);
        
        $_SESSION['customer_name'] = $fullName;
        $_SESSION['customer_phone'] = $phone;
        
        setFlash('success', 'Profile details updated successfully!');
        header('Location: profile.php');
        exit;
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'change_password') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $passErr = "Please fill in all password fields.";
    } elseif (strlen($newPassword) < 6) {
        $passErr = "New password must be at least 6 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $passErr = "New password and confirmation do not match.";
    } else {
        // Verify current password
        $stmtFetch = $db->prepare("SELECT password_hash FROM customers WHERE id = ? LIMIT 1");
        $stmtFetch->execute([$customerId]);
        $row = $stmtFetch->fetch();

        if ($row && password_verify($currentPassword, $row['password_hash'])) {
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmtPass = $db->prepare("UPDATE customers SET password_hash = ? WHERE id = ?");
            $stmtPass->execute([$newHash, $customerId]);
            
            setFlash('success', 'Your password has been changed successfully!');
            header('Location: profile.php');
            exit;
        } else {
            $passErr = "Current password entered is incorrect.";
        }
    }
}
?>

<div class="row g-4">
    <!-- Profile Details Card -->
    <div class="col-lg-6">
        <div class="card portal-card border-gold h-100">
            <div class="portal-card-header bg-navy border-bottom border-gold">
                <h5 class="font-heading text-warning mb-0"><i class="fas fa-user-edit me-2"></i> Edit Account Profile</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($profileErr)): ?>
                    <div class="alert alert-danger mb-3" role="alert"><i class="fas fa-exclamation-circle me-1"></i> <?= e($profileErr) ?></div>
                <?php endif; ?>

                <form method="POST" action="profile.php">
                    <input type="hidden" name="form_type" value="update_profile">

                    <div class="mb-3">
                        <label for="full_name" class="form-label text-warning small font-heading fw-bold">Full Name *</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" id="full_name" name="full_name" value="<?= e($customer['full_name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small font-heading fw-bold">Email Address (Read-only)</label>
                        <input type="email" class="form-control bg-dark text-muted border-secondary" value="<?= e($customer['email']) ?>" disabled>
                        <div class="form-text text-muted">Email address cannot be modified once registered.</div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label text-warning small font-heading fw-bold">Phone Number *</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" id="phone" name="phone" value="<?= e($customer['phone']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label text-warning small font-heading fw-bold">Billing / Site Address</label>
                        <textarea class="form-control bg-dark text-white border-secondary" id="address" name="address" rows="3"><?= e($customer['address']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-gold font-heading fw-bold px-4 py-2"><i class="fas fa-save me-1"></i> Save Profile Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Security Card -->
    <div class="col-lg-6">
        <div class="card portal-card border-gold h-100">
            <div class="portal-card-header bg-navy border-bottom border-gold">
                <h5 class="font-heading text-warning mb-0"><i class="fas fa-lock me-2"></i> Security & Password</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($passErr)): ?>
                    <div class="alert alert-danger mb-3" role="alert"><i class="fas fa-exclamation-circle me-1"></i> <?= e($passErr) ?></div>
                <?php endif; ?>

                <form method="POST" action="profile.php">
                    <input type="hidden" name="form_type" value="change_password">

                    <div class="mb-3">
                        <label for="current_password" class="form-label text-warning small font-heading fw-bold">Current Password *</label>
                        <input type="password" class="form-control bg-dark text-white border-secondary" id="current_password" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label text-warning small font-heading fw-bold">New Password *</label>
                        <input type="password" class="form-control bg-dark text-white border-secondary" id="new_password" name="new_password" placeholder="Min. 6 characters" required>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label text-warning small font-heading fw-bold">Confirm New Password *</label>
                        <input type="password" class="form-control bg-dark text-white border-secondary" id="confirm_password" name="confirm_password" placeholder="Repeat new password" required>
                    </div>

                    <button type="submit" class="btn btn-gold font-heading fw-bold px-4 py-2"><i class="fas fa-key me-1"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/customer-footer.php'; ?>
