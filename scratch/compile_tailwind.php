<?php

$js = file_get_contents('c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\scratch\\old_tailwind_config.js');

// Helper to convert JS object to CSS vars
function extractBlock($name, $js) {
    preg_match("/$name:\s*\{([^}]*)\}/s", $js, $matches);
    if (!$matches) return [];
    $block = $matches[1];
    $lines = explode("\n", $block);
    $vars = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line || str_starts_with($line, '//') || str_starts_with($line, '/*')) continue;
        if (preg_match('/"([^"]+)"\s*:\s*([^,]+)/', $line, $m)) {
            $key = $m[1];
            $val = trim($m[2], ' "\',');
            $vars[$key] = $val;
        }
    }
    return $vars;
}

$colors = extractBlock('colors', $js);
$borderRadius = extractBlock('borderRadius', $js);
$spacing = extractBlock('spacing', $js);
$boxShadow = extractBlock('boxShadow', $js);

// Custom extract for font sizes
preg_match("/fontSize:\s*\{([^}]+)\}/s", $js, $fsMatches);
$fontSizes = [];
if ($fsMatches) {
    preg_match_all('/"([^"]+)"\s*:\s*\["([^"]+)",\s*\{\s*lineHeight:\s*"([^"]+)",\s*letterSpacing:\s*"([^"]+)",\s*fontWeight:\s*"([^"]+)"\s*\}\]/s', $fsMatches[1], $m, PREG_SET_ORDER);
    foreach ($m as $match) {
        $fontSizes[$match[1]] = [
            'size' => $match[2],
            'lineHeight' => $match[3],
            'letterSpacing' => $match[4],
            'fontWeight' => $match[5]
        ];
    }
}

// Custom extract for fonts
preg_match("/fontFamily:\s*\{([^}]+)\}/s", $js, $ffMatches);
$fonts = [];
if ($ffMatches) {
    preg_match_all('/"([^"]+)"\s*:\s*\[([^\]]+)\]/s', $ffMatches[1], $m, PREG_SET_ORDER);
    foreach ($m as $match) {
        $fonts[$match[1]] = trim($match[2]);
    }
}

$css = "@theme {\n";

$css .= "    /* Colors */\n";
foreach ($colors as $k => $v) {
    $css .= "    --color-$k: $v;\n";
}

$css .= "\n    /* Border Radius */\n";
foreach ($borderRadius as $k => $v) {
    if ($k === 'DEFAULT') {
        $css .= "    --radius: $v;\n";
    } else {
        $css .= "    --radius-$k: $v;\n";
    }
}

$css .= "\n    /* Spacing */\n";
foreach ($spacing as $k => $v) {
    if ($k === 'container-max') {
        $v = '100%'; // Make it wide by default!
    }
    $css .= "    --spacing-$k: $v;\n";
}

$css .= "\n    /* Box Shadow */\n";
foreach ($boxShadow as $k => $v) {
    // The JS value might be wrapped in quotes
    $v = str_replace(['"', "'"], "", $v);
    $css .= "    --shadow-$k: $v;\n";
}

$css .= "\n    /* Font Family */\n";
foreach ($fonts as $k => $v) {
    $v = str_replace(["'", '"'], "", $v);
    $css .= "    --font-$k: \"$v\";\n";
}

$css .= "\n    /* Font Size */\n";
foreach ($fontSizes as $k => $v) {
    $css .= "    --text-$k: {$v['size']};\n";
    $css .= "    --text-$k--line-height: {$v['lineHeight']};\n";
    if ($v['letterSpacing'] !== '0') {
        $css .= "    --text-$k--letter-spacing: {$v['letterSpacing']};\n";
    }
    $css .= "    --text-$k--font-weight: {$v['fontWeight']};\n";
}

$css .= "}\n";

$target = 'c:\\Users\\M RIDWAN SURYA PUTRA\\Goatin-1-1\\resources\\css\\app.css';
$content = file_get_contents($target);
$content = preg_replace('/@theme\s*\{.*?\}/s', $css, $content);
file_put_contents($target, $content);
echo "Done.\n";
