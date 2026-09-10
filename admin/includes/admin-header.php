<?php
/**
 * Admin Header Layout
 */
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$adminFullName = $_SESSION['admin_full_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($adminPageTitle) ? e($adminPageTitle) . ' | Admin Panel' : 'Admin Panel - Raman Group' ?></title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-wrapper">
    <!-- Include Sidebar -->
    <?php require_once __DIR__ . '/admin-sidebar.php'; ?>

    <div class="admin-content">
        <!-- Admin Top Navigation Bar -->
        <header class="admin-topbar">
            <button class="btn btn-light d-lg-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <div class="font-heading fw-bold text-dark fs-5 mb-0">
                <?= isset($adminPageTitle) ? e($adminPageTitle) : 'Dashboard Overview' ?>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="../index.php" target="_blank" class="btn btn-outline-secondary btn-sm" title="View Public Website"><i class="fas fa-globe me-1"></i> View Site</a>
                <div class="dropdown">
                    <button class="btn btn-dark btn-sm dropdown-toggle font-heading fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1 text-warning"></i> <?= e($adminFullName) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="admin-main">
            <?= getFlash() ?>
