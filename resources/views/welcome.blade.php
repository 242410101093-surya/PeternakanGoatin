<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Goatin - Peternakan Kambing Modern & Efisien</title>
    
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
    
    <!-- Tailwind CSS -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #EDF4F8;
            color: #051F20;
            overflow-x: hidden;
        }

        /* ── Glassmorphic Effect ── */
        .glass-nav {
            background: #ffffff;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(5, 31, 32, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .glass-card-dark {
            background: rgba(5, 31, 32, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* ── Scroll Progress Bar ── */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #2A7844 0%, #235347 100%);
            width: 0%;
            z-index: 9999;
            transition: width 0.1s ease-out;
        }

        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #EDF4F8;
        }
        ::-webkit-scrollbar-thumb {
            background: #235347;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #163832;
        }

        /* ── Micro-interactions ── */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px -10px rgba(5, 31, 32, 0.15);
        }

        .hover-glow {
            transition: all 0.3s ease;
        }
        .hover-glow:hover {
            box-shadow: 0 0 20px 2px rgba(42, 120, 68, 0.2);
            border-color: rgba(42, 120, 68, 0.3);
        }

        /* Floating 3D animations */
        @keyframes floatY {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: floatY 4s ease-in-out infinite;
        }
        
        @keyframes floatYDelayed {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        .animate-float-delayed {
            animation: floatYDelayed 5s ease-in-out infinite;
            animation-delay: 1s;
        }

        /* ── Scroll Animations (Intersection Observer) ── */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-left-to-right {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animate-left-to-right.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-right-to-left {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .animate-right-to-left.animated {
            opacity: 1;
            transform: translateX(0);
        }

        /* Delay Utilities */
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        .delay-500 { transition-delay: 500ms; }

        /* Custom active navbar link */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #2A7844;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased select-none overflow-x-hidden">

    <!-- ═══ Global Page-Navigation Loading Spinner ═══ -->
    <div id="global-page-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(255,255,255,0.4); backdrop-filter:blur(3px);
                align-items:center; justify-content:center;">
        @include('partials.modern_loader')
    </div>

    @include('partials.landing.header')


    <!-- HERO SECTION -->
    <section id="home" class="relative min-h-screen pt-24 flex items-center justify-center overflow-hidden bg-cover bg-center" style="background-image: url('{{ asset('images/background_goats.png') }}');">
        <!-- Overlay Dark-Green Vignette -->
        <div class="absolute inset-0 bg-gradient-to-tr from-primary-dark/95 via-primary-dark/85 to-[#0e3b2e]/60 z-0"></div>
        
        <!-- Glowing Ambient Lights -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-goatin-green/20 rounded-full blur-[100px] pointer-events-none z-0"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[450px] h-[450px] bg-accent-teal/15 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div class="relative z-10 max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Grid: Copywriting & CTAs -->
            <div class="lg:col-span-7 space-y-6 md:space-y-8 text-left animate-left-to-right">
                
                <!-- Tagline Pill -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 shadow-lg text-[10px] md:text-[11px] font-extrabold tracking-widest text-[#8EB69B] uppercase">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#3ae07b] animate-pulse"></span>
                    <span>Platform Peternakan Digital #1</span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight uppercase" style="letter-spacing: -0.025em;">
                    Kambing Unggulan<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#8EB69B] to-[#3ae07b]">
                        Kesehatan Terjamin
                    </span>
                </h1>

                <!-- Sub-headline Description -->
                <p class="text-sm sm:text-base font-medium leading-relaxed max-w-2xl text-slate-200/90 bg-black/30 backdrop-blur-sm p-4 rounded-2xl border border-white/5 shadow-md">
                    Temukan ras kambing terbaik untuk kurban, aqiqah, maupun ternak langsung dari peternakan kami. Dipantau secara digital dengan rekam medis terpadu dan pemeriksaan rutin dokter hewan.
                </p>

                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#catalog" 
                       class="flex items-center gap-2 py-4 px-8 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-goatin-green hover:bg-[#206034] shadow-md hover:shadow-lg active:scale-[0.98] group cursor-pointer">
                        <span>Jelajahi Katalog</span>
                        <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">shopping_bag</span>
                    </a>
                    <a href="https://wa.me/62895365651114" target="_blank"
                       class="flex items-center gap-2 py-4 px-8 rounded-full text-xs font-extrabold text-slate-100 uppercase tracking-widest transition-all duration-300 bg-white/10 hover:bg-white/20 border border-white/25 shadow-sm active:scale-[0.98] cursor-pointer">
                        <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.472 1.45 5.378 1.451 5.61.003 10.177-4.56 10.18-10.168.002-2.717-1.054-5.271-2.974-7.193C17.314 1.32 14.764.265 12.048.265 6.439.265 1.871 4.832 1.868 10.441c-.002 1.905.496 3.77 1.442 5.365l-1.002 3.66 3.75-.983zm13.11-6.983c-.307-.154-1.82-.9-2.102-1.003-.28-.103-.485-.154-.69.154-.202.307-.787.99-.966 1.196-.179.205-.358.23-.665.077-.307-.154-1.298-.478-2.472-1.526-.913-.815-1.53-1.82-1.71-2.126-.179-.307-.018-.473.136-.626.139-.138.307-.358.46-.537.154-.179.205-.307.307-.512.103-.205.051-.384-.025-.537-.077-.154-.69-1.66-.945-2.279-.249-.597-.502-.516-.69-.526-.179-.009-.384-.01-.589-.01-.205 0-.537.077-.82.384-.28.307-1.075 1.05-1.075 2.56 0 1.51 1.1 2.972 1.252 3.177.154.205 2.164 3.307 5.242 4.639.732.316 1.302.505 1.748.647.736.234 1.406.2 1.936.12.59-.09 1.82-.743 2.077-1.46.256-.718.256-1.332.179-1.46-.077-.128-.282-.205-.589-.359z"/>
                        </svg>
                        <span>Hubungi Kami</span>
                    </a>
                </div>

                <!-- Minor Badges -->
                <div class="flex items-center gap-6 pt-4 border-t border-white/10 max-w-lg">
                    <div class="flex items-center gap-2 text-slate-300 text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px] text-goatin-green">health_and_safety</span>
                        <span>Bebas PMK & Sehat</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-300 text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px] text-goatin-green">local_shipping</span>
                        <span>Kurir Khusus Ternak</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-300 text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px] text-goatin-green">verified</span>
                        <span>Sertifikat Resmi</span>
                    </div>
                </div>
            </div>

            <!-- Right Grid: 3D Composition Cards -->
            <div class="lg:col-span-5 relative flex items-center justify-center min-h-[420px] sm:min-h-[550px] animate-right-to-left lg:-translate-x-16">
                <!-- Large Rotating Underlay Circle -->
                <div class="absolute w-[340px] sm:w-[450px] h-[340px] sm:h-[450px] rounded-full border border-white/5 bg-gradient-to-tr from-goatin-green/20 to-transparent animate-spin" style="animation-duration: 25s;"></div>

                <!-- Main Glass Card Container -->
                <div class="relative w-[300px] sm:w-[380px] h-[380px] sm:h-[480px] rounded-[32px] overflow-hidden shadow-2xl border border-white/15 bg-white/5 backdrop-blur-md p-1.5 flex flex-col justify-end animate-float">
                    <div class="absolute inset-0 bg-cover bg-center z-0 scale-105 filter brightness-95 rounded-[30px]" style="background-image: url('{{ asset('images/background_goats.png') }}');"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-dark via-black/20 to-transparent z-10"></div>
                    
                    <div class="relative z-20 p-5 space-y-2">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[#3ae07b] text-[18px]">verified_user</span>
                            <span class="text-[9px] uppercase tracking-widest text-[#8EB69B] font-extrabold">Peternakan Goatin</span>
                        </div>
                        <h4 class="text-white text-base sm:text-lg font-black tracking-tight leading-snug uppercase">Standardisasi Ternak Modern</h4>
                        <p class="text-[10px] text-slate-200/80 font-medium leading-relaxed">Pengawasan medis 24/7 dan pelaporan bobot berkala langsung di HP Anda.</p>
                    </div>
                </div>

                <!-- Floating Glass Card 1 (Top Left) -->
                <div class="absolute top-0 -left-4 sm:top-2 sm:-left-8 glass-card rounded-2xl p-4 shadow-xl flex items-center gap-3 border border-white/40 max-w-[200px] sm:max-w-[240px] animate-float-delayed z-20">
                    <div class="w-10 h-10 rounded-xl bg-goatin-green/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-goatin-green text-[20px] font-bold">clinical_notes</span>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-500 font-extrabold uppercase tracking-wide">Status Medis</p>
                        <p class="text-xs text-primary-dark font-black">100% Terverifikasi</p>
                    </div>
                </div>

                <!-- Floating Glass Card 2 (Bottom Right) -->
                <div class="absolute bottom-0 -right-4 sm:bottom-2 sm:-right-8 glass-card rounded-2xl p-4 shadow-xl flex items-center gap-3 border border-white/40 max-w-[200px] sm:max-w-[240px] animate-float z-20">
                    <div class="w-10 h-10 rounded-xl bg-[#e3851c]/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#e3851c] text-[20px] font-bold">monitoring</span>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-500 font-extrabold uppercase tracking-wide">Bobot Ternak</p>
                        <p class="text-xs text-primary-dark font-black">Pertumbuhan Stabil</p>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- STATS BAR -->
    <section class="relative z-20 -mt-10 max-w-[1280px] mx-auto px-4 sm:px-6">
        <div class="glass-card rounded-3xl shadow-xl p-8 border border-white/60 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 divide-y-0 md:divide-x divide-slate-200/80 animate-on-scroll">
            
            <div class="text-center p-2 flex flex-col justify-center">
                <span class="text-3xl sm:text-4xl font-extrabold text-goatin-green tracking-tight">1.500+</span>
                <span class="text-[10px] text-slate-500 font-extrabold uppercase tracking-widest mt-1">Kambing Terjual</span>
            </div>

            <div class="text-center p-2 flex flex-col justify-center">
                <span class="text-3xl sm:text-4xl font-extrabold text-goatin-green tracking-tight">99.8%</span>
                <span class="text-[10px] text-slate-500 font-extrabold uppercase tracking-widest mt-1">Kondisi Sehat</span>
            </div>

            <div class="text-center p-2 flex flex-col justify-center">
                <span class="text-3xl sm:text-4xl font-extrabold text-goatin-green tracking-tight">100%</span>
                <span class="text-[10px] text-slate-500 font-extrabold uppercase tracking-widest mt-1">Bebas PMK</span>
            </div>

            <div class="text-center p-2 flex flex-col justify-center">
                <span class="text-3xl sm:text-4xl font-extrabold text-goatin-green tracking-tight">24 Jam</span>
                <span class="text-[10px] text-slate-500 font-extrabold uppercase tracking-widest mt-1">Respons Admin</span>
            </div>

        </div>
    </section>


    <!-- KEY FEATURES / KEUNGGULAN -->
    <section id="features" class="py-20 lg:py-28 bg-[#EDF4F8]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3 animate-on-scroll">
                <span class="text-[10px] font-extrabold tracking-widest text-goatin-green uppercase bg-goatin-green/10 px-4 py-1.5 rounded-full">Keunggulan Platform</span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark uppercase tracking-tight">Kenapa Harus Membeli di Goatin?</h2>
                <div class="w-16 h-1 bg-goatin-green mx-auto rounded-full"></div>
                <p class="text-sm text-slate-500 font-medium">Kami memadukan teknologi manajemen peternakan digital untuk menjamin transparansi, kemudahan, dan kualitas kambing terbaik.</p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Card 1 -->
                <div class="glass-card rounded-3xl p-8 hover-lift hover-glow border border-white/60 space-y-5 flex flex-col justify-between animate-on-scroll delay-100">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-goatin-green/10 text-goatin-green flex items-center justify-center">
                            <span class="material-symbols-outlined text-[32px] font-bold">clinical_notes</span>
                        </div>
                        <h3 class="text-base font-black text-primary-dark uppercase tracking-tight">Rekam Medis Digital</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Setiap kambing memiliki log kesehatan, vaksin, serta pengobatan terperinci yang dapat diakses langsung oleh pembeli.
                        </p>
                    </div>
                    <div class="pt-2 flex items-center text-[10px] text-goatin-green font-extrabold uppercase tracking-widest gap-1">
                        <span>Transparan</span>
                        <span class="material-symbols-outlined text-[12px]">check_circle</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card rounded-3xl p-8 hover-lift hover-glow border border-white/60 space-y-5 flex flex-col justify-between animate-on-scroll delay-200">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[32px] font-bold">nutrition</span>
                        </div>
                        <h3 class="text-base font-black text-primary-dark uppercase tracking-tight">Pakan Organik Pilihan</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Diberi asupan pakan organik premium dengan nutrisi seimbang untuk pertumbuhan bobot daging optimal dan sehat alami.
                        </p>
                    </div>
                    <div class="pt-2 flex items-center text-[10px] text-amber-600 font-extrabold uppercase tracking-widest gap-1">
                        <span>Nutrisi Tinggi</span>
                        <span class="material-symbols-outlined text-[12px]">check_circle</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="glass-card rounded-3xl p-8 hover-lift hover-glow border border-white/60 space-y-5 flex flex-col justify-between animate-on-scroll delay-300">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[32px] font-bold">local_shipping</span>
                        </div>
                        <h3 class="text-base font-black text-primary-dark uppercase tracking-tight">Kurir Khusus Ternak</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Pengiriman menggunakan armada angkut hewan standar tinggi, memastikan tingkat stres kambing rendah sampai ke alamat Anda.
                        </p>
                    </div>
                    <div class="pt-2 flex items-center text-[10px] text-sky-600 font-extrabold uppercase tracking-widest gap-1">
                        <span>Aman & Nyaman</span>
                        <span class="material-symbols-outlined text-[12px]">check_circle</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="glass-card rounded-3xl p-8 hover-lift hover-glow border border-white/60 space-y-5 flex flex-col justify-between animate-on-scroll delay-400">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[32px] font-bold">payments</span>
                        </div>
                        <h3 class="text-base font-black text-primary-dark uppercase tracking-tight">Harga Langsung Peternak</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Tanpa perantara! Kami menawarkan harga terbaik langsung dari kandang peternakan kami, lengkap dengan sistem transparansi nota.
                        </p>
                    </div>
                    <div class="pt-2 flex items-center text-[10px] text-rose-600 font-extrabold uppercase tracking-widest gap-1">
                        <span>Tanpa Perantara</span>
                        <span class="material-symbols-outlined text-[12px]">check_circle</span>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- DYNAMIC CATALOG -->
    <section id="catalog" class="py-20 lg:py-28 bg-[#EDF4F8] border-t border-slate-100">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3 animate-on-scroll">
                <span class="text-[10px] font-extrabold tracking-widest text-goatin-green uppercase bg-goatin-green/10 px-4 py-1.5 rounded-full">Katalog Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark uppercase tracking-tight">Kambing Unggulan Tersedia</h2>
                <div class="w-16 h-1 bg-goatin-green mx-auto rounded-full"></div>
                <p class="text-sm text-slate-500 font-medium">Beli langsung melalui platform digital kami. Berikut beberapa ternak yang siap Anda pinang hari ini.</p>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                    @foreach($featuredProducts as $produk)
                        <!-- Dynamic Card -->
                        <div class="glass-card rounded-[32px] overflow-hidden hover-lift hover-glow border border-white/60 flex flex-col justify-between shadow-sm animate-on-scroll">
                            
                            <div>
                                <!-- Image Header -->
                                <div class="relative h-56 bg-slate-100 overflow-hidden">
                                    @if($produk->foto)
                                        @if(config('app.env') === 'production')
                                            <img src="https://yzvshrhziexfcjhamrfk.supabase.co/object/public/goatin-storage/{{ $produk->foto }}?render=image" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-kambing.png') }}';">
                                        @else
                                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" onerror="this.onerror=null; this.src='{{ asset('images/placeholder-kambing.png') }}';">
                                        @endif
                                    @else
                                        <!-- Fallback design using gradient and icon if no image uploaded -->
                                        <div class="w-full h-full bg-gradient-to-br from-accent-teal to-primary-dark flex flex-col items-center justify-center p-4">
                                            <span class="material-symbols-outlined text-white/50 text-[64px]">pets</span>
                                            <span class="text-[10px] font-black uppercase text-[#8EB69B] tracking-wider mt-2">Foto Ternak Goatin</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Stock Status Pill -->
                                    <div class="absolute top-4 left-4 z-10">
                                        <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-goatin-green text-white shadow-md">
                                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#3ae07b] animate-ping"></span>
                                            <span>Tersedia</span>
                                        </span>
                                    </div>

                                    <!-- Breed Tag (Overlaid bottom-right) -->
                                    <div class="absolute bottom-4 right-4 z-10">
                                        <span class="px-3.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-black/55 backdrop-blur-md text-white border border-white/10 shadow-sm">
                                            {{ $produk->inventaris->ras ?? 'Ras Super' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6 space-y-4">
                                    <div>
                                        <p class="text-[9px] font-extrabold text-goatin-green uppercase tracking-widest">{{ $produk->inventaris->jenis ?? 'Kambing' }}</p>
                                        <h3 class="text-lg font-black text-primary-dark tracking-tight uppercase mt-0.5">{{ $produk->nama_produk }}</h3>
                                    </div>

                                    <!-- Specs Grid -->
                                    <div class="grid grid-cols-3 gap-2 bg-goatin-light/60 p-3 rounded-2xl border border-slate-100/50">
                                        <div class="text-center">
                                            <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Gender</p>
                                            <p class="text-xs text-primary-dark font-black mt-0.5">
                                                {{ ($produk->inventaris->gender ?? '-') == 'Jantan' ? 'Jantan ♂' : 'Betina ♀' }}
                                            </p>
                                        </div>
                                        <div class="text-center border-x border-slate-200">
                                            <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Umur</p>
                                            <p class="text-xs text-primary-dark font-black mt-0.5">{{ $produk->inventaris->umur ?? '-' }} Bln</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Berat</p>
                                            <p class="text-xs text-primary-dark font-black mt-0.5">{{ $produk->inventaris->berat ?? '-' }} Kg</p>
                                        </div>
                                    </div>

                                    <!-- Health Status -->
                                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-white/50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                        <span class="material-symbols-outlined text-goatin-green text-[16px]">health_and_safety</span>
                                        <span>Medis: <strong class="text-goatin-green font-extrabold uppercase">{{ $produk->inventaris->rekam_medis_general ?? 'Sangat Sehat' }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-slate-100/50 gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wider">Harga Penjualan</span>
                                    <span class="text-base font-extrabold text-primary-dark tracking-tight">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('login') }}" 
                                   class="flex items-center gap-1.5 py-3 px-5 rounded-full text-[10px] font-extrabold text-white uppercase tracking-widest bg-goatin-green hover:bg-accent-teal shadow-md active:scale-95 transition-all duration-200 group">
                                    <span>Pesan</span>
                                    <span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover:translate-x-0.5">arrow_forward</span>
                                </a>
                            </div>

                        </div>
                    @endforeach
                @else
                    <!-- Fallback Mock Cards if database is empty -->
                    <!-- Mock Card 1 -->
                    <div class="glass-card rounded-[32px] overflow-hidden hover-lift hover-glow border border-white/60 flex flex-col justify-between shadow-sm animate-on-scroll delay-100">
                        <div>
                            <div class="relative h-56 bg-slate-100 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-accent-teal to-primary-dark flex flex-col items-center justify-center p-4">
                                    <span class="material-symbols-outlined text-[#8EB69B]/30 text-[72px]">pets</span>
                                    <span class="text-[10px] font-black uppercase text-[#8EB69B] tracking-wider mt-2">Goatin Breeding</span>
                                </div>
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-goatin-green text-white shadow-md">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#3ae07b] animate-ping"></span>
                                        <span>Tersedia</span>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4 z-10">
                                    <span class="px-3.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-black/55 backdrop-blur-md text-white border border-white/10 shadow-sm">
                                        Etawa Super
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-[9px] font-extrabold text-goatin-green uppercase tracking-widest">Kambing Etawa</p>
                                    <h3 class="text-lg font-black text-primary-dark tracking-tight uppercase mt-0.5">Etawa Premium Grade A</h3>
                                </div>
                                <div class="grid grid-cols-3 gap-2 bg-goatin-light/60 p-3 rounded-2xl border border-slate-100/50">
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Gender</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">Jantan ♂</p>
                                    </div>
                                    <div class="text-center border-x border-slate-200">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Umur</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">18 Bln</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Berat</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">65 Kg</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-white/50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                    <span class="material-symbols-outlined text-goatin-green text-[16px]">health_and_safety</span>
                                    <span>Medis: <strong class="text-goatin-green font-extrabold uppercase">100% Sehat & Vaksin</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-slate-100/50 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wider">Harga Penjualan</span>
                                <span class="text-base font-extrabold text-primary-dark tracking-tight">Rp 4.500.000</span>
                            </div>
                            <a href="{{ route('login') }}" 
                               class="flex items-center gap-1.5 py-3 px-5 rounded-full text-[10px] font-extrabold text-white uppercase tracking-widest bg-goatin-green hover:bg-accent-teal shadow-md active:scale-95 transition-all duration-200 group">
                                <span>Pesan</span>
                                <span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover:translate-x-0.5">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Mock Card 2 -->
                    <div class="glass-card rounded-[32px] overflow-hidden hover-lift hover-glow border border-white/60 flex flex-col justify-between shadow-sm animate-on-scroll delay-200">
                        <div>
                            <div class="relative h-56 bg-slate-100 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-[#206034] to-accent-teal-dark flex flex-col items-center justify-center p-4">
                                    <span class="material-symbols-outlined text-[#8EB69B]/30 text-[72px]">pets</span>
                                    <span class="text-[10px] font-black uppercase text-[#8EB69B] tracking-wider mt-2">Goatin Breeding</span>
                                </div>
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-goatin-green text-white shadow-md">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#3ae07b] animate-ping"></span>
                                        <span>Tersedia</span>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4 z-10">
                                    <span class="px-3.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-black/55 backdrop-blur-md text-white border border-white/10 shadow-sm">
                                        Garut Gagah
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-[9px] font-extrabold text-goatin-green uppercase tracking-widest">Domba Garut</p>
                                    <h3 class="text-lg font-black text-primary-dark tracking-tight uppercase mt-0.5">Domba Garut Kurban Super</h3>
                                </div>
                                <div class="grid grid-cols-3 gap-2 bg-goatin-light/60 p-3 rounded-2xl border border-slate-100/50">
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Gender</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">Jantan ♂</p>
                                    </div>
                                    <div class="text-center border-x border-slate-200">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Umur</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">20 Bln</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Berat</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">70 Kg</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-white/50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                    <span class="material-symbols-outlined text-goatin-green text-[16px]">health_and_safety</span>
                                    <span>Medis: <strong class="text-goatin-green font-extrabold uppercase">Bebas PMK (Drh. Hewan)</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-slate-100/50 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wider">Harga Penjualan</span>
                                <span class="text-base font-extrabold text-primary-dark tracking-tight">Rp 5.200.000</span>
                            </div>
                            <a href="{{ route('login') }}" 
                               class="flex items-center gap-1.5 py-3 px-5 rounded-full text-[10px] font-extrabold text-white uppercase tracking-widest bg-goatin-green hover:bg-accent-teal shadow-md active:scale-95 transition-all duration-200 group">
                                <span>Pesan</span>
                                <span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover:translate-x-0.5">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Mock Card 3 -->
                    <div class="glass-card rounded-[32px] overflow-hidden hover-lift hover-glow border border-white/60 flex flex-col justify-between shadow-sm animate-on-scroll delay-300">
                        <div>
                            <div class="relative h-56 bg-slate-100 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-[#1b2b27] to-[#122822] flex flex-col items-center justify-center p-4">
                                    <span class="material-symbols-outlined text-[#8EB69B]/30 text-[72px]">pets</span>
                                    <span class="text-[10px] font-black uppercase text-[#8EB69B] tracking-wider mt-2">Goatin Breeding</span>
                                </div>
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-goatin-green text-white shadow-md">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#3ae07b] animate-ping"></span>
                                        <span>Tersedia</span>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4 z-10">
                                    <span class="px-3.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-black/55 backdrop-blur-md text-white border border-white/10 shadow-sm">
                                        Gibas Super
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-[9px] font-extrabold text-goatin-green uppercase tracking-widest">Kambing Gibas</p>
                                    <h3 class="text-lg font-black text-primary-dark tracking-tight uppercase mt-0.5">Gibas Super Aqiqah</h3>
                                </div>
                                <div class="grid grid-cols-3 gap-2 bg-goatin-light/60 p-3 rounded-2xl border border-slate-100/50">
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Gender</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">Jantan ♂</p>
                                    </div>
                                    <div class="text-center border-x border-slate-200">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Umur</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">15 Bln</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wide">Berat</p>
                                        <p class="text-xs text-primary-dark font-black mt-0.5">58 Kg</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-white/50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                    <span class="material-symbols-outlined text-goatin-green text-[16px]">health_and_safety</span>
                                    <span>Medis: <strong class="text-goatin-green font-extrabold uppercase">Sertifikat Medis Lengkap</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-slate-100/50 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wider">Harga Penjualan</span>
                                <span class="text-base font-extrabold text-primary-dark tracking-tight">Rp 3.800.000</span>
                            </div>
                            <a href="{{ route('login') }}" 
                               class="flex items-center gap-1.5 py-3 px-5 rounded-full text-[10px] font-extrabold text-white uppercase tracking-widest bg-goatin-green hover:bg-accent-teal shadow-md active:scale-95 transition-all duration-200 group">
                                <span>Pesan</span>
                                <span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover:translate-x-0.5">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
            
            <!-- Bottom CTA to full page -->
            <div class="text-center mt-14 animate-on-scroll">
                @auth
                    <a href="{{ route('customer.produk') }}" 
                       class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-goatin-green hover:text-accent-teal hover:underline transition-all group">
                        <span>Lihat Seluruh Katalog Kami</span>
                        <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-goatin-green hover:text-accent-teal hover:underline transition-all group">
                        <span>Masuk untuk melihat seluruh katalog kami</span>
                        <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                @endauth
            </div>

        </div>
    </section>


    <!-- TESTIMONIALS -->
    <section id="testimonials" class="py-20 lg:py-28 bg-[#EDF4F8] border-t border-slate-100">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3 animate-on-scroll">
                <span class="text-[10px] font-extrabold tracking-widest text-goatin-green uppercase bg-goatin-green/10 px-4 py-1.5 rounded-full">Testimoni Pelanggan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-primary-dark uppercase tracking-tight">Kepuasan Pelanggan Adalah Prioritas</h2>
                <div class="w-16 h-1 bg-goatin-green mx-auto rounded-full"></div>
                <p class="text-sm text-slate-500 font-medium">Inilah pendapat jujur dari mereka yang telah memesan kambing di Peternakan Goatin.</p>
            </div>

            <!-- Testimonials Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Testimonial 1 -->
                <div class="glass-card rounded-[32px] p-8 border border-white/60 space-y-5 flex flex-col justify-between shadow-sm animate-on-scroll delay-100">
                    <div class="space-y-4">
                        <!-- Rating Stars -->
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium italic leading-relaxed">
                            "Membeli kambing Kurban di Goatin sangat mudah dan transparan. Kita dikasih akses buat rekam medis kambingnya secara berkala, jadi ketahuan perkembangan bobot dan kesehatannya. Top banget!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/50">
                        <div class="w-10 h-10 rounded-full bg-goatin-green text-white flex items-center justify-center font-black text-sm uppercase">
                            H
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase">Haji Rian Fauzi</h4>
                            <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Pekurban Mandiri</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="glass-card rounded-[32px] p-8 border border-white/60 space-y-5 flex flex-col justify-between shadow-sm animate-on-scroll delay-200">
                    <div class="space-y-4">
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium italic leading-relaxed">
                            "Aqiqah anak jadi gak ribet. Pesan dari rumah lewat web Goatin, konfirmasi via WhatsApp, langsung diantar ke alamat dengan kondisi kambing sehat bebas PMK. Sangat terpercaya."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/50">
                        <div class="w-10 h-10 rounded-full bg-accent-teal text-white flex items-center justify-center font-black text-sm uppercase">
                            S
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase">Siti Aisyah</h4>
                            <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Ibu Rumah Tangga</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="glass-card rounded-[32px] p-8 border border-white/60 space-y-5 flex flex-col justify-between shadow-sm animate-on-scroll delay-300">
                    <div class="space-y-4">
                        <div class="flex text-amber-500 gap-0.5">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 0;">star</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium italic leading-relaxed">
                            "Saya bekerjasama dengan Goatin untuk menyuplai warung sate kami. Kualitas daging kambing Etawa-nya juara, timbangan bobotnya akurat, dan pelayanan pengirimannya cepat dan tertib."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/50">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-black text-sm uppercase">
                            B
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase">Bambang Hartono</h4>
                            <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Owner Warung Sate Makmur</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- CTA BANNER -->
    <section class="py-16 bg-[#EDF4F8] relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6">
            
            <div class="glass-card-dark rounded-[48px] shadow-2xl relative overflow-hidden p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8 border border-white/10 animate-on-scroll" style="background-image: url('{{ asset('images/background_goats.png') }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-gradient-to-r from-primary-dark/95 via-primary-dark/85 to-[#0b2b21]/80 z-0"></div>
                
                <div class="relative z-10 max-w-xl space-y-4 text-left">
                    <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight uppercase leading-tight">Mulai Pengalaman Transaksi Ternak Digital</h3>
                    <p class="text-xs sm:text-sm text-slate-300 font-medium leading-relaxed">Daftar sekarang secara gratis, nikmati kemudahan memantau kesehatan ternak, serta riwayat pembayaran yang rapi dan aman.</p>
                </div>

                <div class="relative z-10 shrink-0 flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto flex justify-center items-center gap-2 py-4 px-8 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-goatin-green hover:bg-[#206034] shadow-md hover:shadow-lg active:scale-95 cursor-pointer">
                        <span>Daftar Sekarang</span>
                        <span class="material-symbols-outlined text-sm">person_add</span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto flex justify-center items-center gap-2 py-4 px-8 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-white/10 hover:bg-white/20 border border-white/25 shadow-sm active:scale-95 cursor-pointer">
                        <span>Sign In</span>
                        <span class="material-symbols-outlined text-sm">login</span>
                    </a>
                </div>

            </div>

        </div>
    </section>


    <!-- CONTACT SECTION -->
    <section id="contact" class="py-20 lg:py-28 bg-[#EDF4F8] border-t border-slate-100">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Info (5 Cols) -->
            <div class="lg:col-span-5 space-y-8 animate-left-to-right">
                <div class="space-y-3">
                    <span class="text-[10px] font-extrabold tracking-widest text-goatin-green uppercase bg-goatin-green/10 px-4 py-1.5 rounded-full">Hubungi Kontak</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-primary-dark uppercase tracking-tight">Butuh Konsultasi Ternak?</h2>
                    <div class="w-16 h-1 bg-goatin-green rounded-full"></div>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">Tim ahli peternakan kami siap membantu Anda memilih ras kambing terbaik sesuai keperluan kurban, aqiqah, maupun ternak.</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-goatin-green/10 text-goatin-green flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">location_on</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase tracking-wide">ALAMAT PETERNAKAN</h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Sidoarjo, Jawa Timur, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-goatin-green/10 text-goatin-green flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">call</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase tracking-wide">WhatsApp Support</h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">0895365651114 (Fast Response)</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-goatin-green/10 text-goatin-green flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">mail</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-primary-dark uppercase tracking-wide">GMAIL PETERNAKAN</h4>
                            <p class="text-xs text-slate-500 mt-1 font-medium">goatinnnn@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form (7 Cols) -->
            <div class="lg:col-span-7 animate-right-to-left">
                <div class="glass-card rounded-[32px] p-8 md:p-10 border border-white/60 shadow-lg">
                    <h3 class="text-base font-black text-primary-dark uppercase tracking-tight mb-6">Kirim Pesan Cepat</h3>
                    
                    <form action="#" class="space-y-5" onsubmit="sendWhatsAppMessage(event, this);">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-extrabold text-[#8C9EA8] uppercase tracking-widest pl-1">Nama Lengkap</label>
                                <input type="text" required class="bg-goatin-light border-none rounded-full px-5 py-3.5 text-xs w-full text-slate-800 focus:ring-2 focus:ring-goatin-green focus:outline-none" placeholder="Masukkan nama...">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-extrabold text-[#8C9EA8] uppercase tracking-widest pl-1">Nomor WhatsApp</label>
                                <input type="tel" required class="bg-goatin-light border-none rounded-full px-5 py-3.5 text-xs w-full text-slate-800 focus:ring-2 focus:ring-goatin-green focus:outline-none" placeholder="Contoh: 0812...">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-extrabold text-[#8C9EA8] uppercase tracking-widest pl-1">Pertanyaan / Pesan Anda</label>
                            <textarea required rows="4" class="bg-goatin-light border-none rounded-[20px] px-5 py-3.5 text-xs w-full text-slate-800 focus:ring-2 focus:ring-goatin-green focus:outline-none" placeholder="Tuliskan detail pertanyaan atau pesanan Anda di sini..."></textarea>
                        </div>

                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest transition-all duration-300 bg-goatin-green hover:bg-[#206034] shadow-md group cursor-pointer">
                            <span>Kirim ke WhatsApp Admin</span>
                            <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-1">send</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </section>


    @include('partials.landing.footer')


    <script>
        (function() {
            // Loader handling for seamless navigation transitions
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

            window.addEventListener('pageshow', function() {
                hideLoader();
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuIcon = document.getElementById('mobileMenuIcon');

            mobileMenuBtn.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenuIcon.textContent = 'close';
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuIcon.textContent = 'menu';
                }
            });

            // Close mobile menu on clicking any navigation link
            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    mobileMenuIcon.textContent = 'menu';
                });
            });



            // Scroll Animation Observer (Intersection Observer)
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, {
                threshold: 0.05,
                rootMargin: '0px 0px -50px 0px' // Trigger slightly before entering fully
            });

            // Register animate classes to observer
            document.querySelectorAll('.animate-on-scroll, .animate-left-to-right, .animate-right-to-left').forEach(element => {
                animationObserver.observe(element);
            });

            // Contact modal success show/close functions
            window.showContactModal = function() {
                const modal = document.getElementById('contactSuccessModal');
                const content = document.getElementById('contactSuccessModalContent');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                // Trigger transition
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            };

            window.closeContactModal = function() {
                const modal = document.getElementById('contactSuccessModal');
                const content = document.getElementById('contactSuccessModalContent');
                
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
            };

            window.sendWhatsAppMessage = function(event, form) {
                event.preventDefault();
                const nameInput = form.querySelector('input[type="text"]');
                const phoneInput = form.querySelector('input[type="tel"]');
                const messageInput = form.querySelector('textarea');
                
                const name = nameInput ? nameInput.value.trim() : '';
                const phone = phoneInput ? phoneInput.value.trim() : '';
                const message = messageInput ? messageInput.value.trim() : '';
                
                const text = `Halo Admin Goatin,\n\nSaya ingin berkonsultasi mengenai ternak.\n\n*Nama:* ${name}\n*No. WhatsApp:* ${phone}\n*Pesan:* ${message}`;
                const encodedText = encodeURIComponent(text);
                const waUrl = `https://wa.me/62895365651114?text=${encodedText}`;
                
                // Direct to WhatsApp chat immediately in the same window
                window.location.href = waUrl;
            };
        })();
    </script>

    <!-- Custom Modern Centered Notification Modal -->
    <div id="contactSuccessModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" style="background: rgba(5, 31, 32, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
        <div class="relative w-full max-w-md overflow-hidden rounded-[2rem] border border-white/20 bg-white shadow-2xl p-8 flex flex-col items-center text-center transform scale-95 opacity-0 transition-all duration-300" id="contactSuccessModalContent">
            <!-- Success Icon with Glowing Ambient -->
            <div class="w-20 h-20 mb-6 rounded-full bg-goatin-green/10 flex items-center justify-center border border-goatin-green/20 relative group">
                <span class="material-symbols-outlined text-4xl text-goatin-green">check_circle</span>
            </div>
            
            <!-- Text -->
            <h3 class="text-xl font-black text-primary-dark tracking-tight mb-2 uppercase">PESAN TERKIRIM</h3>
            <p class="text-xs font-semibold text-slate-500 leading-relaxed max-w-[280px]">
                Terima kasih! Pesan Anda berhasil dikirim ke Admin Goatin.
            </p>

            <!-- Close Button -->
            <button type="button" onclick="closeContactModal()" class="mt-8 w-full py-3.5 px-6 rounded-full text-xs font-extrabold text-white uppercase tracking-widest bg-goatin-green hover:bg-[#206034] shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                Selesai
            </button>
        </div>
    </div>
</body>
</html>
