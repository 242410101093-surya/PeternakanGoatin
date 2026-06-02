<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Goatin - @yield('title', 'Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon-32.png') }}">
    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "primary-green":   "#2A7844",
                        "primary-green-10":"#f0faf3",
                        "off-white":       "#F8FAFC",
                        "border-subtle":   "#E2E8F0",
                        "text-muted":      "#64748B",
                        "text-body":       "#1E293B",
                        "text-heading":    "#0E3247",
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
                    fontFamily: {
                        "sans":    ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(42, 120, 68, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(14, 50, 71, 0.03) 0%, transparent 55%);
            background-attachment: fixed;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
        }

        /* ── Premium Glassmorphism Cards ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(14, 50, 71, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(14, 50, 71, 0.07), 0 1px 3px rgba(0, 0, 0, 0.02);
            border-color: rgba(42, 120, 68, 0.2);
        }

        /* ── UI Buttons ── */
        .btn-premium {
            background: linear-gradient(135deg, #2A7844 0%, #1e5c33 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 10px 22px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(42, 120, 68, 0.25);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-premium:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(42, 120, 68, 0.35);
            filter: brightness(1.05);
        }
        .btn-premium:active {
            transform: translateY(0);
        }

        .btn-premium-secondary {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            color: #0E3247;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 10px 22px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-premium-secondary:hover {
            background: #ffffff;
            border-color: #CBD5E1;
            box-shadow: 0 4px 12px rgba(14, 50, 71, 0.05);
            transform: translateY(-1px);
        }

        /* ── Modern Badges ── */
        .badge-premium-green {
            background: rgba(220, 252, 231, 0.7);
            color: #166534;
            border: 1px solid rgba(34, 197, 94, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-premium-amber {
            background: rgba(254, 243, 199, 0.7);
            color: #92400E;
            border: 1px solid rgba(245, 158, 11, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-premium-red {
            background: rgba(254, 226, 226, 0.7);
            color: #991B1B;
            border: 1px solid rgba(239, 68, 68, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-premium-blue {
            background: rgba(219, 234, 254, 0.7);
            color: #1E40AF;
            border: 1px solid rgba(59, 130, 246, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Input styling ── */
        .premium-input {
            width: 100%;
            padding: 11px 16px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            background: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            color: #1E293B;
            transition: all 0.2s ease;
        }
        .premium-input:focus {
            outline: none;
            border-color: #2A7844 !important;
            box-shadow: 0 0 0 3px rgba(42, 120, 68, 0.12);
            background: #ffffff;
        }

        /* ── Animated Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* ── Dynamic Page Entrances ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-off-white text-text-body min-h-screen flex flex-col pt-[100px]">

    <!-- Floating Capsule Navbar -->
    @include('partials.customer.navbar')

    <!-- Main Content Area -->
    <div class="flex-grow fade-in">
        @yield('content')
    </div>

    <!-- Premium Footer -->
    @include('partials.customer.footer')

</body>
</html>
