{{-- ── MODAL: Notifikasi Pesanan (Redesigned) ── --}}
<div id="notificationsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(5,31,32,.55);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[88vh] flex flex-col shadow-2xl overflow-hidden" style="border:1px solid rgba(42,120,68,0.1);">

        {{-- Header --}}
        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100" style="background:linear-gradient(135deg,#051F20 0%,#163832 100%);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background:rgba(255,255,255,0.12);">
                    <span class="material-symbols-outlined text-white" style="font-size:22px;">notifications_active</span>
                </div>
                <div>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Notifikasi Pesanan</h3>
                    <p class="text-[10px] font-semibold mt-0.5" style="color:rgba(255,255,255,0.55);">Permintaan pembelian dari pelanggan</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(count($unreadNotifications) > 0)
                <span class="text-[10px] font-black px-2.5 py-1 rounded-full text-white" style="background:rgba(220,38,38,0.85);">
                    {{ count($unreadNotifications) }} Baru
                </span>
                @endif
                <button onclick="closeNotificationsModal()" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all" style="background:rgba(255,255,255,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <span class="material-symbols-outlined text-white" style="font-size:18px;">close</span>
                </button>
            </div>
        </div>

        {{-- Notification Cards List --}}
        <div class="overflow-y-auto flex-1 p-5 space-y-4" id="modal-notifications-list">
            @forelse($unreadNotifications as $notif)
            @php
                $msg = $notif->message ?? '';
                // Parse structured data from the **Key:** Value markdown format
                preg_match('/Pelanggan \*\*(.+?)\*\* \(WhatsApp: \*\*(.+?)\*\*\)/', $msg, $customerMatch);
                preg_match('/ingin membeli produk \*\*(.+?)\*\*/', $msg, $produkMatch);
                preg_match('/\*\*ID Ternak:\*\* (.+?)(?:\n|$)/', $msg, $idMatch);
                preg_match('/\*\*Jenis:\*\* (.+?)(?:\n|$)/', $msg, $jenisMatch);
                preg_match('/\*\*Ras:\*\* (.+?)(?:\n|$)/', $msg, $rasMatch);
                preg_match('/\*\*Gender:\*\* (.+?)(?:\n|$)/', $msg, $genderMatch);
                preg_match('/\*\*Umur:\*\* (.+?)(?:\n|$)/', $msg, $umurMatch);
                preg_match('/\*\*Berat:\*\* (.+?)(?:\n|$)/', $msg, $beratMatch);
                preg_match('/\*\*Harga:\*\* (.+?)(?:\n|$)/', $msg, $hargaMatch);
                preg_match('/\*\*Alamat:\*\* (.+?)(?:\n|$)/s', $msg, $alamatMatch);
                preg_match('/\[Buka Peta\]\((.+?)\)/', $msg, $mapMatch);

                $namaCustomer   = trim($customerMatch[1] ?? '-');
                $waCustomer     = trim($customerMatch[2] ?? '-');
                $namaProduk     = trim($produkMatch[1] ?? '-');
                $idTernak       = trim($idMatch[1] ?? '-');
                $jenisTernak    = trim($jenisMatch[1] ?? '-');
                $rasTernak      = trim($rasMatch[1] ?? '-');
                $genderTernak   = trim($genderMatch[1] ?? '-');
                $umurTernak     = trim($umurMatch[1] ?? '-');
                $beratTernak    = trim($beratMatch[1] ?? '-');
                $hargaTernak    = trim($hargaMatch[1] ?? '-');
                // Extract raw number from harga for input field
                $hargaNumeric   = preg_replace('/[^0-9]/', '', $hargaTernak);
                $alamatRaw      = trim($alamatMatch[1] ?? '-');
                $alamat         = preg_replace('/\n.*/', '', $alamatRaw);
                $mapUrl         = trim($mapMatch[1] ?? '');
                $initial        = strtoupper(substr($namaCustomer, 0, 1));
                $waDigits       = preg_replace('/[^0-9]/', '', $waCustomer);
                $waLink         = $waDigits ? 'https://wa.me/' . $waDigits : '#';
                $genderIcon     = strtolower($genderTernak) === 'betina' ? 'female' : 'male';
                $genderColor    = strtolower($genderTernak) === 'betina' ? '#EC4899' : '#3B82F6';
            @endphp
            <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm transition-all hover:shadow-md" id="modal-notif-item-{{ $notif->id }}" style="background:#FAFCFF;">

                {{-- Card Top: Customer Info + Timestamp --}}
                <div class="flex items-start gap-4 px-5 pt-5 pb-3">
                    {{-- Avatar --}}
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-white text-sm shrink-0 shadow-md" style="background:linear-gradient(135deg,#2A7844,#163832);">
                        {{ $initial }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="text-sm font-black text-primary-dark">{{ $namaCustomer }}</h4>
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full text-white" style="background:#DC2626;">Baru</span>
                        </div>
                        {{-- WhatsApp Chip --}}
                        <a href="{{ $waLink }}" target="_blank"
                           class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all hover:opacity-80"
                           style="background:#DCFCE7;color:#16a34a;">
                            <span class="material-symbols-outlined" style="font-size:12px;">call</span>
                            {{ $waCustomer }}
                        </a>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[10px] font-semibold" style="color:#94A3B8;">{{ $notif->created_at->diffForHumans() }}</p>
                        <p class="text-[9px] mt-0.5" style="color:#CBD5E1;">{{ $notif->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Product Name Banner --}}
                <div class="mx-5 mb-3 px-4 py-2.5 rounded-xl flex items-center gap-2" style="background:linear-gradient(90deg,rgba(42,120,68,0.08),rgba(42,120,68,0.03));border:1px solid rgba(42,120,68,0.12);">
                    <span class="material-symbols-outlined" style="font-size:16px;color:#2A7844;">pets</span>
                    <p class="text-xs font-black" style="color:#051F20;">{{ $namaProduk }}</p>
                </div>

                {{-- Detail Grid (6 fields: 5 info + 1 editable price) --}}
                <div class="mx-5 mb-3 grid grid-cols-3 gap-2">
                    @php
                    $fields = [
                        ['label'=>'ID Ternak',  'value'=>'#'.$idTernak,   'icon'=>'tag'],
                        ['label'=>'Jenis',      'value'=>$jenisTernak,    'icon'=>'category'],
                        ['label'=>'Ras',        'value'=>$rasTernak,      'icon'=>'genetics'],
                        ['label'=>'Umur',       'value'=>$umurTernak,     'icon'=>'calendar_month'],
                        ['label'=>'Berat',      'value'=>$beratTernak,    'icon'=>'monitor_weight'],
                    ];
                    @endphp
                    @foreach($fields as $field)
                    <div class="px-3 py-2.5 rounded-xl" style="background:#F1F5F9;border:1px solid #E2E8F0;">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="material-symbols-outlined" style="font-size:11px;color:#94A3B8;">{{ $field['icon'] }}</span>
                            <p class="text-[9px] font-bold uppercase tracking-wider" style="color:#94A3B8;">{{ $field['label'] }}</p>
                        </div>
                        <p class="text-[11px] font-black leading-tight" style="color:#051F20;">{{ $field['value'] }}</p>
                    </div>
                    @endforeach

                    {{-- Harga Card (editable, same style as other cards) --}}
                    <div class="px-3 py-2.5 rounded-xl" style="background:#F1F5F9;border:1px solid #E2E8F0;">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="material-symbols-outlined" style="font-size:11px;color:#94A3B8;">payments</span>
                            <p class="text-[9px] font-bold uppercase tracking-wider" style="color:#94A3B8;">Harga Jual</p>
                            <span class="material-symbols-outlined ml-auto" style="font-size:10px;color:#94A3B8;">edit</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-[10px] font-black" style="color:#051F20;">Rp</span>
                            <input type="number" value="{{ $hargaNumeric }}" min="0"
                                   class="notif-harga-input w-full text-[11px] font-black bg-transparent outline-none border-none p-0"
                                   style="color:#051F20; min-width:0;"
                                   data-notif-id="{{ $notif->id }}" />
                        </div>
                    </div>
                </div>

                {{-- Gender Chip --}}
                <div class="mx-5 mb-3 flex items-center gap-2 px-3 py-2 rounded-xl" style="background:#F8FAFC;border:1px solid #E2E8F0;">
                    <span class="material-symbols-outlined" style="font-size:14px;color:{{ $genderColor }};">{{ $genderIcon }}</span>
                    <p class="text-[10px] font-bold" style="color:#64748B;">Gender: <span class="font-black" style="color:{{ $genderColor }};">{{ $genderTernak }}</span></p>
                </div>

                {{-- Alamat Block --}}
                @if($alamat && $alamat !== '-')
                <div class="mx-5 mb-3 px-3 py-2.5 rounded-xl flex items-start gap-2" style="background:#EFF6FF;border:1px solid #BFDBFE;">
                    <span class="material-symbols-outlined mt-0.5 shrink-0" style="font-size:14px;color:#3B82F6;">location_on</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black uppercase tracking-wider mb-0.5" style="color:#93C5FD;">Alamat Pengiriman</p>
                        <p class="text-[10px] font-semibold leading-relaxed" style="color:#1E40AF;">{{ $alamat }}</p>
                        @if($mapUrl)
                        <a href="{{ $mapUrl }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-[9px] font-black uppercase tracking-wider transition-opacity hover:opacity-75" style="color:#2563EB;">
                            <span class="material-symbols-outlined" style="font-size:10px;">open_in_new</span>
                            Buka di Google Maps
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Card Action Footer --}}
                <div class="px-5 py-3.5 flex items-center gap-3 border-t border-slate-100" style="background:#F8FAFC;">
                    <a href="{{ $waLink }}" target="_blank"
                       class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all hover:opacity-80 shadow-sm"
                       style="background:#DCFCE7;color:#16a34a;border:1px solid #BBF7D0;">
                        <span class="material-symbols-outlined" style="font-size:13px;">chat</span>
                        Chat WA
                    </a>
                    <div class="flex-1"></div>
                    <button onclick="rejectOrder({{ $notif->id }})"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-200 active:scale-[0.98]"
                            style="background:#FEF2F2; border:1px solid #FEE2E2;">
                        <span class="material-symbols-outlined" style="font-size:14px;">cancel</span>
                        Tolak
                    </button>
                    <button onclick="confirmOrderDirect({{ $notif->id }}, '{{ addslashes($namaCustomer) }}', '{{ addslashes($idTernak) }}', '{{ addslashes($jenisTernak) }}', '{{ addslashes($rasTernak) }}')"
                            class="flex items-center gap-2 px-5 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider text-white shadow-md transition-all hover:shadow-lg active:scale-[0.98]"
                            style="background:linear-gradient(135deg,#2A7844,#163832);">
                        <span class="material-symbols-outlined" style="font-size:15px;">check_circle</span>
                        Konfirmasi
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-16 empty-placeholder">
                <div class="w-20 h-20 rounded-full mx-auto flex items-center justify-center mb-4" style="background:#F1F5F9;">
                    <span class="material-symbols-outlined text-5xl" style="color:#CBD5E1;">notifications_none</span>
                </div>
                <p class="text-sm font-black" style="color:#94A3B8;">Tidak Ada Notifikasi Baru</p>
                <p class="text-xs mt-1" style="color:#CBD5E1;">Semua pesanan sudah ditangani</p>
            </div>
            @endforelse
        </div>

        {{-- Modal Footer --}}
        <div class="flex justify-between items-center px-7 py-4 border-t border-slate-100" style="background:#F8FAFC;" id="modal-notifications-footer">
            <div id="modal-read-all-container" class="{{ count($unreadNotifications) > 0 ? '' : 'hidden' }}">
                <form action="{{ route('admin.notifications.read-all') }}" method="POST" id="readAllNotificationsForm">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider transition-colors hover:opacity-75" style="color:#DC2626;">
                        <span class="material-symbols-outlined" style="font-size:14px;">done_all</span>
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
            <button onclick="closeNotificationsModal()"
                    class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl transition-all bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 shadow-sm">
                <span class="material-symbols-outlined" style="font-size:14px;">close</span>
                Tutup
            </button>
        </div>
    </div>
</div>
