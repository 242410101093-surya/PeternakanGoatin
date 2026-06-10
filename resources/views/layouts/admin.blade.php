<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin Admin - @yield('title', 'Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png?v=3') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png?v=3') }}">
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              /* ── Brand Palette 2026 – Forest Green ── */
              "primary-dark":    "#051F20",
              "primary-dark-80": "#0B2B26",
              "primary-dark-60": "#163832",
              "primary-green":   "#235347",
              "primary-green-80":"#2d6b5a",
              "primary-green-10":"#ffffff",
              "off-white":       "#ffffff",
              "border-subtle":   "#E2E8F0",
              "text-muted":      "#64748B",
              "text-body":       "#1E293B",
              "text-heading":    "#051F20",
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
              "background":                  "#ffffff",
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
              "card":  "0 1px 3px 0 rgba(0,0,0,.06), 0 4px 16px 0 rgba(5,31,32,.07)",
              "card-hover": "0 4px 8px 0 rgba(0,0,0,.06), 0 12px 32px 0 rgba(5,31,32,.12)",
              "sidebar":"4px 0 32px 0 rgba(5,31,32,.18)",
              "topbar": "0 1px 0 0 #E2E8F0",
            }
          }
        }
      }
    </script>
    <style>
      /* ── Premium Glassmorphic Toast ── */
      .premium-toast {
          background: rgba(255, 255, 255, 0.9);
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border: 1px solid rgba(226, 232, 240, 0.8);
          border-radius: 16px;
          padding: 14px 20px;
          box-shadow: 0 10px 30px rgba(5, 31, 32, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
          display: flex;
          align-items: center;
          gap: 12px;
          transform: translateX(120%);
          transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
          pointer-events: auto;
          opacity: 0;
      }
      .premium-toast.show {
          transform: translateX(0);
          opacity: 1;
      }
      /* ── Global Reset ── */
      *, *::before, *::after { box-sizing: border-box; }
      body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #ffffff;
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
        box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(5,31,32,.07);
        transition: box-shadow .2s ease, transform .2s ease;
      }
      .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,.06), 0 12px 32px rgba(5,31,32,.12);
        transform: translateY(-1px);
      }

      /* ── Glassmorphism Sidebar ── */
      .sidebar-glass {
        background: linear-gradient(180deg, #051F20 0%, #0B2B26 100%);
        box-shadow: 4px 0 32px rgba(5,31,32,.25);
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
        color: #051F20;
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
        background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);
        color: #DAF1DE;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: .05em;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1px solid rgba(5, 31, 32, 0.3);
      }
      .premium-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 14px;
        color: #1E293B;
        vertical-align: middle;
      }
      .premium-table tr:hover td {
        background: #ccebce;
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

      /* ── Modern Modal Animations ── */
      @keyframes modalFadeIn {
        from { opacity: 0; background-color: rgba(0, 0, 0, 0); backdrop-filter: blur(0px); }
        to { opacity: 1; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
      }
      @keyframes modalFadeOut {
        from { opacity: 1; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
        to { opacity: 0; background-color: rgba(0, 0, 0, 0); backdrop-filter: blur(0px); }
      }
      @keyframes modalScaleIn {
        from { transform: scale(0.95) translateY(12px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
      }
      @keyframes modalScaleOut {
        from { transform: scale(1) translateY(0); opacity: 1; }
        to { transform: scale(0.95) translateY(12px); opacity: 0; }
      }

      .animate-modal-backdrop-in {
        animation: modalFadeIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }
      .animate-modal-backdrop-out {
        animation: modalFadeOut 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }
      .animate-modal-content-in {
        animation: modalScaleIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      }
      .animate-modal-content-out {
        animation: modalScaleOut 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }
    </style>
</head>
<body class="bg-white text-text-body min-h-screen flex relative">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden transition-all duration-300"></div>

    <!-- Sidebar -->
    @include('partials.admin.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen w-full overflow-hidden">

        <!-- Top Navbar -->
        @include('partials.admin.navbar')

        <!-- Canvas / Page Content -->
        <main class="flex-1 w-full">
            @yield('content')
        </main>

    </div>

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(5,31,32,0.52); backdrop-filter:blur(5px);
                align-items:center; justify-content:center; flex-direction:column; gap:16px;">
        <div style="position:relative; width:72px; height:72px;">
            <!-- Outer ring pulse -->
            <div style="position:absolute; inset:-6px; border-radius:50%;
                        border:2px solid rgba(35,83,71,0.18); animation:gpl-pulse 2s ease-in-out infinite;"></div>
            <!-- Spinning arc -->
            <div style="position:absolute; inset:0; border-radius:50%;
                        border:4px solid transparent;
                        border-top-color:#235347; border-right-color:#235347;
                        animation:gpl-spin 0.8s linear infinite;"></div>
            <!-- Dashed inner ring -->
            <div style="position:absolute; inset:10px; border-radius:50%;
                        border:1.5px dashed rgba(35,83,71,0.35);
                        animation:gpl-spin 4s linear infinite reverse;"></div>
            <!-- Center goat logo -->
            <div style="position:absolute; inset:18px; border-radius:50%;
                        background:rgba(35,83,71,0.1); display:flex;
                        align-items:center; justify-content:center;">
                <img src="{{ asset('images/favicon-32.png') }}" alt="" style="width:20px;height:20px;object-fit:cover;border-radius:50%;opacity:0.85;">
            </div>
        </div>
        <p style="color:#8EB69B; font-size:10px; font-weight:700; letter-spacing:0.2em;
                  text-transform:uppercase; animation:gpl-pulse 1.5s ease-in-out infinite;">Memuat...</p>
    </div>
    <style>
        @keyframes gpl-spin  { to { transform: rotate(360deg); } }
        @keyframes gpl-pulse { 0%,100%{opacity:.5;} 50%{opacity:1;} }
    </style>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        (function() {
            const loader = document.getElementById('global-page-loader');

            function showLoader() {
                loader.style.display = 'flex';
            }
            function hideLoader() {
                loader.style.display = 'none';
            }

            // Intercept all <a> clicks that navigate away
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                // Skip anchors, javascript:, external, new-tab, and download links
                if (!href || href.startsWith('#') || href.startsWith('javascript') ||
                    href.startsWith('mailto') || href.startsWith('tel') ||
                    link.target === '_blank' || link.hasAttribute('download') ||
                    href.includes('/export-pdf') || href.includes('.pdf')) return;
                showLoader();
            });

            // Intercept all form submissions
            document.addEventListener('submit', function(e) {
                const form = e.target;
                
                // If it is a delete form requiring confirmation
                if (form.classList.contains('delete-form') && !form.dataset.confirmed) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const message = form.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
                    window.openConfirmModal(message, function() {
                        form.dataset.confirmed = 'true';
                        showLoader();
                        form.submit();
                    });
                    return;
                }

                if (e.defaultPrevented) return;
                showLoader();
            });

            // Hide when page is re-shown (back/forward navigation)
            window.addEventListener('pageshow', hideLoader);
            window.addEventListener('load', hideLoader);
        })();


        // Modern Centered Modal JS Engine
        window.openModal = function(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Add entrance animation classes
            modal.classList.remove('animate-modal-backdrop-out');
            modal.classList.add('animate-modal-backdrop-in');
            const content = modal.querySelector('.bg-surface-container-lowest') || modal.querySelector('.modal-content') || modal.querySelector('.bg-white') || modal.firstElementChild;
            if (content) {
                content.classList.remove('animate-modal-content-out');
                content.classList.add('animate-modal-content-in');
            }
        };

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            
            modal.classList.remove('animate-modal-backdrop-in');
            modal.classList.add('animate-modal-backdrop-out');
            
            const content = modal.querySelector('.bg-surface-container-lowest') || modal.querySelector('.modal-content') || modal.querySelector('.bg-white') || modal.firstElementChild;
            if (content) {
                content.classList.remove('animate-modal-content-in');
                content.classList.add('animate-modal-content-out');
            }
            
            function onAnimationEnd(e) {
                if (e.animationName === 'modalFadeOut') {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-modal-backdrop-out');
                    if (content) content.classList.remove('animate-modal-content-out');
                    modal.removeEventListener('animationend', onAnimationEnd);
                }
            }
            modal.addEventListener('animationend', onAnimationEnd);
        };

        // Modern Confirmation Modal Handler
        let currentConfirmCallback = null;
        window.openConfirmModal = function(message, confirmCallback) {
            document.getElementById('globalConfirmMessage').textContent = message;
            currentConfirmCallback = confirmCallback;
            window.openModal('globalConfirmModal');
        };
        window.openDeleteModal = window.openConfirmModal;

        document.addEventListener('DOMContentLoaded', function() {
            const cancelBtn = document.getElementById('globalConfirmCancelBtn');
            const confirmBtn = document.getElementById('globalConfirmBtn');
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    window.closeModal('globalConfirmModal');
                    currentConfirmCallback = null;
                });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    if (currentConfirmCallback) {
                        currentConfirmCallback();
                    }
                    window.closeModal('globalConfirmModal');
                });
            }

            // Intercept individual mark-read forms in navbar
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('.navbar-mark-read-form');
                if (form) {
                    e.preventDefault();
                    e.stopPropagation();
                    const notifId = form.dataset.notifId;
                    const url = form.getAttribute('action');
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast(data.message, 'success');
                            
                            const item = document.getElementById(`navbar-notif-item-${notifId}`);
                            if (item) {
                                item.classList.remove('bg-emerald-50/20');
                                form.remove();
                            }
                            
                            updateGlobalPendingCounts(data.pendingOrders);
                        }
                    })
                    .catch(err => console.error(err));
                }
            });

            // Intercept read-all form in navbar
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('#navbar-read-all-form');
                if (form) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = form.getAttribute('action');
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast(data.message, 'success');
                            
                            document.querySelectorAll('[id^="navbar-notif-item-"]').forEach(item => {
                                item.classList.remove('bg-emerald-50/20');
                            });
                            document.querySelectorAll('.navbar-mark-read-form').forEach(f => f.remove());
                            
                            form.remove();
                            
                            updateGlobalPendingCounts(0);
                        }
                    })
                    .catch(err => console.error(err));
                }
            });

            // Helper to update global pending counts across the page
            window.updateGlobalPendingCounts = function(count) {
                const dot = document.getElementById('navbar-notif-dot');
                if (dot) {
                    if (count > 0) dot.classList.remove('hidden');
                    else dot.classList.add('hidden');
                }
                
                const badge = document.getElementById('navbar-notif-count-badge');
                if (badge) {
                    if (count > 0) {
                        badge.textContent = `${count} Baru`;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                
                const dbCount = document.getElementById('dashboard-pending-orders-count');
                if (dbCount) dbCount.textContent = count;
                
                const dbBadge = document.getElementById('dashboard-pending-orders-badge');
                if (dbBadge) {
                    if (count > 0) dbBadge.classList.remove('hidden');
                    else dbBadge.classList.add('hidden');
                }
                
                const dbAction = document.getElementById('dashboard-pending-orders-action');
                if (dbAction) {
                    const span = dbAction.querySelector('span');
                    if (span) {
                        span.textContent = count > 0 ? 'Konfirmasi Sekarang' : 'Semua dibaca';
                    }
                }
            };
        });
    </script>

    <!-- Global Modern Centered Confirmation Modal -->
    <div id="globalConfirmModal" class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-2xl w-full max-w-md overflow-hidden shadow-2xl border border-surface-variant transform">
            <div class="p-6 text-center space-y-4">
                <!-- Icon container -->
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto shadow-inner">
                    <span class="material-symbols-outlined text-3xl font-semibold">warning</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface">Konfirmasi Tindakan</h3>
                <p id="globalConfirmMessage" class="text-sm text-on-surface-variant font-medium leading-relaxed">Apakah Anda yakin ingin melakukan tindakan ini?</p>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 border-t border-surface-variant">
                <button id="globalConfirmCancelBtn" type="button" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-lg transition-colors font-semibold">Batal</button>
                <button id="globalConfirmBtn" type="button" class="px-4 py-2 text-sm bg-red-600 text-white hover:bg-red-700 rounded-lg transition-all shadow-sm font-semibold">Ya, Hapus</button>
            </div>
        </div>
    </div>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-24 right-4 z-[9999] flex flex-col gap-3 pointer-events-none max-w-sm w-full"></div>
    <script>
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'premium-toast';

            let icon = 'check_circle';
            let iconColor = '#2A7844';
            let borderColor = 'rgba(34, 197, 94, 0.2)';
            let bgColor = 'rgba(220, 252, 231, 0.9)';

            if (type === 'error') {
                icon = 'error';
                iconColor = '#DC2626';
                borderColor = 'rgba(239, 68, 68, 0.2)';
                bgColor = 'rgba(254, 226, 226, 0.9)';
            } else if (type === 'info') {
                icon = 'info';
                iconColor = '#2563EB';
                borderColor = 'rgba(59, 130, 246, 0.2)';
                bgColor = 'rgba(219, 234, 254, 0.9)';
            } else if (type === 'warning') {
                icon = 'warning';
                iconColor = '#D97706';
                borderColor = 'rgba(245, 158, 11, 0.2)';
                bgColor = 'rgba(254, 243, 199, 0.9)';
            }

            toast.style.background = bgColor;
            toast.style.borderColor = borderColor;

            toast.innerHTML = `
                <span class="material-symbols-outlined shrink-0" style="color: ${iconColor}; font-size: 20px;">${icon}</span>
                <span class="text-xs font-bold text-slate-800 leading-normal">${message}</span>
                <button class="ml-auto p-0.5 rounded-lg hover:bg-black/5 shrink-0 transition-colors" onclick="this.parentElement.classList.remove('show'); setTimeout(() => this.parentElement.remove(), 400)">
                    <span class="material-symbols-outlined text-slate-400" style="font-size: 16px;">close</span>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 4000);
        };

        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                window.showToast("{{ session('error') }}", 'error');
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    window.showToast("{{ $error }}", 'error');
                @endforeach
            @endif
        });
    </script>

    @stack('modals')
</body>
</html>
