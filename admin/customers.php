<?php
/**
 * Admin Customer Accounts Management
 * Raman Group Management Portal
 */
$adminPageTitle = "Customer Accounts Management";
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$error = '';
$success = '';

// Handle Delete Customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_customer') {
    $custId = (int)($_POST['customer_id'] ?? 0);
    if ($custId > 0) {
        $stmtDel = $db->prepare("DELETE FROM customers WHERE id = ?");
        $stmtDel->execute([$custId]);
        setFlash('success', "Customer account #{$custId} deleted successfully.");
        header('Location: customers.php');
        exit;
    }
}

// Handle Add / Edit Customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_customer') {
    $custId = (int)($_POST['customer_id'] ?? 0);
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = strtolower(sanitize($_POST['email'] ?? ''));
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($fullName) || empty($email) || empty($phone)) {
        $error = "Full Name, Email, and Phone Number are required.";
    } else {
        if ($custId > 0) {
            // Update existing customer
            $stmtCheck = $db->prepare("SELECT id FROM customers WHERE email = ? AND id != ?");
            $stmtCheck->execute([$email, $custId]);
            if ($stmtCheck->fetch()) {
                $error = "Another customer account already exists with email '{$email}'.";
            } else {
                if (!empty($password)) {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmtUpd = $db->prepare("UPDATE customers SET full_name = ?, email = ?, phone = ?, address = ?, password_hash = ? WHERE id = ?");
                    $stmtUpd->execute([$fullName, $email, $phone, $address, $hash, $custId]);
                } else {
                    $stmtUpd = $db->prepare("UPDATE customers SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
                    $stmtUpd->execute([$fullName, $email, $phone, $address, $custId]);
                }
                setFlash('success', "Customer account updated successfully.");
                header('Location: customers.php');
                exit;
            }
        } else {
            // Create new customer
            $stmtCheck = $db->prepare("SELECT id FROM customers WHERE email = ?");
            $stmtCheck->execute([$email]);
            if ($stmtCheck->fetch()) {
                $error = "A customer account already exists with email '{$email}'.";
            } else {
                $passToUse = !empty($password) ? $password : 'password123';
                $hash = password_hash($passToUse, PASSWORD_BCRYPT);
                $stmtIns = $db->prepare("INSERT INTO customers (full_name, email, phone, password_hash, address) VALUES (?, ?, ?, ?, ?)");
                $stmtIns->execute([$fullName, $email, $phone, $hash, $address]);
                setFlash('success', "New customer account created successfully.");
                header('Location: customers.php');
                exit;
            }
        }
    }
}

// Fetch Search Filter
$search = sanitize($_GET['search'] ?? '');
$query = "SELECT c.*, 
            (SELECT COUNT(*) FROM quote_requests WHERE customer_id = c.id) as quote_count,
            (SELECT COUNT(*) FROM projects WHERE customer_id = c.id) as project_count
          FROM customers c";
$params = [];

if (!empty($search)) {
    $query .= " WHERE c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?";
    $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
}

$query .= " ORDER BY c.id DESC";
$stmtCust = $db->prepare($query);
$stmtCust->execute($params);
$customers = $stmtCust->fetchAll();
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="font-heading fw-bold mb-1 text-dark">Customer Accounts Management</h4>
        <p class="text-muted small mb-0">View registered client accounts, assign projects, and update access details.</p>
    </div>
    <div>
        <button class="btn btn-gold font-heading fw-bold" data-bs-toggle="modal" data-bs-target="#customerModal0">
            <i class="fas fa-user-plus me-1"></i> Add New Customer
        </button>
    </div>
</div>

<!-- Search Filter Bar -->
<div class="admin-table-card mb-4 p-3">
    <form method="GET" action="customers.php" class="row g-3 align-items-center">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by customer name, email address, or phone number..." value="<?= e($search) ?>">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="fas fa-filter me-1"></i> Search</button>
            <?php if (!empty($search)): ?>
                <a href="customers.php" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Customers Table Card -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Full Name & Contact</th>
                    <th>Address</th>
                    <th>Quotes / Projects</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fs-2 mb-2 d-block text-secondary"></i>
                            No customer accounts found matching your search.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><span class="fw-bold text-dark">#CUST-<?= $c['id'] ?></span></td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?= e($c['full_name']) ?></div>
                                <div class="small text-muted"><i class="fas fa-envelope text-warning me-1"></i> <?= e($c['email']) ?></div>
                                <div class="small text-muted"><i class="fas fa-phone text-warning me-1"></i> <?= e($c['phone']) ?></div>
                            </td>
                            <td><small class="text-muted"><?= e($c['address'] ?: 'N/A') ?></small></td>
                            <td>
                                <span class="badge bg-warning text-dark me-1"><i class="fas fa-calculator me-1"></i> <?= $c['quote_count'] ?> Quotes</span>
                                <span class="badge bg-info text-dark"><i class="fas fa-hard-hat me-1"></i> <?= $c['project_count'] ?> Projects</span>
                            </td>
                            <td class="small text-muted"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-dark btn-action" data-bs-toggle="modal" data-bs-target="#customerModal<?= $c['id'] ?>" title="Edit Customer"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="customers.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete customer <?= e($c['full_name']) ?>? This action cannot be undone.');">
                                        <input type="hidden" name="action" value="delete_customer">
                                        <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Delete Customer"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>

                                <!-- Edit Customer Modal -->
                                <div class="modal fade text-start" id="customerModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title font-heading text-warning">Edit Customer #CUST-<?= $c['id'] ?></h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="customers.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="save_customer">
                                                    <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label font-heading fw-bold">Full Name *</label>
                                                        <input type="text" name="full_name" class="form-control" value="<?= e($c['full_name']) ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-heading fw-bold">Email Address *</label>
                                                        <input type="email" name="email" class="form-control" value="<?= e($c['email']) ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-heading fw-bold">Phone Number *</label>
                                                        <input type="text" name="phone" class="form-control" value="<?= e($c['phone']) ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-heading fw-bold">Site / Contact Address</label>
                                                        <textarea name="address" class="form-control" rows="2"><?= e($c['address']) ?></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label font-heading fw-bold">New Password (Optional)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep existing password">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-gold font-heading fw-bold">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Customer Modal (ID 0) -->
<div class="modal fade" id="customerModal0" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-heading text-warning"><i class="fas fa-user-plus me-1"></i> Add New Customer Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="customers.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="save_customer">
                    <input type="hidden" name="customer_id" value="0">

                    <div class="mb-3">
                        <label class="form-label font-heading fw-bold">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Anil Kumar" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-heading fw-bold">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. anil@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-heading fw-bold">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +91 98765 43210" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-heading fw-bold">Site / Contact Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="e.g. Sector 128, Noida"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-heading fw-bold">Initial Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Default: password123">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold font-heading fw-bold">Create Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
