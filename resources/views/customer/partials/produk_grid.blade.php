{{-- ── Products Card Grid ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($produks as $produk)
    <div class="group flex flex-col glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
        
        {{-- Header Image / Stylized Vector illustration if null --}}
        <div class="h-52 relative overflow-hidden shrink-0 flex items-center justify-center bg-gradient-to-br"
             style="background: linear-gradient(135deg, #051F20 0%, #0B2B26 100%);">
            
            @if($produk->foto)
                @if(config('app.env') === 'production')
                    <img src="{{ env('SUPABASE_URL') }}/storage/v1/object/public/{{ env('SUPABASE_BUCKET') }}/{{ $produk->foto }}?render=image" alt="{{ $produk->nama_produk }}"
                          class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                          onerror="this.onerror=null; this.src='{{ asset('images/placeholder-kambing.png') }}';">
                @else
                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                          class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                          onerror="this.onerror=null; this.src='{{ asset('images/placeholder-kambing.png') }}';">
                @endif
            @else
                {{-- Luxurious Stylized Vector representation of livestock --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                    <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full filter blur-xl opacity-20" style="background:#2A7844;"></div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-2" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);">
                        <span class="material-symbols-outlined text-white text-3xl font-light">pets</span>
                    </div>
                    <span class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Goatin Prime</span>
                </div>
            @endif

            {{-- Status Tags (Hover trigger) --}}
            @if($produk->inventaris)
            <div class="absolute top-3 left-3 z-10">
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold shadow-sm flex items-center gap-1"
                      style="background:rgba(255, 255, 255, 0.95); color:#2A7844; border:1px solid rgba(42, 120, 68, 0.15);">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $produk->inventaris->gender }}
                </span>
            </div>
            @endif
        </div>

        {{-- Body Info --}}
        <div class="p-5 flex flex-col flex-grow space-y-3">
            <h3 class="font-bold text-base leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2" style="color:#051F20;">
                {{ $produk->nama_produk }}
            </h3>
            <p class="text-xs leading-relaxed line-clamp-2" style="color:#64748B;">
                {{ $produk->spesifikasi }}
            </p>

            {{-- Bottom Metadata, Price & Action --}}
            <div class="mt-auto pt-4 flex flex-col">
                @if($produk->inventaris)
                <div class="text-[12px] font-medium mb-3 text-slate-500">
                    Berat: {{ $produk->inventaris->berat }} kg | Umur: {{ $produk->inventaris->umur }} bulan
                </div>
                @else
                <div class="text-[12px] font-medium mb-3 text-slate-400">
                    Produk Umum
                </div>
                @endif

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Harga Ternak</span>
                        <span class="text-base font-extrabold" style="color:#2A7844;">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="button"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-md shrink-0 cursor-pointer"
                            style="background:linear-gradient(135deg,#2A7844 0%,#1e5c33 100%);color:#fff;"
                            onmouseover="this.style.boxShadow='0 4px 12px rgba(42,120,68,0.3)';"
                            onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.02)';"
                            onclick="if(window.openProductModal) window.openProductModal(this);"
                            data-product-id="{{ $produk->id }}"
                            data-product-name="{{ $produk->nama_produk }}"
                            data-product-specs="{{ $produk->spesifikasi }}"
                            data-product-price="Rp {{ number_format($produk->harga, 0, ',', '.') }}"
                            data-product-gender="{{ $produk->inventaris?->jenis ?? '-' }}"
                            data-product-age="{{ $produk->inventaris?->umur ? $produk->inventaris->umur . ' Bulan' : '-' }}"
                            data-product-berat="{{ $produk->inventaris?->berat ? $produk->inventaris->berat . ' Kg' : '-' }}"
                            data-product-image="{{ $produk->foto ? (config('app.env') === 'production' ? env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $produk->foto . '?render=image' : asset('storage/' . $produk->foto)) : '' }}"
                            data-product-rekam-medis="{{ json_encode($produk->inventaris?->rekamMedis ?? []) }}">
                        <span class="material-symbols-outlined" style="font-size:18px;">shopping_basket</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
    @empty
    <div class="col-span-full py-16 px-8 text-center bg-white/30 backdrop-blur-md rounded-3xl border border-emerald-800/10 shadow-[0_8px_32px_rgba(5,31,32,0.02)] max-w-md mx-auto relative overflow-hidden group transition-all duration-300 hover:border-emerald-600/20 hover:shadow-[0_12px_40px_rgba(5,31,32,0.05)]" data-aos="zoom-in">
        <!-- Glowing background effects -->
        <div class="absolute -top-10 -left-10 w-24 h-24 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-emerald-600/5 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Floating Icon Container -->
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-emerald-600/10 border border-emerald-600/20 shadow-[0_8px_16px_rgba(42,120,68,0.04)] relative transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600/15">
            <span class="material-symbols-outlined text-3xl text-emerald-800">storefront</span>
        </div>
        
        <!-- Content -->
        <h3 class="font-bold text-base mb-1.5" style="color:#051F20;">Belum Ada Bibit Ternak</h3>
        <p class="text-xs max-w-xs mx-auto leading-relaxed" style="color:#64748B;">
            Bibit ternak berkualitas tinggi akan segera tersedia untuk dibeli. Silakan periksa kembali nanti.
        </p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="flex justify-center pt-6">
    {{ $produks->links() }}
</div>
