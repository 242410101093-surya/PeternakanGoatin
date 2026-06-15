<?php
$file = 'resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

// 1. Remove confirmOrderModal HTML
$startHtml = "{{-- ── MODAL: Konfirmasi & Edit Pesanan ── --}}";
$endHtml = "</div>\n</div>\n"; // End of confirmOrderModal
$pos1 = strpos($content, $startHtml);
if ($pos1 !== false) {
    $pos2 = strpos($content, '<script>', $pos1);
    if ($pos2 !== false) {
        $content = substr($content, 0, $pos1) . substr($content, $pos2);
    }
}

// 2. Remove old JS functions
$content = preg_replace('/let wasNotifModalOpen = false;.*?function focusNotificationInModal/s', 'function focusNotificationInModal', $content);
$content = preg_replace('/function closeConfirmOrderModal.*?document\.addEventListener\(\'DOMContentLoaded\', function\(\) \{/s', "document.addEventListener('DOMContentLoaded', function() {", $content);

// 3. Remove sync harga_jual listener
$content = preg_replace('/\/\/ Sync harga_jual dengan teks di dalam textarea.*?\/\/ AJAX-ify Tandai Semua Dibaca/s', '// AJAX-ify Tandai Semua Dibaca', $content);

// 4. Replace confirmOrderForm submit listener with confirmOrderDirect
$confirmDirectJs = <<<JS
        window.confirmOrderDirect = function(notifId, user, idTernak, jenis, ras) {
            const input = document.querySelector(`.notif-harga-input[data-notif-id="\${notifId}"]`);
            let harga = 0;
            if (input) {
                harga = input.value;
            }
            
            const formattedHarga = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(harga);
            
            const confirmMsg = `Konfirmasi Pesanan?\\n\\nPelanggan: \${user}\\nHarga Jual: \${formattedHarga}\\nID Ternak: \${idTernak}\\nJenis/Ras: \${jenis} / \${ras}`;
            
            if (!confirm(confirmMsg)) return;
            
            const loader = document.getElementById('global-page-loader');
            if(loader) loader.style.display = 'flex';
            
            const formData = new FormData();
            formData.append('harga_jual', harga);
            // Include default title and message to bypass validation if needed by backend
            formData.append('title', 'Pesanan Dikonfirmasi');
            formData.append('message', `Pesanan untuk \${jenis} \${ras} telah dikonfirmasi dengan harga \${formattedHarga}.`);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}');
            
            fetch(`/admin/notifications/\${notifId}/confirm`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(loader) loader.style.display = 'none';
                if (data.success) {
                    if(window.showToast) window.showToast(data.message, 'success');
                    
                    const recentItem = document.getElementById(`dashboard-recent-notif-item-\${notifId}`);
                    if (recentItem) recentItem.remove();
                    
                    const modalItem = document.getElementById(`modal-notif-item-\${notifId}`);
                    if (modalItem) modalItem.remove();
                    
                    const navbarItem = document.getElementById(`navbar-notif-item-\${notifId}`);
                    if (navbarItem) navbarItem.remove();
                    
                    const profitCount = document.getElementById('dashboard-laba-bersih-count');
                    if (profitCount && data.labaBersih !== undefined) profitCount.textContent = `Rp \${data.labaBersih}`;
                    
                    if (window.updateGlobalPendingCounts && data.pendingOrders !== undefined) {
                        window.updateGlobalPendingCounts(data.pendingOrders);
                    }
                    if (typeof checkEmptyNotificationStates === 'function') {
                        checkEmptyNotificationStates(data.pendingOrders);
                    }
                } else {
                    if(window.showToast) window.showToast(data.message || 'Gagal mengonfirmasi', 'error');
                }
            })
            .catch(err => {
                if(loader) loader.style.display = 'none';
                if(window.showToast) window.showToast('Terjadi kesalahan jaringan.', 'error');
            });
        }
JS;

$content = preg_replace('/const confirmOrderForm = document\.getElementById\(\'confirmOrderForm\'\);.*?window\.rejectOrder = function/s', $confirmDirectJs . "\n\n        window.rejectOrder = function", $content);

// 5. Update rejectOrder to not depend on form
$content = preg_replace('/let notifId = directId;.*?const loader = document/s', "let notifId = directId;\n            if (!notifId) return;\n            \n            const loader = document", $content);

file_put_contents($file, $content);
echo "Done.";
