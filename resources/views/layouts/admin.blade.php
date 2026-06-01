<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin Admin - @yield('title', 'Dashboard')</title>
    <!-- Fonts and Icons -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-bright": "#ffffff",
                    "on-secondary-container": "#16431b",
                    "inverse-surface": "#1a1c1a",
                    "tertiary-fixed-dim": "#f7d69a",
                    "on-primary-fixed": "#0f2b10",
                    "error-container": "#ffdad6",
                    "tertiary-fixed": "#fde8c1",
                    "on-secondary": "#ffffff",
                    "on-secondary-fixed": "#0d2c17",
                    "background": "#f8f7ed",
                    "secondary-fixed": "#dff2dd",
                    "surface-container-highest": "#e8efe6",
                    "on-tertiary-fixed": "#4d2d0a",
                    "surface-dim": "#dadacc",
                    "inverse-primary": "#d8edd7",
                    "on-primary-container": "#0f2a10",
                    "on-primary": "#ffffff",
                    "surface-container-low": "#f8f9f4",
                    "on-primary-fixed-variant": "#203c23",
                    "inverse-on-surface": "#f4f5f1",
                    "error": "#ba1a1a",
                    "primary": "#1e4e2f",
                    "secondary-fixed-dim": "#bdd9b2",
                    "on-tertiary-fixed-variant": "#5f3f15",
                    "on-tertiary-container": "#2f1d07",
                    "tertiary": "#d89e2a",
                    "primary-fixed-dim": "#b7dbb8",
                    "outline": "#9fae9a",
                    "on-surface-variant": "#525f52",
                    "surface": "#ffffff",
                    "surface-variant": "#e8efe6",
                    "surface-container-lowest": "#ffffff",
                    "surface-tint": "#3f7a50",
                    "primary-container": "#4e7f58",
                    "surface-container": "#f2f6f0",
                    "primary-fixed": "#d8efd8",
                    "tertiary-container": "#fde4a9",
                    "secondary-container": "#dff2dd",
                    "on-tertiary": "#1f2b0b",
                    "on-surface": "#1a1c1a",
                    "on-error": "#ffffff",
                    "secondary": "#5f984d",
                    "on-secondary-fixed-variant": "#2d4f28",
                    "outline-variant": "#c7d0c0",
                    "surface-container-high": "#e5efe2",
                    "on-error-container": "#93000a",
                    "on-background": "#1a1c1a"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "margin-mobile": "16px",
                    "gutter": "24px",
                    "stack-xl": "64px",
                    "unit": "8px",
                    "stack-sm": "8px",
                    "container-max": "1280px",
                    "stack-xs": "4px",
                    "stack-lg": "32px",
                    "stack-md": "16px",
                    "margin-desktop": "40px"
            },
            "fontFamily": {
                    "h3": ["Manrope"],
                    "h2": ["Manrope"],
                    "body-md": ["Manrope"],
                    "body-lg": ["Manrope"],
                    "label-sm": ["Manrope"],
                    "h1": ["Manrope"],
                    "caption": ["Manrope"]
            },
            "fontSize": {
                    "h3": ["24px", {"lineHeight": "1.4", "letterSpacing": "0", "fontWeight": "600"}],
                    "h2": ["32px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "letterSpacing": "0", "fontWeight": "400"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "letterSpacing": "0", "fontWeight": "400"}],
                    "label-sm": ["14px", {"lineHeight": "1.2", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "h1": ["40px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "caption": ["12px", {"lineHeight": "1.4", "letterSpacing": "0", "fontWeight": "500"}]
            }
          }
        }
      }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
        .ambient-shadow { box-shadow: 4px 4px 24px rgba(30, 78, 47, 0.12); }
        .ambient-shadow-hover:hover { box-shadow: 0px 8px 32px rgba(30, 78, 47, 0.15); transform: translateY(-2px); transition: all 0.3s ease; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #e2e2e2; border-radius: 10px; }
    </style>
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen flex">

    <!-- Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">

        <!-- Top Navbar -->
        @include('partials.admin.navbar')

        <!-- Canvas / Page Content -->
        @yield('content')

    </div>
</body>
</html>
