<?php
/**
 * Admin Website Global Settings Module
 * Raman Group
 */
$adminPageTitle = "Website Settings";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('danger', 'Security token mismatch.');
    } else {
        $settings = $_POST['settings'] ?? [];

        $stmtSave = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :val) ON DUPLICATE KEY UPDATE setting_value = :val2");

        foreach ($settings as $key => $val) {
            $cleanKey = sanitize($key);
            $cleanVal = trim($val);
            $stmtSave->execute([':key' => $cleanKey, ':val' => $cleanVal, ':val2' => $cleanVal]);
        }

        setFlash('success', 'Website settings saved successfully.');
        header("Location: settings.php");
        exit;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-heading fw-bold mb-0">Global Website Settings</h4>
        <small class="text-muted">Configure corporate address, contact numbers, social links, and statistics counters.</small>
    </div>
</div>

<div class="admin-table-card p-4">
    <form action="settings.php" method="POST">
        <?= getCSRFInput() ?>

        <div class="row g-4">
            <!-- Branding -->
            <div class="col-12">
                <h5 class="font-heading fw-bold text-dark border-bottom pb-2"><i class="fas fa-globe text-warning me-2"></i> Branding & Meta Information</h5>
            </div>
            
            <div class="col-md-6">
                <label class="form-label fw-bold small">Website Main Title</label>
                <input type="text" name="settings[site_title]" class="form-control" value="<?= e(getSiteSetting('site_title')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Company Tagline</label>
                <input type="text" name="settings[tagline]" class="form-control" value="<?= e(getSiteSetting('tagline')) ?>">
            </div>

            <!-- Contact & Office -->
            <div class="col-12 mt-4">
                <h5 class="font-heading fw-bold text-dark border-bottom pb-2"><i class="fas fa-phone-alt text-warning me-2"></i> Office Contact Details</h5>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Primary Phone</label>
                <input type="text" name="settings[phone]" class="form-control" value="<?= e(getSiteSetting('phone')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Secondary Phone</label>
                <input type="text" name="settings[secondary_phone]" class="form-control" value="<?= e(getSiteSetting('secondary_phone')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">General Info Email</label>
                <input type="email" name="settings[email]" class="form-control" value="<?= e(getSiteSetting('email')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Projects Support Email</label>
                <input type="email" name="settings[support_email]" class="form-control" value="<?= e(getSiteSetting('support_email')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">WhatsApp Direct Number (with Country Code e.g. 919876543210)</label>
                <input type="text" name="settings[whatsapp_number]" class="form-control" value="<?= e(getSiteSetting('whatsapp_number')) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Business Hours</label>
                <input type="text" name="settings[business_hours]" class="form-control" value="<?= e(getSiteSetting('business_hours')) ?>">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold small">Corporate Address</label>
                <textarea name="settings[address]" rows="2" class="form-control"><?= e(getSiteSetting('address')) ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold small">Google Map iFrame Embed URL</label>
                <input type="text" name="settings[google_map_embed]" class="form-control" value="<?= e(getSiteSetting('google_map_embed')) ?>">
            </div>

            <!-- Social Links -->
            <div class="col-12 mt-4">
                <h5 class="font-heading fw-bold text-dark border-bottom pb-2"><i class="fas fa-share-nodes text-warning me-2"></i> Social Media Links</h5>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small">Facebook URL</label>
                <input type="text" name="settings[facebook_url]" class="form-control" value="<?= e(getSiteSetting('facebook_url')) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small">Instagram URL</label>
                <input type="text" name="settings[instagram_url]" class="form-control" value="<?= e(getSiteSetting('instagram_url')) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small">LinkedIn URL</label>
                <input type="text" name="settings[linkedin_url]" class="form-control" value="<?= e(getSiteSetting('linkedin_url')) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small">YouTube URL</label>
                <input type="text" name="settings[youtube_url]" class="form-control" value="<?= e(getSiteSetting('youtube_url')) ?>">
            </div>

            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-gold btn-lg px-5"><i class="fas fa-save me-1"></i> Save Global Settings</button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
