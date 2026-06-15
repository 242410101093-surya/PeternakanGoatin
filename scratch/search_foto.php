<?php
$dirs = ['app/', 'resources/views/', 'routes/'];
foreach ($dirs as $dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $content = file_get_contents($file->getRealPath());
            if (strpos($content, 'foto') !== false) {
                echo "---- " . $file->getRealPath() . " ----\n";
                $lines = explode("\n", $content);
                foreach ($lines as $i => $line) {
                    if (strpos($line, 'foto') !== false) {
                        echo "Line " . ($i+1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
}
