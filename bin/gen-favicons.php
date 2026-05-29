<?php
/**
 * One-time favicon generator.
 * Source: square 7 Islands Watamu badge (blue, white mark).
 * Produces PNG favicons + apple-touch-icon + a multi-size favicon.ico.
 *
 * Run:  php bin/gen-favicons.php "C:/path/to/source.jpeg"
 */

$src    = $argv[1] ?? 'C:/Users/Alysa Emilio/Downloads/WhatsApp Image 2026-05-29 at 10.04.07 AM.jpeg';
$root   = dirname(__DIR__);
$imgDir = $root . '/assets/img';

if (!file_exists($src)) {
    fwrite(STDERR, "Source not found: $src\n");
    exit(1);
}

$info = getimagesize($src);
$srcImg = match ($info['mime']) {
    'image/jpeg' => imagecreatefromjpeg($src),
    'image/png'  => imagecreatefrompng($src),
    default      => null,
};
if (!$srcImg) {
    fwrite(STDERR, "Unsupported source mime: {$info['mime']}\n");
    exit(1);
}
$sw = imagesx($srcImg);
$sh = imagesy($srcImg);

/** Resample the source down to a square $size x $size truecolor image with alpha kept. */
function squareResize($srcImg, int $sw, int $sh, int $size) {
    $dst = imagecreatetruecolor($size, $size);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefilledrectangle($dst, 0, 0, $size, $size, $transparent);
    imagealphablending($dst, true);
    imagecopyresampled($dst, $srcImg, 0, 0, 0, 0, $size, $size, $sw, $sh);
    return $dst;
}

function pngBytes($img): string {
    ob_start();
    imagepng($img);
    return ob_get_clean();
}

/** Build a multi-image .ico that embeds PNG-encoded frames (Vista+). */
function buildIco(array $framesBySize): string {
    $count   = count($framesBySize);
    $header  = pack('vvv', 0, 1, $count);   // reserved, type=icon, count
    $entries = '';
    $data    = '';
    $offset  = 6 + 16 * $count;
    foreach ($framesBySize as $size => $bytes) {
        $dim  = $size >= 256 ? 0 : $size;   // 0 == 256 in ICO spec
        $len  = strlen($bytes);
        $entries .= pack('CCCC', $dim, $dim, 0, 0)  // w, h, palette, reserved
                  . pack('vv', 1, 32)                // color planes, bits-per-pixel
                  . pack('VV', $len, $offset);       // size, offset
        $data   .= $bytes;
        $offset += $len;
    }
    return $header . $entries . $data;
}

// Master square PNG (reusable square logo asset) + favicon PNGs.
$outputs = [
    "$imgDir/logo-square.png"      => 250,
    "$imgDir/favicon-16.png"       => 16,
    "$imgDir/favicon-32.png"       => 32,
    "$imgDir/favicon-48.png"       => 48,
    "$imgDir/favicon-96.png"       => 96,
    "$imgDir/favicon-192.png"      => 192,
    "$imgDir/apple-touch-icon.png" => 180,
];
foreach ($outputs as $path => $size) {
    $img = squareResize($srcImg, $sw, $sh, $size);
    imagepng($img, $path);
    imagedestroy($img);
    echo "wrote $path ({$size}x{$size})\n";
}

// favicon.ico with 16/32/48 frames at site root.
$icoFrames = [];
foreach ([16, 32, 48] as $size) {
    $img = squareResize($srcImg, $sw, $sh, $size);
    $icoFrames[$size] = pngBytes($img);
    imagedestroy($img);
}
file_put_contents("$root/favicon.ico", buildIco($icoFrames));
echo "wrote $root/favicon.ico (16/32/48)\n";

imagedestroy($srcImg);
echo "done\n";
