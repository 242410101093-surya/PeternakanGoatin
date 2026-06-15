<?php
$viewsDir = dirname(__DIR__) . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $newContent = preg_replace_callback(
            '/(<(?:button|a)[^>]*class=")([^"]*)("[^>]*>)\s*Batal\s*(<\/(?:button|a)>)/',
            function ($matches) {
                $classes = $matches[2];
                // Don't add if already there
                if (strpos($classes, 'btn-batal') === false) {
                    $classes .= ' btn-batal';
                }
                return $matches[1] . $classes . $matches[3] . 'Batal' . $matches[4];
            },
            $content
        );
        
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
            $count++;
            echo "Updated: $path\n";
        }
    }
}
echo "Total files updated: $count\n";
