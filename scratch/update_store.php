<?php
$files = [
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\app\\Http\\Controllers\\ProfileController.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\app\\Http\\Controllers\\Admin\\KatalogController.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\app\\Http\\Controllers\\Admin\\InventarisController.php',
    'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\app\\Http\\Controllers\\Admin\\ArtikelController.php'
];
foreach($files as $f) {
    if(file_exists($f)) {
        $c = file_get_contents($f);
        $c = str_replace("store('profile_photos', 'public')", "store('profile_photos', 'supabase')", $c);
        $c = str_replace("store('produk_fotos', 'public')", "store('produk_fotos', 'supabase')", $c);
        $c = str_replace("store('artikel_fotos', 'public')", "store('artikel_fotos', 'supabase')", $c);
        file_put_contents($f, $c);
        echo "Updated " . basename($f) . "\n";
    }
}
