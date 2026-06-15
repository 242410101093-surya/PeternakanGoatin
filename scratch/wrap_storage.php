<?php
$viewsDir = 'resources/views/';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $original = $content;

        // Replace Storage::disk('supabase')->url($X) with a safe try-catch inline or a safe fallback
        // We can use a custom Blade-compatible fallback:
        // {{ (function($p){ try { return $p ? Storage::disk(config('filesystems.default'))->url($p) : asset('images/default.jpg'); } catch(\Exception $e) { return asset('images/default.jpg'); } })($var) }}

        $pattern = '/Storage::disk\(\'supabase\'\)->url\(([^)]+)\)/';
        
        $replacement = "(function(\$p){ try { return \$p ? Storage::disk(config('filesystems.default'))->url(\$p) : asset('images/placeholder.png'); } catch(\Exception \$e) { return asset('images/placeholder.png'); } })($1)";
        
        $content = preg_replace($pattern, $replacement, $content);

        // Also fix the ->foto property check for Users in navbar if any, but since we reverted, it's ->foto_profil
        
        if ($content !== $original) {
            file_put_contents($file->getRealPath(), $content);
            echo "Updated " . $file->getRealPath() . PHP_EOL;
        }
    }
}
