<?php
$files = [
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\partials\\customer\\navbar.blade.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\customer\\dashboard.blade.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\customer\\produk.blade.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views\\customer\\monitoring.blade.php'
];

foreach($files as $f) {
    if(file_exists($f)) {
        $content = file_get_contents($f);
        $content = str_replace('max-w-[1200px]', 'max-w-[1600px]', $content);
        file_put_contents($f, $content);
        echo 'Updated ' . basename($f) . PHP_EOL;
    }
}
