<?php
/**
 * Customer Portal Header
 * Raman Group Platform
 */
require_once __DIR__ . '/../../includes/auth.php';
requireCustomerLogin();

$customer = getLoggedInCustomer();
$pageTitle = $customerPageTitle ?? 'Customer Portal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Raman Group Customer Portal</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            background-color: #0f141d;
            color: #e2e8f0;
        }
        .portal-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .portal-sidebar {
            width: 270px;
            background: #161c28;
            border-right: 1px solid rgba(212, 175, 55, 0.2);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }
        .portal-content {
            flex-grow: 1;
            background: #0f141d;
            padding: 2rem;
            overflow-x: hidden;
        }
        .portal-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            background: #121722;
        }
        .portal-nav .nav-link {
            color: #94a3b8;
            padding: 0.85rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .portal-nav .nav-link:hover, .portal-nav .nav-link.active {
            color: #d4af37;
            background: rgba(212, 175, 55, 0.08);
            border-left-color: #d4af37;
        }
        .portal-card {
            background: #161c28;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .portal-card-header {
            background: #1b2333;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem 1.5rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .stat-card-gold {
            background: linear-gradient(135deg, #1b2333 0%, #252e42 100%);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
        }
        @media (max-width: 991.98px) {
            .portal-wrapper {
                flex-direction: column;
            }
            .portal-sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="portal-wrapper">
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/customer-sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="portal-content">
        <!-- Top Header Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <div>
                <h3 class="font-heading text-white fw-bold mb-1"><?= e($pageTitle) ?></h3>
                <span class="text-muted small">Raman Group Customer Portal</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="../index.php" class="btn btn-outline-gold btn-sm"><i class="fas fa-globe me-1"></i> Public Website</a>
                <div class="dropdown">
                    <button class="btn btn-navy text-warning border-gold dropdown-toggle btn-sm font-heading fw-bold" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> <?= e($customer['full_name'] ?? 'Customer') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary" aria-labelledby="userMenu">
                        <li><a class="dropdown-item text-light" href="profile.php"><i class="fas fa-user-cog me-2 text-warning"></i> My Profile</a></li>
                        <li><a class="dropdown-item text-light" href="quotes.php"><i class="fas fa-file-invoice me-2 text-warning"></i> My Quotes</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?= getFlash() ?>
