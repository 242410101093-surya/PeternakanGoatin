<!-- FOOTER -->
<footer class="bg-primary-dark text-slate-400 py-12 md:py-16 border-t border-white/5 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-10">
        
        <!-- Left Info -->
        <div class="md:col-span-5 space-y-6">
            <a href="{{ request()->routeIs('landing') ? '#home' : route('landing').'#home' }}" class="inline-block group">
                <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-11 w-auto transition-transform duration-300 group-hover:scale-[1.02]" style="filter: brightness(0) invert(1);">
            </a>
            <p class="text-xs leading-relaxed max-w-sm text-slate-300/80">
                Platform peternakan kambing modern terintegrasi berstandar tahun 2026. Kami menghubungkan peternak mandiri dan pembeli cerdas melalui integrasi teknologi pemantauan, rekam medis ternak yang lengkap, dan kemudahan transaksi digital.
            </p>
            <!-- Social Channels -->
            <div class="flex items-center gap-3 pt-2">
                @foreach([
                    ['icon' => 'language', 'url' => '#', 'label' => 'Website'],
                    ['icon' => 'mail', 'url' => 'mailto:goatinnnn@gmail.com', 'label' => 'Email'],
                ] as $social)
                <a href="{{ $social['url'] }}" aria-label="{{ $social['label'] }}"
                   class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 border shadow-md"
                   style="background: rgba(142, 182, 155, 0.08); border-color: rgba(142, 182, 155, 0.25);"
                   onmouseover="this.style.background='#235347'; this.style.borderColor='#235347'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(35, 83, 71, 0.35)';"
                   onmouseout="this.style.background='rgba(142, 182, 155, 0.08)'; this.style.borderColor='rgba(142, 182, 155, 0.25)'; this.style.transform='none'; this.style.boxShadow='none';">
                    <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">{{ $social['icon'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Middle Quick Links -->
        <div class="md:col-span-3 space-y-4">
            <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#8EB69B]">Navigasi Halaman</h4>
            <ul class="space-y-3.5">
                <li>
                    <a href="{{ request()->routeIs('landing') ? '#home' : route('landing').'#home' }}" class="text-xs font-semibold transition-colors flex items-center gap-2 text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #8EB69B;"></span>
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ request()->routeIs('landing') ? '#features' : route('landing').'#features' }}" class="text-xs font-semibold transition-colors flex items-center gap-2 text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #8EB69B;"></span>
                        Keunggulan
                    </a>
                </li>
                <li>
                    <a href="{{ request()->routeIs('landing') ? '#catalog' : route('landing').'#catalog' }}" class="text-xs font-semibold transition-colors flex items-center gap-2 text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #8EB69B;"></span>
                        Katalog Kambing
                    </a>
                </li>
                <li>
                    <a href="{{ request()->routeIs('landing') ? '#testimonials' : route('landing').'#testimonials' }}" class="text-xs font-semibold transition-colors flex items-center gap-2 text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #8EB69B;"></span>
                        Ulasan Mitra
                    </a>
                </li>
                <li>
                    <a href="{{ request()->routeIs('landing') ? '#contact' : route('landing').'#contact' }}" class="text-xs font-semibold transition-colors flex items-center gap-2 text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #8EB69B;"></span>
                        Kontak Resmi
                    </a>
                </li>
            </ul>
        </div>

        <!-- Right Info Contact -->
        <div class="md:col-span-4 space-y-5">
            <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#8EB69B]">Layanan Informasi</h4>
            <ul class="space-y-4">
                <li class="flex items-start gap-3 text-xs font-semibold text-[#F1F5F9]">
                    <span class="material-symbols-outlined shrink-0 text-[#8EB69B]" style="font-size: 20px;">location_on</span>
                    <span class="leading-relaxed">Sidoarjo, Jawa Timur,<br><span class="text-xs font-medium text-slate-400" style="color: #94A3B8;">Indonesia</span></span>
                </li>
                <li class="flex items-center gap-3 text-xs font-semibold text-[#F1F5F9]">
                    <span class="material-symbols-outlined shrink-0 text-[#8EB69B]" style="font-size: 20px;">mail</span>
                    <a href="mailto:goatinnnn@gmail.com" class="transition-colors hover:underline text-[#F1F5F9]" onmouseover="this.style.color='#8EB69B';" onmouseout="this.style.color='#F1F5F9';">goatinnnn@gmail.com</a>
                </li>
                <li class="flex items-center gap-3 text-xs font-semibold text-[#F1F5F9]">
                    <span class="material-symbols-outlined shrink-0 text-[#8EB69B]" style="font-size: 20px;">schedule</span>
                    <span>Senin – Sabtu, 08.00 – 17.00</span>
                </li>
            </ul>
        </div>

    </div>

    <!-- Copyright -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-400">
        <p>
            © {{ date('Y') }} <strong style="color: #8EB69B;">Goatin Peternakan</strong>. Didesain secara premium untuk pengelolaan ternak modern.
        </p>
        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-slate-200 transition-colors">Kebijakan Privasi</a>
            <a href="#" class="hover:text-slate-200 transition-colors">Syarat & Ketentuan</a>
        </div>
    </div>
</footer>
