<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Login</title>
    <!-- Google Fonts: Manrope -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Configuration -->
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
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "1.6", "letterSpacing": "0", "fontWeight": "400"}],
                        "caption": ["12px", {"lineHeight": "1.4", "letterSpacing": "0", "fontWeight": "500"}],
                        "body-md": ["16px", {"lineHeight": "1.6", "letterSpacing": "0", "fontWeight": "400"}],
                        "h3": ["24px", {"lineHeight": "1.4", "letterSpacing": "0", "fontWeight": "600"}],
                        "h1": ["40px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "h2": ["32px", {"lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-sm": ["14px", {"lineHeight": "1.2", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-on-surface font-body-md text-body-md antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Centered Form Area -->
    <div class="w-full max-w-md bg-surface-container-lowest p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgba(74,124,89,0.12)] border border-surface-variant">

        <!-- Brand Logo -->
        <div class="flex items-center justify-center gap-stack-sm mb-stack-xl">
            <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">pets</span>
            </div>
            <span class="font-h2 text-h2 text-primary">Goatin</span>
        </div>

        <!-- Welcome Text -->
        <div class="mb-stack-lg text-center">
            <h1 class="font-h2 text-h2 text-on-surface mb-stack-xs">Selamat Datang</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Silakan masuk untuk melanjutkan pemantauan.</p>
        </div>

        @if(session('status'))
            <div class="mb-stack-md rounded-lg border border-primary-container bg-primary-fixed-dim p-4 text-sm text-on-secondary-container">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.submit') }}" class="space-y-stack-md" method="POST">
            @csrf
            <!-- Username Input -->
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-stack-xs" for="email">
                    Username / Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline-variant">person</span>
                    </div>
                    <input class="block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-lg bg-surface-container-low text-on-surface placeholder-outline-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors sm:text-sm" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan username" required="" type="text"/>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div>
                <div class="flex items-center justify-between mb-stack-xs">
                    <label class="block font-label-sm text-label-sm text-on-surface-variant" for="password">
                        Password
                    </label>
                    <a class="font-label-sm text-label-sm text-secondary hover:text-primary transition-colors" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline-variant">lock</span>
                    </div>
                    <input autocomplete="current-password" class="block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-lg bg-surface-container-low text-on-surface placeholder-outline-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors sm:text-sm" id="password" name="password" placeholder="••••••••" required="" type="password"/>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center pt-stack-xs pb-stack-sm">
                <input class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded bg-surface-container-low" id="remember-me" name="remember-me" type="checkbox"/>
                <label class="ml-2 block font-body-md text-body-md text-on-surface-variant" for="remember-me">
                    Ingat saya
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg font-label-sm text-label-sm text-on-primary bg-primary hover:bg-primary-container hover:text-on-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-[0_4px_20px_rgba(74,124,89,0.1)]" type="submit">
                    Masuk
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-stack-md text-center">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary font-semibold hover:text-primary-container hover:underline transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>

</body>
</html>
