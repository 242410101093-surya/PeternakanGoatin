<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Reset Password</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-surface text-on-surface font-body-md text-body-md antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-surface-container-lowest p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgba(74,124,89,0.12)] border border-surface-variant">
        <div class="flex items-center justify-center gap-stack-sm mb-stack-xl">
            <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">lock</span>
            </div>
            <span class="font-h2 text-h2 text-primary">Ganti Password</span>
        </div>

        <div class="mb-stack-lg text-center">
            <h1 class="font-h2 text-h2 text-on-surface mb-stack-xs">Atur Ulang Password</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Masukkan password baru untuk akun Anda.</p>
        </div>

        @if(session('status'))
            <div class="mb-stack-md rounded-lg border border-primary-container bg-primary-fixed-dim p-4 text-sm text-on-secondary-container">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" class="space-y-stack-md" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}"/>

            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-stack-xs" for="password">
                    Password Baru
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-outline-variant">lock</span>
                    </div>
                    <input autocomplete="new-password" class="block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-lg bg-surface-container-low text-on-surface placeholder-outline-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors sm:text-sm" id="password" name="password" placeholder="••••••••" required="" type="password"/>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg font-label-sm text-label-sm text-on-primary bg-primary hover:bg-primary-container hover:text-on-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-[0_4px_20px_rgba(74,124,89,0.1)]" type="submit">
                    Reset Password
                    <span class="material-symbols-outlined text-sm">check</span>
                </button>
            </div>
        </form>

        <div class="mt-stack-md text-center">
            <a href="{{ route('login') }}" class="text-secondary font-semibold hover:text-primary-container hover:underline transition-colors">Kembali ke login</a>
        </div>
    </div>
</body>
</html>
