<?php
$content = file_get_contents('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\scratch\\old_welcome_git.blade.php');
$content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');

if (preg_match('/<script>\s*tailwind\.config\s*=\s*(\{.*?\});?\s*<\/script>/is', $content, $matches)) {
    file_put_contents('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\scratch\\welcome_tailwind.js', $matches[1]);
    echo "Extracted.\n";
} else {
    echo "Not found tailwind.config.\n";
}
