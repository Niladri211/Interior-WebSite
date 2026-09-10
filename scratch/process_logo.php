<?php
$transFile = 'assets/images/raman-group-logo-transparent.png';
$imTrans = imagecreatefrompng($transFile);
$w = imagesx($imTrans);
$h = imagesy($imTrans);

$out = imagecreatetruecolor($w, $h);
imagealphablending($out, false);
imagesavealpha($out, true);
$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefilledrectangle($out, 0, 0, $w, $h, $transparent);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($imTrans, $x, $y);
        $alpha = ($rgb >> 24) & 0x7F;
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // Ribbon text region: y between 710 and 810 (70% to 80% of image height)
        // Check if pixel is light/white inside the ribbon (where text is)
        if ($y > $h * 0.70 && $y < $h * 0.81 && $alpha < 30) {
            // Check if color is white/light silver (R>190, G>190, B>190) or near-white fill inside gold outline
            $isLightText = ($r > 190 && $g > 190 && $b > 190);
            
            if ($isLightText) {
                // Change white fill to dark black/navy text color (#0f172a)
                $darkColor = imagecolorallocatealpha($out, 15, 23, 42, $alpha);
                imagesetpixel($out, $x, $y, $darkColor);
                continue;
            }
        }

        imagesetpixel($out, $x, $y, $rgb);
    }
}

imagepng($out, 'assets/images/raman-group-logo-clean.png');
echo "Generated assets/images/raman-group-logo-clean.png successfully!\n";
?>
