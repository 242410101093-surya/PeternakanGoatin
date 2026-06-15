@forelse($unreadNotifications->take(4) as $notif)
@php
    $msg = $notif->message ?? '';
    preg_match('/Pelanggan \*\*(.+?)\*\* \(WhatsApp: \*\*(.+?)\*\*\)/', $msg, $customerMatch);
    preg_match('/ingin membeli produk \*\*(.+?)\*\*/', $msg, $produkMatch);

    $namaCustomer   = trim($customerMatch[1] ?? '');
    $namaProduk     = trim($produkMatch[1] ?? '');
@endphp
<div onclick="focusNotificationInModal({{ $notif->id }})"
     class="group flex items-start gap-4 p-4 rounded-2xl border border-slate-100/80 transition-all hover:bg-emerald-50/20 hover:border-emerald-500/35 hover:-translate-y-0.5 cursor-pointer shadow-sm"
     style="background:#FAFCFF;" 
     id="dashboard-recent-notif-item-{{ $notif->id }}">
    
    {{-- Decorative Icon / Avatar --}}
    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-all group-hover:scale-105" 
         style="background:linear-gradient(135deg, #DCFCE7 0%, #BBF7D0 100%); border:1px solid rgba(42, 120, 68, 0.15);">
        <span class="material-symbols-outlined text-emerald-700" style="font-size:20px;">shopping_bag</span>
    </div>
    
    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between gap-2">
            <h4 class="text-xs font-black text-primary-dark truncate uppercase tracking-wide group-hover:text-emerald-800 transition-colors">
                {{ $namaCustomer ?: $notif->title }}
            </h4>
            <span class="text-[9px] font-bold text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
        </div>
        
        <p class="text-[10px] mt-1 font-semibold text-slate-500 leading-normal">
            @if($namaProduk)
                Membeli <span class="text-primary-dark font-black text-[11px]">{{ $namaProduk }}</span>
            @else
                {{ \Illuminate\Support\Str::limit(strip_tags($msg), 60) }}
            @endif
        </p>
    </div>
</div>
@empty
<div class="text-center py-8 empty-placeholder">
    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3 border border-slate-100">
        <span class="material-symbols-outlined text-slate-400" style="font-size:20px;">done_all</span>
    </div>
    <p class="text-xs font-bold" style="color:#94A3B8;">Semua notifikasi sudah dibaca</p>
</div>
@endforelse
