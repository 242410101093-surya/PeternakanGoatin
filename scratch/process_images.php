<?php
$mediaPath = 'C:/Users/M RIDWAN SURYA PUTRA/.gemini/antigravity-ide/brain/e516f981-5039-456a-b301-189090b0b30a/media__1780807276829.png';
$signaturePath = 'C:/Users/M RIDWAN SURYA PUTRA/.gemini/antigravity-ide/brain/e516f981-5039-456a-b301-189090b0b30a/doctor_signature_1780807243833.png';

// Copy signature
if (copy($signaturePath, 'public/images/signature.png')) {
    echo "Successfully copied signature to public/images/signature.png\n";
} else {
    echo "Failed to copy signature\n";
}

// Function to resize and save PNG
function resizePng($srcPath, $destPath, $newWidth, $newHeight) {
    $src = imagecreatefrompng($srcPath);
    if (!$src) {
        echo "Failed to load source image: $srcPath\n";
        return false;
    }
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $transparent);
    
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($src), imagesy($src));
    imagepng($dst, $destPath);
    imagedestroy($src);
    imagedestroy($dst);
    echo "Resized $srcPath to $destPath ($newWidth x $newHeight)\n";
    return true;
}

// Resize the user logo to different favicon sizes
resizePng($mediaPath, 'public/images/favicon.png', 1024, 1024);
resizePng($mediaPath, 'public/images/favicon-64.png', 64, 64);
resizePng($mediaPath, 'public/images/favicon-32.png', 32, 32);
resizePng($mediaPath, 'public/images/favicon-16.png', 16, 16);
?>
