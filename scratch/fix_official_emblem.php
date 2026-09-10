<?php
$srcFiles = [
    'assets/images/raman-group-official-logo.png',
    'assets/images/raman-group-logo-transparent.png',
    'assets/images/raman-group-logo-clean.png',
    'assets/images/raman-group-logo.png'
];

$workingSrc = null;
foreach ($srcFiles as $f) {
    if (file_exists($f)) {
        $workingSrc = $f;
        break;
    }
}

if (!$workingSrc) {
    die("No source logo file found!\n");
}

echo "Using source logo file: $workingSrc\n";

$im = @imagecreatefrompng($workingSrc);
if (!$im) $im = @imagecreatefromjpeg($workingSrc);

if (!$im) {
    die("Failed to load image from $workingSrc\n");
}

$w = imagesx($im);
$h = imagesy($im);
echo "Image dimensions: {$w}x{$h}\n";

// Process image to preserve Crown, RM Shield, Two Lions, Raman Group ribbon in Gold
// with clean transparent alpha background
$out = imagecreatetruecolor($w, $h);
imagealphablending($out, false);
imagesavealpha($out, true);
$trans = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefilledrectangle($out, 0, 0, $w, $h, $trans);

$minX = $w; $maxX = 0; $minY = $h; $maxY = 0;

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($im, $x, $y);
        $alpha = ($rgb >> 24) & 0x7F;
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Artwork threshold: gold/yellow/bronze artwork or bright pixels
        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);
        
        // Check if pixel belongs to background (dark/black/navy/gray background)
        $isDarkBg = ($maxC < 55) || ($alpha > 120);
        $isGrayBg = ($maxC < 75 && abs($r - $g) < 15 && abs($g - $b) < 15);
        
        if ($isDarkBg || $isGrayBg) {
            continue; // Transparent pixel
        }
        
        // Track bounding box
        if ($x < $minX) $minX = $x;
        if ($x > $maxX) $maxX = $x;
        if ($y < $minY) $minY = $y;
        if ($y > $maxY) $maxY = $y;
        
        // Calculate smooth alpha for semi-dark edge pixels
        if ($maxC < 110 && !($r > $g + 10 && $g > $b + 5)) {
            $edgeAlpha = (int)round(127 - (($maxC - 55) / 55.0) * 127);
            $edgeAlpha = max(0, min(127, $edgeAlpha));
            $col = imagecolorallocatealpha($out, $r, $g, $b, $edgeAlpha);
            imagesetpixel($out, $x, $y, $col);
        } else {
            $col = imagecolorallocatealpha($out, $r, $g, $b, 0);
            imagesetpixel($out, $x, $y, $col);
        }
    }
}

// Crop to artwork bounding box with 15px padding
$pad = 15;
$cropX = max(0, $minX - $pad);
$cropY = max(0, $minY - $pad);
$cropW = min($w - $cropX, ($maxX - $minX) + ($pad * 2));
$cropH = min($h - $cropY, ($maxY - $minY) + ($pad * 2));

$finalIm = imagecreatetruecolor($cropW, $cropH);
imagealphablending($finalIm, false);
imagesavealpha($finalIm, true);
imagefilledrectangle($finalIm, 0, 0, $cropW, $cropH, $trans);

imagecopy($finalIm, $out, 0, 0, $cropX, $cropY, $cropW, $cropH);

$destFile = 'assets/images/official-gold-crest.png';
imagepng($finalIm, $destFile);

imagedestroy($im);
imagedestroy($out);
imagedestroy($finalIm);

echo "Successfully created $destFile ({$cropW}x{$cropH}, " . filesize($destFile) . " bytes)\n";
