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
</head>
<body>
    <div class="auth-layout" style="background: linear-gradient(135deg, var(--text-dark) 0%, var(--brown) 100%);">
        <div class="auth-card">
            <div class="logo" style="color: var(--brown);">🐐 Admin Portal</div>
            <h2 class="text-center" style="color: var(--text-dark);">Login Admin</h2>
            <p class="text-center mb-2" style="color: var(--text-light);">Manajemen sistem Goat-In.</p>
            
            <form id="admin-login-form" action="{{ route('admin.login.submit') }}" method="POST">
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
                background:rgba(5,31,32,0.50); backdrop-filter:blur(5px);
                align-items:center; justify-content:center; flex-direction:column; gap:16px;">
        <div style="position:relative; width:72px; height:72px;">
            <div style="position:absolute; inset:-6px; border-radius:50%;
                        border:2px solid rgba(35,83,71,0.18); animation:gpl-pulse 2s ease-in-out infinite;"></div>
            <div style="position:absolute; inset:0; border-radius:50%;
                        border:4px solid transparent;
                        border-top-color:#235347; border-right-color:#235347;
                        animation:gpl-spin 0.8s linear infinite;"></div>
            <div style="position:absolute; inset:10px; border-radius:50%;
                        border:1.5px dashed rgba(35,83,71,0.35);
                        animation:gpl-spin 4s linear infinite reverse;"></div>
            <div style="position:absolute; inset:18px; border-radius:50%;
                        background:rgba(35,83,71,0.1); display:flex;
                        align-items:center; justify-content:center;">
                <img src="{{ asset('images/favicon-32.png?v=3') }}" alt="" style="width:20px;height:20px;object-fit:cover;border-radius:50%;opacity:0.85;">
            </div>
        </div>
        <p style="color:#8EB69B; font-size:10px; font-weight:700; letter-spacing:0.2em;
                  text-transform:uppercase; animation:gpl-pulse 1.5s ease-in-out infinite;">Memuat...</p>
    </div>
    <style>
        @keyframes gpl-spin  { to { transform: rotate(360deg); } }
        @keyframes gpl-pulse { 0%,100%{opacity:.5;} 50%{opacity:1;} }
    </style>
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

            document.getElementById('admin-login-form').addEventListener('submit', function(e) {
                showLoader();
            });

            window.addEventListener('pageshow', function() {
                hideLoader();
            });
        })();
    </script>
</body>
</html>
