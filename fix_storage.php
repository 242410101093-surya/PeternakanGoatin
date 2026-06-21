<?php

/**
 * Goatin Storage Fixer Script
 * Designed for Laragon / Windows local environment to automatically solve 403 Forbidden & symlink issues.
 */

define('DS', DIRECTORY_SEPARATOR);

echo "=============================================\n";
echo "       GOATIN LOCAL STORAGE LINK FIXER       \n";
echo "=============================================\n\n";

$basePath = __DIR__;
$publicStoragePath = $basePath . DS . 'public' . DS . 'storage';
$appStoragePath = $basePath . DS . 'storage' . DS . 'app' . DS . 'public';

// 1. Ensure storage subdirectories exist
echo "[1/4] Memastikan folder penyimpanan lokal ada...\n";
$subdirs = ['artikel_fotos', 'produk_fotos', 'profile_photos', 'nota_pembayaran'];
if (!is_dir($appStoragePath)) {
    mkdir($appStoragePath, 0755, true);
    echo " -> Created folder: storage/app/public\n";
}

foreach ($subdirs as $dir) {
    $path = $appStoragePath . DS . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo " -> Created folder: storage/app/public/{$dir}\n";
    }
}
echo " -> OK: Semua folder penyimpanan lokal dipastikan ada.\n\n";

// 2. Clean up old/broken symlink/junction
echo "[2/4] Membersihkan jembatan (symlink) lama jika ada...\n";
if (file_exists($publicStoragePath) || is_link($publicStoragePath)) {
    // Determine link type and remove it
    if (is_dir($publicStoragePath) && !is_link($publicStoragePath)) {
        // Check if it's a Windows Junction
        // We attempt to delete it using rmdir
        if (@rmdir($publicStoragePath)) {
            echo " -> Sukses menghapus junction/direktori 'public/storage'\n";
        } else {
            // If it's a real directory with files, rename it or warn
            echo " -> WARNING: 'public/storage' adalah direktori asli. Mengubah namanya menjadi 'public/storage_backup'...\n";
            rename($publicStoragePath, $publicStoragePath . '_backup_' . time());
        }
    } else {
        if (@unlink($publicStoragePath) || @rmdir($publicStoragePath)) {
            echo " -> Sukses menghapus symlink 'public/storage'\n";
        } else {
            echo " -> Gagal menghapus 'public/storage' secara langsung. Mencoba via command line...\n";
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('rmdir "' . $publicStoragePath . '" 2>NUL');
                exec('del "' . $publicStoragePath . '" 2>NUL');
            } else {
                exec('rm -rf "' . $publicStoragePath . '"');
            }
        }
    }
} else {
    echo " -> OK: Tidak ditemukan symlink lama yang rusak di 'public/storage'.\n";
}
echo "\n";

// 3. Choose dynamic fallback mode vs physical symlink
echo "[3/4] Mengonfigurasi jembatan jalan pintas...\n";
echo "Apakah Anda menggunakan Apache Laragon atau php artisan serve?\n";
echo "Di Windows/Apache, symlink fisik seringkali memicu error '403 Forbidden' karena batasan hak akses direktori Apache.\n";
echo "Kami telah membuat route fallback dinamis di Laravel (routes/web.php) yang menangani asset /storage/ secara otomatis dan aman.\n\n";

echo "Mencoba membuat jembatan baru...\n";

// Run artisan storage:link
$output = [];
$returnVar = 0;
exec('php artisan storage:link', $output, $returnVar);

echo implode("\n", $output) . "\n";

if ($returnVar === 0 && file_exists($publicStoragePath)) {
    echo "\n -> OK: Jembatan fisik 'public/storage' berhasil dibuat.\n";
} else {
    echo "\n -> WARNING: Gagal membuat jembatan fisik. Jangan khawatir!\n";
    echo "    Route fallback dinamis di 'routes/web.php' akan menangani asset secara otomatis.\n";
}
echo "\n";

// 4. Checking verification & permissions
echo "[4/4] Verifikasi Lingkungan Lokal...\n";
echo "Aplikasi Anda menggunakan Apache Laragon / Windows.\n";
echo "Jika Anda masih mengalami error 403 Forbidden di Laragon:\n";
echo "1. Hapus folder 'public/storage' (jalankan: rmdir public\storage di CMD).\n";
echo "2. Laragon akan otomatis mem-bypass folder fisik tersebut dan meneruskannya ke Laravel,\n";
echo "   sehingga route fallback dinamis kami di 'routes/web.php' akan menyajikan gambar dengan aman (200 OK).\n\n";
echo "Selesai! Silakan refresh browser Anda di localhost (127.0.0.1:8000 atau goatin.test) untuk memverifikasi.\n";
echo "=============================================\n";
