<?php
$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/customer.blade.php',
    'resources/views/layouts/admin.blade.php',
    'resources/views/admin/auth/login.blade.php',
    'resources/views/customer/auth/reset-password.blade.php',
    'resources/views/customer/auth/register.blade.php',
    'resources/views/customer/auth/login.blade.php',
    'resources/views/customer/auth/forgot-password.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        $pattern = "/const loader = document\.getElementById\('global-page-loader'\);\s*function showLoader\(\) \{ [^}]* \}\s*function hideLoader\(\) \{ [^}]* \}/m";
        
        $replacement = "const loader = document.getElementById('global-page-loader');\n            let loaderTimeout;\n            function showLoader() { loaderTimeout = setTimeout(() => { loader.style.display = 'flex'; }, 150); }\n            function hideLoader() { clearTimeout(loaderTimeout); loader.style.display = 'none'; }";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        // Admin layout is slightly different (multiline functions)
        $pattern2 = "/const loader = document\.getElementById\('global-page-loader'\);\s*function showLoader\(\) \{\s*loader\.style\.display = 'flex';\s*\}\s*function hideLoader\(\) \{\s*loader\.style\.display = 'none';\s*\}/m";
        $newContent = preg_replace($pattern2, $replacement, $newContent);

        if ($newContent !== $content) {
            file_put_contents($file, $newContent);
            echo "Updated: $file\n";
        }
    }
}
echo "Done.\n";
