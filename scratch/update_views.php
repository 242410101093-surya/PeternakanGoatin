<?php
$directory = new RecursiveDirectoryIterator('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $new_content = preg_replace('/asset\(\'storage\/\'\s*\.\s*([^)]+)\)/', 'Storage::disk(\'supabase\')->url($1)', $content);
    if($new_content !== $content) {
        file_put_contents($path, $new_content);
        echo "Updated " . basename($path) . "\n";
        $count++;
    }
}
echo "Total files updated: $count\n";
