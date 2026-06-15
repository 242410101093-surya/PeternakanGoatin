<?php
$cssPath = 'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\css\\app.css';
$cssContent = file_get_contents($cssPath);

$missingConfig = <<<CSS

    /* Spacing */
    --spacing-margin-mobile: 16px;
    --spacing-margin-desktop: 32px;
    --spacing-gutter: 24px;
    --spacing-stack-xs: 4px;
    --spacing-stack-sm: 8px;
    --spacing-stack-md: 16px;
    --spacing-stack-lg: 32px;
    --spacing-stack-xl: 56px;
    --spacing-unit: 8px;
    --spacing-container-max: 1280px;

    /* Fonts */
    --font-sans: "Plus Jakarta Sans", sans-serif;
    --font-body-md: "Plus Jakarta Sans", sans-serif;
    --font-body-lg: "Plus Jakarta Sans", sans-serif;
    --font-label-sm: "Plus Jakarta Sans", sans-serif;
    --font-caption: "Plus Jakarta Sans", sans-serif;
    --font-h1: "Plus Jakarta Sans", sans-serif;
    --font-h2: "Plus Jakarta Sans", sans-serif;
    --font-h3: "Plus Jakarta Sans", sans-serif;

    /* Font Sizes */
    --text-h1: 36px;
    --text-h1--line-height: 1.2;
    --text-h1--letter-spacing: -0.02em;
    --text-h1--font-weight: 800;

    --text-h2: 28px;
    --text-h2--line-height: 1.3;
    --text-h2--letter-spacing: -0.01em;
    --text-h2--font-weight: 700;

    --text-h3: 20px;
    --text-h3--line-height: 1.4;
    --text-h3--font-weight: 600;

    --text-body-lg: 17px;
    --text-body-lg--line-height: 1.7;
    --text-body-lg--font-weight: 400;

    --text-body-md: 15px;
    --text-body-md--line-height: 1.6;
    --text-body-md--font-weight: 400;

    --text-label-sm: 13px;
    --text-label-sm--line-height: 1.3;
    --text-label-sm--letter-spacing: 0.04em;
    --text-label-sm--font-weight: 600;

    --text-caption: 12px;
    --text-caption--line-height: 1.4;
    --text-caption--font-weight: 500;

    /* Box Shadow */
    --shadow-card: 0 1px 3px 0 rgba(0,0,0,.06), 0 4px 16px 0 rgba(5,31,32,.07);
    --shadow-card-hover: 0 4px 8px 0 rgba(0,0,0,.06), 0 12px 32px 0 rgba(5,31,32,.12);
    --shadow-sidebar: 4px 0 32px 0 rgba(5,31,32,.18);
    --shadow-topbar: 0 1px 0 0 #E2E8F0;

    /* Border Radius */
    --radius-sm: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.25rem;
    --radius-2xl: 1.5rem;
    --radius-full: 9999px;
    --radius: 0.75rem;
CSS;

// Append missingConfig just before the closing '}' of @theme
if (!str_contains($cssContent, '--spacing-margin-mobile')) {
    $cssContent = preg_replace('/}(?!.*})/', $missingConfig . "\n}", $cssContent);
    file_put_contents($cssPath, $cssContent);
    echo "Added missing configuration to app.css.\n";
} else {
    echo "Config already exists in app.css.\n";
}
