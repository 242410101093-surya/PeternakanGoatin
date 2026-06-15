<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Goat-In</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png?v=3') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png?v=3') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png?v=3') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        /* custom validation tooltip */
        .custom-val-tooltip {
            position: absolute;
            top: -42px;
            left: 10px;
            background: #ef4444; /* red-500 */
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 50;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
            animation: bounceIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .custom-val-tooltip::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 20px;
            width: 12px;
            height: 12px;
            background: #ef4444;
            transform: rotate(45deg);
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: translateY(10px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden">
    <div class="auth-layout" style="background: linear-gradient(135deg, var(--text-dark) 0%, var(--brown) 100%);">
        <div class="auth-card">
            <div class="logo" style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <img src="{{ asset('images/logo-auth.png') }}" alt="Goatin Logo" style="height: 36px; width: auto; object-fit: contain; display: block;">
            </div>
            <h2 class="text-center" style="color: var(--text-dark);">Login Admin</h2>
            <p class="text-center mb-2" style="color: var(--text-light);">Manajemen sistem Goat-In.</p>
            
            <form id="admin-login-form" action="{{ route('admin.login.submit') }}" method="POST" novalidate>
                @csrf
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="email" class="form-control" placeholder="Masukkan Username" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-secondary" style="width: 100%;">Login Admin</button>
            </form>
            
            <p class="text-center mt-2"><a href="{{ route('customer.login') }}" style="color: var(--text-light); font-size: 0.9rem;">Kembali ke Portal Pelanggan</a></p>
        </div>
    </div>

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
                    link.target === '_blank' || link.hasAttribute('download')) return;
                showLoader();
            });

            const adminLoginForm = document.getElementById('admin-login-form');
            adminLoginForm.addEventListener('submit', function(e) {
                // Clear any existing custom tooltips
                document.querySelectorAll('.custom-val-tooltip').forEach(el => el.remove());
                
                let isValid = true;
                const requiredInputs = adminLoginForm.querySelectorAll('input[required]');
                
                // Reverse loop so the first empty input gets focus
                for (let i = requiredInputs.length - 1; i >= 0; i--) {
                    const input = requiredInputs[i];
                    if (!input.value.trim()) {
                        isValid = false;
                        
                        const tooltip = document.createElement('div');
                        tooltip.className = 'custom-val-tooltip';
                        tooltip.innerHTML = '<span class="material-symbols-outlined text-[16px]">error</span> Harap isi semua kolom';
                        
                        const container = input.closest('.form-group');
                        container.style.position = 'relative';
                        container.appendChild(tooltip);
                        
                        // Auto-hide after 3.5s
                        setTimeout(() => {
                            if (tooltip.parentElement) {
                                tooltip.style.animation = 'bounceIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) reverse forwards';
                                setTimeout(() => tooltip.remove(), 200);
                            }
                        }, 3500);
                        
                        input.focus();
                        
                        // Remove tooltip on typing
                        input.addEventListener('input', function onInput() {
                            if (tooltip.parentElement) tooltip.remove();
                            input.removeEventListener('input', onInput);
                        });
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                
                showLoader();
            });

            window.addEventListener('pageshow', function() {
                hideLoader();
            });
        })();
    </script>
</body>
</html>
