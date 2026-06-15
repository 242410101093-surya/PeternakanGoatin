<?php
$files = [
    'app/Models/User.php',
    'app/Http/Controllers/ProfileController.php',
    'resources/views/admin/profile.blade.php',
    'resources/views/partials/admin/navbar.blade.php',
    'resources/views/customer/profile.blade.php',
    'resources/views/partials/customer/navbar.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $content = str_replace('foto_profil', 'foto', $content);
    file_put_contents($file, $content);
    echo 'Updated ' . $file . PHP_EOL;
}
