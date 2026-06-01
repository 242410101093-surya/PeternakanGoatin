<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary-fixed": "#0d2c17",
                        "on-surface": "#1a1c1a",
                        "surface-variant": "#e8efe6",
                        "surface-container-low": "#f8f9f4",
                        "background": "#f8f7ed",
                        "inverse-primary": "#d8edd7",
                        "surface-container-high": "#e5efe2",
                        "on-secondary-fixed-variant": "#2d4f28",
                        "secondary-container": "#dff2dd",
                        "on-background": "#1a1c1a",
                        "outline": "#9fae9a",
                        "secondary-fixed": "#dff2dd",
                        "secondary-fixed-dim": "#bdd9b2",
                        "primary-fixed-dim": "#b7dbb8",
                        "on-tertiary-fixed-variant": "#5f3f15",
                        "error-container": "#ffdad6",
                        "error": "#ba1a1a",
                        "secondary": "#5f984d",
                        "on-primary-fixed-variant": "#203c23",
                        "on-error-container": "#93000a",
                        "primary-container": "#4e7f58",
                        "surface-bright": "#ffffff",
                        "surface-container": "#f2f6f0",
                        "primary": "#1e4e2f",
                        "outline-variant": "#c7d0c0",
                        "inverse-on-surface": "#f4f5f1",
                        "tertiary-fixed-dim": "#f7d69a",
                        "surface-tint": "#3f7a50",
                        "on-tertiary": "#1f2b0b",
                        "on-tertiary-fixed": "#4d2d0a",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#e8efe6",
                        "on-primary": "#ffffff",
                        "surface-dim": "#dadacc",
                        "tertiary-fixed": "#fde8c1",
                        "on-secondary-container": "#16431b",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-container": "#2f1d07",
                        "inverse-surface": "#1a1c1a",
                        "tertiary": "#d89e2a",
                        "tertiary-container": "#fde4a9",
                        "surface": "#ffffff",
                        "on-primary-container": "#0f2a10",
                        "on-surface-variant": "#525f52",
                        "on-error": "#ffffff",
                        "primary-fixed": "#d8efd8",
                        "on-primary-fixed": "#0f2b10"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-xs": "4px",
                        "margin-mobile": "16px",
                        "stack-sm": "8px",
                        "gutter": "24px",
                        "stack-lg": "32px",
                        "unit": "8px",
                        "container-max": "1280px",
                        "stack-md": "16px",
                        "stack-xl": "64px",
                        "margin-desktop": "40px"
                    },
                    "fontFamily": {
                        "body-lg": ["Manrope"],
                        "caption": ["Manrope"],
                        "body-md": ["Manrope"],
                        "h3": ["Manrope"],
                        "h1": ["Manrope"],
                        "h2": ["Manrope"],
                        "label-sm": ["Manrope"]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; background-color: #f9f9f9; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <!-- Existing styles for backward compatibility -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">
    @include('partials.customer.navbar')

    @yield('content')

    @include('partials.customer.footer')
</body>
</html>
