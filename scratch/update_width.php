<?php
$files = [
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\welcome.blade.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\partials\\landing\\header.blade.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\partials\\landing\\footer.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('max-w-7xl', 'max-w-[1600px]', $content);
        $content = str_replace('max-w-6xl', 'max-w-[1440px]', $content);
        $content = str_replace('max-w-5xl', 'max-w-[1280px]', $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
