<?php

$dir = new RecursiveDirectoryIterator('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // 1. Remove Tailwind CDN
    $content = preg_replace('/<script src="https:\/\/cdn\.tailwindcss\.com[^>]*"><\/script>\s*/s', '', $content);

    // 2. Remove Tailwind Config script
    $content = preg_replace('/<script id="tailwind-config">.*?<\/script>\s*/s', '', $content);

    // 3. Add @vite before </head> if not exists
    if (!str_contains($content, "@vite(['resources/css/app.css', 'resources/js/app.js'])")) {
        $content = str_replace('</head>', "    @vite(['resources/css/app.css', 'resources/js/app.js'])\n</head>", $content);
    }

    // 4. Add overflow-x-hidden to body
    if (preg_match('/<body[^>]*class="([^"]*)"[^>]*>/', $content, $matches)) {
        $classes = $matches[1];
        if (!str_contains($classes, 'overflow-x-hidden')) {
            $newClasses = trim($classes . ' overflow-x-hidden');
            $bodyStr = str_replace('class="' . $classes . '"', 'class="' . $newClasses . '"', $matches[0]);
            $content = str_replace($matches[0], $bodyStr, $content);
        }
    } else if (preg_match('/<body[^>]*>/', $content, $matches)) {
        // Body has no class attribute
        if (!str_contains($matches[0], 'class=')) {
            $bodyStr = str_replace('<body', '<body class="overflow-x-hidden"', $matches[0]);
            $content = str_replace($matches[0], $bodyStr, $content);
        }
    }

    if ($original !== $content) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
    }
}
echo "Done.\n";
