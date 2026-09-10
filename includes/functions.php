<?php
/**
 * Helper Functions & Security Utilities
 * Raman Group Website
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * XSS Clean / HTML escape helper
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize string input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim(htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8'));
}

/**
 * CSRF Token Generation
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getCSRFInput() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * CSRF Token Verification
 */
function verifyCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Flash Notification System
 */
function setFlash($type, $message) {
    $_SESSION['flash_msg'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash_msg'])) {
        $flash = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        $alertClass = $flash['type'] === 'error' ? 'danger' : $flash['type'];
        return '<div class="alert alert-' . e($alertClass) . ' alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas ' . ($flash['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') . ' me-2"></i>
                    ' . e($flash['message']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    return '';
}

/**
 * Slugify text
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Fetch website setting from DB
 */
function getSiteSetting($key, $default = '') {
    static $settingsCache = null;
    
    if ($settingsCache === null) {
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            $settingsCache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (Exception $e) {
            $settingsCache = [];
        }
    }
    
    return $settingsCache[$key] ?? $default;
}

/**
 * Secure File Upload Handler
 */
function handleFileUpload($file, $targetSubDir = 'general', $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'], $maxSizeBytes = 10485760) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        if (isset($file['error']) && $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => false, 'error' => 'No file uploaded.'];
        }
        return ['success' => false, 'error' => 'File upload failed with code: ' . ($file['error'] ?? 'unknown')];
    }

    if ($file['size'] > $maxSizeBytes) {
        return ['success' => false, 'error' => 'File size exceeds maximum limit of ' . ($maxSizeBytes / (1024 * 1024)) . 'MB.'];
    }

    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExts)) {
        return ['success' => false, 'error' => 'Invalid file extension. Allowed: ' . implode(', ', $allowedExts)];
    }

    // Verify MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg', 'image/png', 'image/webp',
        'application/pdf', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'error' => 'Invalid file type header.'];
    }

    $uploadBaseDir = __DIR__ . '/../uploads/' . $targetSubDir . '/';
    if (!file_exists($uploadBaseDir)) {
        mkdir($uploadBaseDir, 0755, true);
    }

    $newFileName = uniqid('file_', true) . '.' . $fileExt;
    $targetPath = $uploadBaseDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [
            'success' => true,
            'filepath' => 'uploads/' . $targetSubDir . '/' . $newFileName,
            'filename' => $newFileName
        ];
    }

    return ['success' => false, 'error' => 'Could not move uploaded file to target folder.'];
}

/**
 * Format rating stars HTML
 */
function renderRatingStars($rating = 5) {
    $rating = max(1, min(5, (int)$rating));
    $html = '<div class="text-warning rating-stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star me-1"></i>';
        } else {
            $html .= '<i class="far fa-star me-1"></i>';
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Format Currency (INR / USD format)
 */
function formatCurrency($amount) {
    if (is_numeric($amount)) {
        return '₹' . number_format($amount, 0, '.', ',');
    }
    return $amount;
}

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? 'C:/xampp/htdocs');
    $rootDir = str_replace('\\', '/', realpath(__DIR__ . '/..') ?: __DIR__ . '/..');
    $relativeDir = str_replace($docRoot, '', $rootDir);
    $baseUrl = rtrim($protocol . $host . '/' . ltrim(str_replace('\\', '/', $relativeDir), '/'), '/') . '/';
    define('BASE_URL', $baseUrl);
}

/**
 * Generate a high-end inline SVG placeholder Data URI
 */
function getFallbackSvg($title = 'Raman Group Project', $category = 'Service') {
    $t = htmlspecialchars($title ?? 'Raman Group', ENT_QUOTES, 'UTF-8');
    $c = strtoupper(htmlspecialchars($category ?? 'SERVICE', ENT_QUOTES, 'UTF-8'));
    
    $iconPath = '<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="#D4AF37" stroke-width="2" fill="none"/>';
    if (str_contains(strtolower($category), 'construct')) {
        $iconPath = '<path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4" stroke="#D4AF37" stroke-width="2" fill="none"/>';
    } elseif (str_contains(strtolower($category), 'interior')) {
        $iconPath = '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="#D4AF37" stroke-width="2" fill="none"/><polyline points="9 22 9 12 15 12 15 22" stroke="#D4AF37" stroke-width="2" fill="none"/>';
    } elseif (str_contains(strtolower($category), 'fabrica')) {
        $iconPath = '<rect x="2" y="6" width="20" height="12" rx="2" stroke="#D4AF37" stroke-width="2" fill="none"/><path d="M12 12h.01M17 12h.01M7 12h.01" stroke="#D4AF37" stroke-width="3" stroke-linecap="round"/>';
    }

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">
        <defs>
            <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#0b1220"/>
                <stop offset="50%" stop-color="#161f33"/>
                <stop offset="100%" stop-color="#0b1220"/>
            </linearGradient>
            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#d4af37" stroke-width="0.5" stroke-opacity="0.1"/>
            </pattern>
        </defs>
        <rect width="800" height="500" fill="url(#bg)"/>
        <rect width="800" height="500" fill="url(#grid)"/>
        <rect x="20" y="20" width="760" height="460" rx="12" fill="none" stroke="#d4af37" stroke-width="1.5" stroke-opacity="0.3"/>
        <g transform="translate(370, 160) scale(2.5)">
            ' . $iconPath . '
        </g>
        <text x="400" y="310" font-family="Outfit, sans-serif" font-size="22" font-weight="800" fill="#ffffff" text-anchor="middle" letter-spacing="1">' . mb_strimwidth($t, 0, 35, '...') . '</text>
        <rect x="300" y="335" width="200" height="28" rx="14" fill="#d4af37" fill-opacity="0.15" stroke="#d4af37" stroke-opacity="0.4"/>
        <text x="400" y="354" font-family="Outfit, sans-serif" font-size="12" font-weight="800" fill="#d4af37" text-anchor="middle" letter-spacing="2">' . $c . '</text>
        <text x="400" y="420" font-family="Plus Jakarta Sans, sans-serif" font-size="13" font-weight="600" fill="#94a3b8" text-anchor="middle">RAMAN GROUP ARCHITECTURE &amp; ENGINEERING</text>
    </svg>';

    return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
}

/**
 * Get Image URL with fallback high-end SVG generation
 */
function getImageUrl($path, $title = 'Raman Group', $category = 'Service') {
    $path = trim($path ?? '');
    
    // 1. If path is a remote URL or data URI, return immediately
    if (!empty($path) && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:'))) {
        return e($path);
    }
    
    // 2. If path is a local file that actually exists on disk, normalize & return base-relative URL
    if (!empty($path)) {
        $cleanPath = ltrim(preg_replace('~^(\.\./)+~', '', $path), '/');
        $physicalPath = __DIR__ . '/../' . $cleanPath;
        
        if (file_exists($physicalPath) && !is_dir($physicalPath)) {
            $baseUrl = defined('BASE_URL') ? BASE_URL : '';
            return e($baseUrl . $cleanPath);
        }
    }
    
    // 3. Dynamic fallback to high-resolution real photographs based on title & category
    $t = strtolower($title . ' ' . $category . ' ' . $path);

    // Explicit high-res domain mappings for specific service & project topics
    if (str_contains($t, 'kitchen')) return 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'bedroom')) return 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'living')) return 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'office') || str_contains($t, 'workplace') || str_contains($t, 'cabin') || str_contains($t, 'boardroom')) return 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'modular furniture') || str_contains($t, 'wardrobe') || str_contains($t, 'cabinetry')) return 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'ceiling')) return 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'lighting') || str_contains($t, 'decor') || str_contains($t, 'chandelier')) return 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'home interior') || str_contains($t, 'luxury home')) return 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80';

    if (str_contains($t, 'residential') || str_contains($t, 'villa')) return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'commercial construction') || str_contains($t, 'commercial tower') || str_contains($t, 'plaza')) return 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'renovation') || str_contains($t, 'restoration') || str_contains($t, 'retrofit')) return 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'civil') || str_contains($t, 'earth') || str_contains($t, 'drainage')) return 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'structural works') || str_contains($t, 'rcc') || str_contains($t, 'superstructure')) return 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'turnkey construction')) return 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?auto=format&fit=crop&w=800&q=80';

    if (str_contains($t, 'steel fabrication') || str_contains($t, 'heavy steel')) return 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'ms fabrication') || str_contains($t, 'mild steel')) return 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'ss fabrication') || str_contains($t, 'stainless')) return 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'gate')) return 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'grill') || str_contains($t, 'screen')) return 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'railing') || str_contains($t, 'balustrade')) return 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'staircase') || str_contains($t, 'spiral')) return 'https://images.unsplash.com/photo-1506146332389-18140dc7b2fb?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'structural fabrication') || str_contains($t, 'peb')) return 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80';
    if (str_contains($t, 'custom metal')) return 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=800&q=80';

    // Construction photo library fallback
    $constructionPhotos = [
        'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?auto=format&fit=crop&w=800&q=80'
    ];

    // Interior photo library fallback
    $interiorPhotos = [
        'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80'
    ];

    // Fabrication photo library fallback
    $fabricationPhotos = [
        'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1535813547-99c456a41d4a?auto=format&fit=crop&w=800&q=80'
    ];

    // Deterministic hash assignment based on title CRC32
    $hash = abs(crc32($title)) % 6;
    if (str_contains($t, 'interior')) {
        return $interiorPhotos[$hash % count($interiorPhotos)];
    } elseif (str_contains($t, 'fabrication')) {
        return $fabricationPhotos[$hash % count($fabricationPhotos)];
    } elseif (str_contains($t, 'construct')) {
        return $constructionPhotos[$hash % count($constructionPhotos)];
    }

    return getFallbackSvg($title, $category);
}

/**
 * Render Status Badge HTML
 */
function renderStatusBadge($status) {
    $s = trim($status);
    $badgeClass = 'bg-secondary';
    $icon = 'fa-info-circle';

    switch (strtolower($s)) {
        case 'pending':
            $badgeClass = 'bg-warning text-dark';
            $icon = 'fa-clock';
            break;
        case 'reviewing':
        case 'under review':
        case 'in discussion':
            $badgeClass = 'bg-info text-dark';
            $icon = 'fa-search';
            break;
        case 'quotation sent':
            $badgeClass = 'bg-primary';
            $icon = 'fa-paper-plane';
            break;
        case 'accepted':
        case 'approved':
        case 'converted':
        case 'completed':
            $badgeClass = 'bg-success';
            $icon = 'fa-check-circle';
            break;
        case 'rejected':
        case 'closed':
            $badgeClass = 'bg-danger';
            $icon = 'fa-times-circle';
            break;
        case 'in_progress':
        case 'in progress':
            $badgeClass = 'bg-primary text-white';
            $icon = 'fa-spinner fa-spin';
            break;
        case 'planned':
        case 'planning':
            $badgeClass = 'bg-secondary';
            $icon = 'fa-drafting-compass';
            break;
        case 'on_hold':
        case 'on hold':
            $badgeClass = 'bg-warning text-dark';
            $icon = 'fa-pause-circle';
            break;
    }

    return '<span class="badge ' . $badgeClass . ' px-3 py-2 rounded-pill"><i class="fas ' . $icon . ' me-1"></i> ' . e($status) . '</span>';
}


