<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin Admin - @yield('title', 'Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon-32.png') }}">
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              /* ── Brand Palette 2026 ── */
              "primary-dark":    "#0E3247",
              "primary-dark-80": "#1a4a63",
              "primary-dark-60": "#2a6382",
              "primary-green":   "#2A7844",
              "primary-green-80":"#338f52",
              "primary-green-10":"#f0faf3",
              "off-white":       "#F8FAFC",
              "border-subtle":   "#E2E8F0",
              "text-muted":      "#64748B",
              "text-body":       "#1E293B",
              "text-heading":    "#0E3247",
              /* ── Status Colors ── */
              "status-green":    "#2A7844",
              "status-green-bg": "#DCFCE7",
              "status-amber":    "#D97706",
              "status-amber-bg": "#FEF3C7",
              "status-red":      "#DC2626",
              "status-red-bg":   "#FEE2E2",
              "status-blue":     "#2563EB",
              "status-blue-bg":  "#DBEAFE",
              /* ── Legacy compatibility ── */
              "surface-bright":              "#ffffff",
              "on-secondary-container":      "#16431b",
              "inverse-surface":             "#1a1c1a",
              "tertiary-fixed-dim":          "#f7d69a",
              "on-primary-fixed":            "#0f2b10",
              "error-container":             "#ffdad6",
              "tertiary-fixed":              "#fde8c1",
              "on-secondary":                "#ffffff",
              "on-secondary-fixed":          "#0d2c17",
              "background":                  "#F8FAFC",
              "secondary-fixed":             "#dff2dd",
              "surface-container-highest":   "#e8efe6",
              "on-tertiary-fixed":           "#4d2d0a",
              "surface-dim":                 "#dadacc",
              "inverse-primary":             "#d8edd7",
              "on-primary-container":        "#0f2a10",
              "on-primary":                  "#ffffff",
              "surface-container-low":       "#f8f9f4",
              "on-primary-fixed-variant":    "#203c23",
              "inverse-on-surface":          "#f4f5f1",
              "error":                       "#ba1a1a",
              "primary":                     "#2A7844",
              "secondary-fixed-dim":         "#bdd9b2",
              "on-tertiary-fixed-variant":   "#5f3f15",
              "on-tertiary-container":       "#2f1d07",
              "tertiary":                    "#d89e2a",
              "primary-fixed-dim":           "#b7dbb8",
              "outline":                     "#94a3b8",
              "on-surface-variant":          "#64748B",
              "surface":                     "#ffffff",
              "surface-variant":             "#E2E8F0",
              "surface-container-lowest":    "#ffffff",
              "surface-tint":                "#2A7844",
              "primary-container":           "#2A7844",
              "surface-container":           "#f1f5f9",
              "primary-fixed":               "#dcfce7",
              "tertiary-container":          "#fde4a9",
              "secondary-container":         "#dff2dd",
              "on-tertiary":                 "#1f2b0b",
              "on-surface":                  "#1E293B",
              "on-error":                    "#ffffff",
              "secondary":                   "#2A7844",
              "on-secondary-fixed-variant":  "#2d4f28",
              "outline-variant":             "#E2E8F0",
              "surface-container-high":      "#e5efe2",
              "on-error-container":          "#93000a",
              "on-background":               "#1E293B",
            },
            borderRadius: {
              "DEFAULT": "0.75rem",
              "sm":      "0.5rem",
              "md":      "0.75rem",
              "lg":      "1rem",
              "xl":      "1.25rem",
              "2xl":     "1.5rem",
              "full":    "9999px",
            },
            spacing: {
              "margin-mobile":  "16px",
              "margin-desktop": "32px",
              "gutter":         "24px",
              "stack-xs":       "4px",
              "stack-sm":       "8px",
              "stack-md":       "16px",
              "stack-lg":       "32px",
              "stack-xl":       "56px",
              "unit":           "8px",
              "container-max":  "1280px",
            },
            fontFamily: {
              "sans":    ['"Plus Jakarta Sans"', 'sans-serif'],
              "body-md": ['"Plus Jakarta Sans"', 'sans-serif'],
              "body-lg": ['"Plus Jakarta Sans"', 'sans-serif'],
              "label-sm":['"Plus Jakarta Sans"', 'sans-serif'],
              "caption": ['"Plus Jakarta Sans"', 'sans-serif'],
              "h1":      ['"Plus Jakarta Sans"', 'sans-serif'],
              "h2":      ['"Plus Jakarta Sans"', 'sans-serif'],
              "h3":      ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            fontSize: {
              "h1":       ["36px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "800" }],
              "h2":       ["28px", { lineHeight: "1.3", letterSpacing: "-0.01em", fontWeight: "700" }],
              "h3":       ["20px", { lineHeight: "1.4", letterSpacing: "0",       fontWeight: "600" }],
              "body-lg":  ["17px", { lineHeight: "1.7", letterSpacing: "0",       fontWeight: "400" }],
              "body-md":  ["15px", { lineHeight: "1.6", letterSpacing: "0",       fontWeight: "400" }],
              "label-sm": ["13px", { lineHeight: "1.3", letterSpacing: "0.04em",  fontWeight: "600" }],
              "caption":  ["12px", { lineHeight: "1.4", letterSpacing: "0",       fontWeight: "500" }],
            },
            boxShadow: {
              "card":  "0 1px 3px 0 rgba(0,0,0,.06), 0 4px 16px 0 rgba(14,50,71,.07)",
              "card-hover": "0 4px 8px 0 rgba(0,0,0,.06), 0 12px 32px 0 rgba(14,50,71,.12)",
              "sidebar":"4px 0 32px 0 rgba(14,50,71,.18)",
              "topbar": "0 1px 0 0 #E2E8F0",
            }
          }
        }
      }
    </script>
    <style>
      /* ── Global Reset ── */
      *, *::before, *::after { box-sizing: border-box; }
      body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #F8FAFC;
        color: #1E293B;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
      }

      /* ── Material Icons ── */
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .material-symbols-outlined.fill {
        font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
      }

      /* ── Premium Card ── */
      .card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(14,50,71,.07);
        transition: box-shadow .2s ease, transform .2s ease;
      }
      .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,.06), 0 12px 32px rgba(14,50,71,.12);
        transform: translateY(-1px);
      }

      /* ── Glassmorphism Sidebar ── */
      .sidebar-glass {
        background: linear-gradient(180deg, #0E3247 0%, #0a2539 100%);
        box-shadow: 4px 0 32px rgba(14,50,71,.25);
      }

      /* ── Green CTA Button ── */
      .btn-primary {
        background-color: #2A7844;
        color: #ffffff;
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 14px;
        transition: background .18s ease, box-shadow .18s ease, transform .12s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }
      .btn-primary:hover {
        background-color: #1e5c33;
        box-shadow: 0 4px 16px rgba(42,120,68,.30);
        transform: translateY(-1px);
      }
      .btn-primary:active { transform: translateY(0); }

      .btn-secondary {
        background-color: #F8FAFC;
        color: #0E3247;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 14px;
        transition: all .18s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }
      .btn-secondary:hover {
        background-color: #EEF2F7;
        border-color: #CBD5E1;
      }

      /* ── Input Focus Glow ── */
      input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #2A7844 !important;
        box-shadow: 0 0 0 3px rgba(42,120,68,.15);
      }

      /* ── Status Badges ── */
      .badge-green  { background:#DCFCE7; color:#166534; border-radius:999px; padding:2px 10px; font-size:12px; font-weight:600; }
      .badge-amber  { background:#FEF3C7; color:#92400E; border-radius:999px; padding:2px 10px; font-size:12px; font-weight:600; }
      .badge-red    { background:#FEE2E2; color:#991B1B; border-radius:999px; padding:2px 10px; font-size:12px; font-weight:600; }
      .badge-blue   { background:#DBEAFE; color:#1E40AF; border-radius:999px; padding:2px 10px; font-size:12px; font-weight:600; }
      .badge-gray   { background:#F1F5F9; color:#475569; border-radius:999px; padding:2px 10px; font-size:12px; font-weight:600; }

      /* ── Table ── */
      .premium-table th {
        background: #F8FAFC;
        color: #64748B;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: .05em;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1px solid #E2E8F0;
      }
      .premium-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 14px;
        color: #1E293B;
        vertical-align: middle;
      }
      .premium-table tr:hover td {
        background: #F8FAFC;
      }
      .premium-table tr:last-child td {
        border-bottom: none;
      }

      /* ── Scrollbar ── */
      ::-webkit-scrollbar { width: 5px; height: 5px; }
      ::-webkit-scrollbar-track { background: transparent; }
      ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
      ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

      /* ── Animate ── */
      @keyframes fadeInUp {
        from { opacity:0; transform:translateY(12px); }
        to   { opacity:1; transform:translateY(0); }
      }
      .fade-in { animation: fadeInUp .35s ease forwards; }
    </style>
</head>
<body class="bg-off-white text-text-body min-h-screen flex">

    <!-- Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">

        <!-- Top Navbar -->
        @include('partials.admin.navbar')

        <!-- Canvas / Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

    </div>
</body>
</html>
