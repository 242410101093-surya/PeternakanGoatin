<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Goatin - @yield('title', 'Dashboard')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png?v=3') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png?v=3') }}">
    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Tailwind CSS -->
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
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1E293B;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
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
            box-shadow: 0 4px 30px rgba(5, 31, 32, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(5, 31, 32, 0.07), 0 1px 3px rgba(0, 0, 0, 0.02);
            border-color: rgba(42, 120, 68, 0.2);
        }

        /* ── UI Buttons ── */
        .btn-batal {
            background-color: transparent !important;
            color: #ef4444 !important;
            border: 1.5px solid #ef4444 !important;
            transition: all 0.3s ease !important;
        }
        .btn-batal:hover, .btn-batal:focus, .btn-batal:active {
            background-color: #ef4444 !important;
            color: white !important;
            border-color: #ef4444 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
        }

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
            color: #051F20;
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
            box-shadow: 0 4px 12px rgba(5, 31, 32, 0.05);
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

        /* ── Premium Table for Customer ── */
        .premium-table-cust th {
            background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);
            color: #E2E8F0;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(5, 31, 32, 0.3);
        }
        .premium-table-cust td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
        }
        .premium-table-cust tr:hover td {
            background: rgba(204, 235, 206, 0.5);
        }
        .premium-table-cust tr:last-child td {
            border-bottom: none;
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

        /* ── Modern Modal Animations ── */
        @keyframes modalFadeIn {
            from { opacity: 0; background-color: rgba(0, 0, 0, 0); backdrop-filter: blur(0px); }
            to { opacity: 1; background-color: rgba(0, 0, 0, 0.45); backdrop-filter: blur(4px); }
        }
        @keyframes modalFadeOut {
            from { opacity: 1; background-color: rgba(0, 0, 0, 0.45); backdrop-filter: blur(4px); }
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

        /* ── Custom Validation Tooltip ── */
        .custom-val-tooltip {
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            background: #DC2626;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
            animation: bounceIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }
        .custom-val-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: #DC2626 transparent transparent transparent;
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: translateX(-50%) scale(0.9) translateY(10px); }
            100% { opacity: 1; transform: translateX(-50%) scale(1) translateY(0); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-text-body min-h-screen flex flex-col pt-[100px] w-full overflow-x-hidden">

    <!-- Floating Capsule Navbar -->
    @include('partials.customer.navbar')

    <!-- Main Content Area -->
    <div class="flex-grow fade-in">
        @yield('content')
    </div>

    <!-- Premium Footer -->
    @include('partials.customer.footer')

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(255,255,255,0.3); backdrop-filter:blur(2px);
                align-items:center; justify-content:center;">
        @include('partials.modern_loader')
    </div>
    <script>
        (function() {
            const loader = document.getElementById('global-page-loader');
            function showLoader() { loader.style.display = 'flex'; }
            function hideLoader() { loader.style.display = 'none'; }

            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') ||
                    href.startsWith('mailto') || href.startsWith('tel') ||
                    link.target === '_blank' || link.hasAttribute('download') ||
                    href.includes('/export-pdf') || href.includes('.pdf')) return;
                
                // Wait for other listeners to execute and check if default was prevented
                setTimeout(() => {
                    if (!e.defaultPrevented) {
                        showLoader();
                    }
                }, 10);
            });

            // Automatically disable native validation globally
            document.querySelectorAll('form').forEach(f => f.setAttribute('novalidate', 'true'));

            document.addEventListener('submit', function(e) {
                const form = e.target;
                
                // --- CUSTOM HTML5 REQUIRED VALIDATION ---
                const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                let isHtml5Valid = true;
                
                for (let i = requiredInputs.length - 1; i >= 0; i--) {
                    const input = requiredInputs[i];
                    if (!input.value.trim() || (input.type === 'checkbox' && !input.checked)) {
                        isHtml5Valid = false;
                        
                        form.querySelectorAll('.custom-val-tooltip').forEach(el => el.remove());

                        const errorMsg = input.getAttribute('data-error-msg') || 'Harap isi semua kolom';

                        const tooltip = document.createElement('div');
                        tooltip.className = 'custom-val-tooltip';
                        tooltip.innerHTML = `<span class="material-symbols-outlined text-[16px] animate-pulse">error</span> ${errorMsg}`;
                        
                        const container = input.parentElement;
                        const originalPos = window.getComputedStyle(container).position;
                        if (originalPos === 'static') container.style.position = 'relative';
                        container.appendChild(tooltip);
                        
                        setTimeout(() => {
                            if (tooltip.parentElement) {
                                tooltip.style.animation = 'bounceIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) reverse forwards';
                                setTimeout(() => tooltip.remove(), 200);
                            }
                        }, 3500);
                        
                        input.focus();
                        input.addEventListener('input', function onInput() {
                            if (tooltip.parentElement) tooltip.remove();
                            input.removeEventListener('input', onInput);
                        });
                    }
                }
                
                if (!isHtml5Valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
                // --- END CUSTOM HTML5 REQUIRED VALIDATION ---

                if (e.defaultPrevented) return;
                showLoader();
            }, true);

            window.addEventListener('pageshow', hideLoader);
            window.addEventListener('load', hideLoader);
        })();

        // Modern Centered Modal JS Engine (Customer)
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
    </script>
    
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
    </script>

    @stack('modals')

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>

</body>
</html>
