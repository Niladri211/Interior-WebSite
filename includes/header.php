<?php
/**
 * Header Component
 * Raman Group Website
 */
require_once __DIR__ . '/functions.php';
$siteTitle = getSiteSetting('site_title', 'Raman Group – Construction, Interior & Fabrication Services');
$pageTitle = isset($customPageTitle) ? $customPageTitle . ' | ' . $siteTitle : $siteTitle;
$metaDesc = isset($customMetaDesc) ? $customMetaDesc : 'Raman Group provides premier Construction, Interior Design, and Precision Fabrication services under one unified brand.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDesc) ?>">
    <meta name="keywords" content="construction company, interior design, metal fabrication, turnkey construction, modular kitchen, structural steel, Noida, Delhi NCR">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDesc) ?>">
    <meta property="og:type" content="website">
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
