<?php
$content = file_get_contents('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\scratch\\old_admin.blade.php');
// The file is UTF-16LE, convert it to UTF-8
$content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');

if (preg_match('/<script id="tailwind-config">(.*?)<\/script>/s', $content, $matches)) {
    file_put_contents('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\scratch\\old_tailwind_config.js', $matches[1]);
    echo "Extracted.\n";
} else {
    echo "Not found.\n";
}
