<!-- ===== CUSTOMER FOOTER — Goatin 2026 Premium ===== -->
<footer style="background: radial-gradient(circle at 100% 0%, rgba(42, 123, 148, 0.22) 0%, transparent 45%), radial-gradient(circle at 0% 100%, rgba(42, 123, 148, 0.16) 0%, transparent 40%), #06151F;"
        class="border-t border-teal-950/45 relative overflow-hidden pt-16 pb-12">
    
    {{-- Decorative glowing blobs --}}
    <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full filter blur-[120px] opacity-25 pointer-events-none" style="background:#2A7B94;"></div>
    <div class="absolute -left-20 top-0 w-80 h-80 rounded-full filter blur-[100px] opacity-15 pointer-events-none" style="background:#0E3247;"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12">

            <!-- Brand Column -->
            <div class="md:col-span-5 space-y-6">
                <a href="{{ route('customer.dashboard') }}" class="inline-block group">
                    <img src="{{ asset('images/logo.png') }}" alt="Goatin Logo" class="h-11 w-auto transition-transform duration-300 group-hover:scale-[1.02]" style="filter: brightness(0) invert(1);">
                </a>
                <p class="text-sm leading-relaxed" style="color: #E2E8F0;">
                    Platform peternakan kambing modern terintegrasi berstandar tahun 2026. Kami menghubungkan peternak mandiri dan pembeli cerdas melalui integrasi teknologi pemantauan, rekam medis ternak yang lengkap, dan kemudahan transaksi digital.
                </p>
                <!-- Social Channels -->
                <div class="flex items-center gap-3 pt-2">
                    @foreach([
                        ['icon' => 'language', 'url' => '#', 'label' => 'Website'],
                        ['icon' => 'mail', 'url' => 'mailto:info@goatin.id', 'label' => 'Email'],
                    ] as $social)
                    <a href="{{ $social['url'] }}" aria-label="{{ $social['label'] }}"
                       class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 border shadow-md"
                       style="background: rgba(56, 189, 248, 0.08); border-color: rgba(56, 189, 248, 0.25);"
                       onmouseover="this.style.background='#2A7B94'; this.style.borderColor='#2A7B94'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(42, 123, 148, 0.35)';"
                       onmouseout="this.style.background='rgba(56, 189, 248, 0.08)'; this.style.borderColor='rgba(56, 189, 248, 0.25)'; this.style.transform='none'; this.style.boxShadow='none';">
                        <span class="material-symbols-outlined" style="font-size: 18px; color: #ffffff;">{{ $social['icon'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Spacer -->
            <div class="hidden md:block md:col-span-1"></div>

            <!-- Links Column -->
            <div class="md:col-span-3 space-y-5">
                <h4 class="text-xs font-extrabold uppercase tracking-widest" style="color: #38BDF8;">Navigasi Menu</h4>
                <ul class="space-y-3.5">
                    @foreach([
                        ['route'=>'customer.produk',    'label'=>'Katalog Produk'],
                        ['route'=>'customer.monitoring','label'=>'Monitoring Pesanan'],
                        ['route'=>'customer.dashboard', 'label'=>'Artikel & Edukasi'],
                        ['route'=>'customer.profile',   'label'=>'Kelola Akun'],
                    ] as $link)
                    <li>
                        <a href="{{ route($link['route']) }}"
                           class="text-sm font-semibold transition-colors flex items-center gap-2"
                           style="color: #F1F5F9;"
                           onmouseover="this.style.color='#38BDF8';" onmouseout="this.style.color='#F1F5F9';">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: #38BDF8;"></span>
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact/Info Column -->
            <div class="md:col-span-3 space-y-5">
                <h4 class="text-xs font-extrabold uppercase tracking-widest" style="color: #38BDF8;">Layanan Informasi</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-sm font-semibold" style="color: #F1F5F9;">
                        <span class="material-symbols-outlined shrink-0" style="font-size: 20px; color: #38BDF8;">location_on</span>
                        <span class="leading-relaxed">Jember, Jawa Timur,<br><span class="text-xs font-medium text-slate-350" style="color: #94A3B8;">Indonesia</span></span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-semibold" style="color: #F1F5F9;">
                        <span class="material-symbols-outlined shrink-0" style="font-size: 20px; color: #38BDF8;">mail</span>
                        <a href="mailto:info@goatin.id" class="transition-colors hover:underline" style="color: #F1F5F9;" onmouseover="this.style.color='#38BDF8';" onmouseout="this.style.color='#F1F5F9';">info@goatin.id</a>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-semibold" style="color: #F1F5F9;">
                        <span class="material-symbols-outlined shrink-0" style="font-size: 20px; color: #38BDF8;">schedule</span>
                        <span>Senin – Sabtu, 08.00 – 17.00</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Divider Line -->
        <div class="h-px bg-slate-800 my-10" style="background: linear-gradient(90deg, rgba(226,232,240,0.01) 0%, rgba(56,189,248,0.2) 50%, rgba(226,232,240,0.01) 100%);"></div>

        <!-- Bottom Row -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
            <p class="text-xs font-medium" style="color: #CBD5E1;">
                © {{ date('Y') }} <strong class="text-sky-400">Goatin Peternakan</strong>. Didesain secara premium untuk pengelolaan ternak modern.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-xs font-semibold transition-colors" style="color: #CBD5E1;"
                   onmouseover="this.style.color='#38BDF8';" onmouseout="this.style.color='#CBD5E1';">
                    Kebijakan Privasi
                </a>
                <a href="#" class="text-xs font-semibold transition-colors" style="color: #CBD5E1;"
                   onmouseover="this.style.color='#38BDF8';" onmouseout="this.style.color='#CBD5E1';">
                    Syarat & Ketentuan
                </a>
            </div>
        </div>
    </div>
</footer>
