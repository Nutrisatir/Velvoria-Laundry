<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold font-mono text-velvoria-ink">#<?= esc($order['order_number']) ?></h1>
                <?= order_status_badge($order['order_status']) ?>
                <?= payment_status_badge($order['payment_status']) ?>
            </div>
            <p class="text-xs text-velvoria-muted mt-1">Dibuat pada <?= format_datetime($order['created_at']) ?></p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="<?= base_url('customer/orders/' . $order['order_number'] . '/receipt') ?>" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-velvoria-border text-xs font-semibold text-velvoria-ink hover:bg-velvoria-cream flex items-center space-x-1.5 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 text-velvoria-plum"></i>
                <span>Cetak Nota</span>
            </a>

            <?php if (in_array($order['order_status'], ['awaiting_payment', 'pending_pickup', 'received'])): ?>
                <button type="button" @click="$dispatch('open-cancel-modal')" class="px-4 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition-all">
                    Batalkan
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Notice Banner (If Unpaid) -->
    <?php if ($order['payment_status'] === 'unpaid' && !in_array($order['order_status'], ['cancelled', 'rejected'])): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-950">Menunggu Pembayaran</h3>
                    <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                        Total tagihan: <strong><?= format_rupiah($order['grand_total']) ?></strong>. Klik tombol bayar di bawah untuk memilih metode pembayaran melalui Midtrans Snap.
                    </p>
                </div>
            </div>

            <button type="button" id="pay-button" class="px-6 py-3 rounded-xl bg-velvoria-plum text-white font-bold text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-md transition-all flex items-center justify-center space-x-2 shrink-0">
                <i data-lucide="lock" class="w-4 h-4 text-velvoria-lilac"></i>
                <span>Bayar Sekarang</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Two Columns: Left Items & Payment, Right Timeline & Address -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Order Details & Breakdown -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Items Box -->
            <div class="bg-white rounded-3xl p-6 border border-velvoria-border shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-velvoria-ink pb-3 border-b border-velvoria-border flex items-center space-x-2">
                    <i data-lucide="package" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Rincian Layanan Cucian</span>
                </h3>

                <div class="divide-y divide-velvoria-border text-xs">
                    <?php foreach ($items as $item): ?>
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-velvoria-ink block"><?= esc($item['service_name_snapshot']) ?></span>
                                <span class="text-velvoria-muted text-[11px]">
                                    <?= $item['quantity'] ?> <?= esc($item['unit_snapshot']) ?> &times; <?= format_rupiah($item['unit_price']) ?>
                                </span>
                            </div>
                            <span class="font-bold text-velvoria-ink"><?= format_rupiah($item['line_total']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Weight Info -->
                <div class="p-3.5 rounded-xl bg-velvoria-cream text-xs space-y-1">
                    <div class="flex justify-between text-velvoria-muted">
                        <span>Estimasi Berat:</span>
                        <span class="font-medium text-velvoria-ink"><?= $order['estimated_weight'] ? $order['estimated_weight'] . ' kg' : '-' ?></span>
                    </div>
                    <div class="flex justify-between text-velvoria-muted">
                        <span>Berat Riil (Hasil Timbang Outlet):</span>
                        <span class="font-bold text-velvoria-plum"><?= $order['actual_weight'] ? $order['actual_weight'] . ' kg' : 'Menunggu penimbangan' ?></span>
                    </div>
                </div>

                <!-- Total Calculations -->
                <div class="pt-3 border-t border-velvoria-border space-y-2 text-xs">
                    <div class="flex justify-between text-velvoria-muted">
                        <span>Subtotal Layanan:</span>
                        <span class="font-medium text-velvoria-ink"><?= format_rupiah($order['subtotal']) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="flex justify-between text-emerald-600">
                            <span>Diskon Voucher (<?= esc($order['promo_code'] ?? '') ?>):</span>
                            <span>-<?= format_rupiah($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-velvoria-muted">
                        <span>Ongkos Antar-Jemput:</span>
                        <span class="font-medium text-velvoria-ink"><?= $order['delivery_fee'] == 0 ? 'GRATIS' : format_rupiah($order['delivery_fee']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-velvoria-ink pt-3 border-t border-velvoria-border">
                        <span>Total Tagihan:</span>
                        <span class="text-lg text-velvoria-plum"><?= format_rupiah($order['grand_total']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if (! empty($order['customer_note'])): ?>
                <div class="bg-white rounded-2xl p-5 border border-velvoria-border shadow-sm text-xs space-y-1">
                    <span class="font-bold text-velvoria-ink block">Catatan Pelanggan:</span>
                    <p class="text-velvoria-muted leading-relaxed"><?= esc($order['customer_note']) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Status Timeline & Shipping Info -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Timeline Tracking -->
            <div class="bg-white rounded-3xl p-6 border border-velvoria-border shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-velvoria-ink pb-3 border-b border-velvoria-border flex items-center space-x-2">
                    <i data-lucide="activity" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Pelacakan Status Cucian</span>
                </h3>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-velvoria-border">
                    <?php foreach ($histories as $idx => $hist): ?>
                        <div class="relative group">
                            <!-- Bullet -->
                            <div class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full <?= $idx === 0 ? 'bg-velvoria-plum ring-4 ring-velvoria-plum/20' : 'bg-gray-300' ?>"></div>
                            <div class="text-xs">
                                <span class="font-bold text-velvoria-ink block"><?= order_status_label($hist['new_status']) ?></span>
                                <span class="text-[10px] text-velvoria-muted block mt-0.5"><?= format_datetime($hist['created_at']) ?></span>
                                <?php if (! empty($hist['note'])): ?>
                                    <p class="text-[11px] text-velvoria-muted mt-1 bg-velvoria-cream/50 p-2 rounded-lg"><?= esc($hist['note']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Handover / Address Info -->
            <div class="bg-white rounded-3xl p-6 border border-velvoria-border shadow-sm space-y-3 text-xs">
                <h3 class="text-sm font-bold text-velvoria-ink pb-2 border-b border-velvoria-border flex items-center space-x-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Informasi Penyerahan</span>
                </h3>

                <div>
                    <span class="text-velvoria-muted block">Metode:</span>
                    <span class="font-bold text-velvoria-ink block">
                        <?= $order['handover_method'] === 'drop_off' ? 'Antar Sendiri ke Outlet' : 'Antar - Jemput oleh Kurir' ?>
                    </span>
                </div>

                <?php if ($address): ?>
                    <div class="pt-2 border-t border-velvoria-border">
                        <span class="text-velvoria-muted block">Alamat Antar-Jemput:</span>
                        <span class="font-bold text-velvoria-ink block"><?= esc($address['recipient_name']) ?> (<?= esc($address['phone']) ?>)</span>
                        <p class="text-velvoria-muted mt-0.5 leading-relaxed"><?= esc($address['address']) ?>, <?= esc($address['district']) ?>, <?= esc($address['city']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($order['pickup_schedule']): ?>
                    <div class="pt-2 border-t border-velvoria-border">
                        <span class="text-velvoria-muted block">Jadwal Penjemputan:</span>
                        <span class="font-medium text-velvoria-ink"><?= format_datetime($order['pickup_schedule']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div x-data="{ open: false }" @open-cancel-modal.window="open = true" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" style="display: none;">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full space-y-4" @click.away="open = false">
        <h3 class="text-lg font-bold text-velvoria-ink">Batalkan Pesanan Ini?</h3>
        <p class="text-xs text-velvoria-muted leading-relaxed">
            Pesanan hanya dapat dibatalkan jika belum masuk proses pencucian. Silakan berikan alasan pembatalan Anda.
        </p>
        <form action="<?= base_url('customer/orders/' . $order['order_number'] . '/cancel') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <textarea name="cancel_reason" rows="3" required placeholder="Alasan pembatalan..." class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs"></textarea>
            <div class="flex justify-end space-x-3">
                <button type="button" @click="open = false" class="px-4 py-2 text-xs font-semibold text-velvoria-muted hover:text-velvoria-ink">Tutup</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl">Konfirmasi Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Smooth loading layer shown only while the server creates the Snap token.
     The actual payment popup is rendered and animated by Midtrans Snap. -->
<style>
    .vl-pay-overlay{position:fixed;inset:0;z-index:9998;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(25,18,29,.48);backdrop-filter:blur(5px);opacity:0;visibility:hidden;transition:opacity .28s ease,visibility .28s ease}
    .vl-pay-overlay.is-open{opacity:1;visibility:visible}
    .vl-pay-card{width:min(100%,360px);padding:2rem 1.5rem;border:1px solid rgba(255,255,255,.65);border-radius:1.5rem;background:#fff;text-align:center;box-shadow:0 24px 80px rgba(20,10,30,.22);transform:translateY(14px) scale(.97);transition:transform .32s cubic-bezier(.2,.8,.2,1)}
    .vl-pay-overlay.is-open .vl-pay-card{transform:translateY(0) scale(1)}
    .vl-pay-spinner{width:42px;height:42px;margin:0 auto 1rem;border:3px solid #eadfeb;border-top-color:#70405f;border-radius:50%;animation:vl-spin .8s linear infinite}
    @keyframes vl-spin{to{transform:rotate(360deg)}}
    @media(prefers-reduced-motion:reduce){.vl-pay-overlay,.vl-pay-card{transition:none}.vl-pay-spinner{animation-duration:1.8s}}
</style>
<div id="vl-payment-loading" class="vl-pay-overlay" aria-hidden="true" role="status" aria-live="polite">
    <div class="vl-pay-card">
        <div class="vl-pay-spinner"></div>
        <h3 class="text-base font-bold text-velvoria-ink">Menyiapkan pembayaran</h3>
        <p class="mt-2 text-xs leading-relaxed text-velvoria-muted">Mohon tunggu sebentar. Kami sedang menghubungkan pesanan Anda ke pembayaran aman Midtrans.</p>
    </div>
</div>

<?php if (! empty($snapClientKey)): ?>
<script src="<?= esc($snapJsUrl) ?>" data-client-key="<?= esc($snapClientKey) ?>"></script>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payBtn = document.getElementById('pay-button');
    if (!payBtn) return;

    const overlay = document.getElementById('vl-payment-loading');
    const originalButton = payBtn.innerHTML;
    let paymentFlowActive = false;

    const showLoading = () => {
        if (!overlay) return;
        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
    };
    const hideLoading = () => {
        if (!overlay) return;
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
    };
    const notify = (message, type = 'error') => {
        if (typeof window.velvoriaNotify === 'function') {
            window.velvoriaNotify(message, type);
            return;
        }
        const notice = document.createElement('div');
        notice.className = 'fixed right-5 top-5 z-[9999] max-w-sm rounded-2xl border border-rose-200 bg-white p-4 text-sm text-slate-800 shadow-xl';
        notice.textContent = message;
        document.body.appendChild(notice);
        window.setTimeout(() => notice.remove(), 5000);
    };
    const resetButton = () => {
        paymentFlowActive = false;
        payBtn.disabled = false;
        payBtn.classList.remove('opacity-70', 'cursor-wait');
        payBtn.innerHTML = originalButton;
        if (window.lucide) window.lucide.createIcons();
    };

    payBtn.addEventListener('click', async () => {
        if (paymentFlowActive || payBtn.disabled) return;

        if (!window.snap || typeof window.snap.pay !== 'function') {
            notify('Snap Sandbox belum termuat. Periksa Client Key, koneksi internet, dan pemblokir script browser.', 'warning');
            return;
        }

        paymentFlowActive = true;
        payBtn.disabled = true;
        payBtn.classList.add('opacity-70', 'cursor-wait');
        payBtn.innerHTML = '<span class="inline-flex items-center gap-2"><span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>Menyiapkan pembayaran...</span>';
        showLoading();

        try {
            const body = new URLSearchParams();
            body.append(<?= json_encode(csrf_token()) ?>, <?= json_encode(csrf_hash()) ?>);

            const response = await fetch(<?= json_encode(base_url('customer/orders/' . $order['order_number'] . '/pay')) ?>, {
                method: 'POST',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    <?= json_encode(csrf_header()) ?>: <?= json_encode(csrf_hash()) ?>
                },
                body: body.toString()
            });

            const raw = await response.text();
            let data;
            try {
                data = JSON.parse(raw);
            } catch (_) {
                throw new Error(response.status === 403
                    ? 'Permintaan pembayaran ditolak (403). Muat ulang halaman untuk memperbarui token keamanan, lalu coba lagi.'
                    : 'Respons server tidak dikenali (HTTP ' + response.status + '). Periksa log CodeIgniter.');
            }

            if (!response.ok || !data.success || !data.snap_token) {
                throw new Error(data.error || ('Gagal membuat token pembayaran (HTTP ' + response.status + ').'));
            }

            hideLoading();
            // Midtrans controls the payment popup UI and its internal animation.
            window.snap.pay(data.snap_token, {
                onSuccess: () => {
                    window.location.href = <?= json_encode(base_url('payment/finish?order_id=' . $order['order_number'])) ?>;
                },
                onPending: () => {
                    window.location.href = <?= json_encode(base_url('payment/unfinish?order_id=' . $order['order_number'])) ?>;
                },
                onError: () => {
                    window.location.href = <?= json_encode(base_url('payment/error?order_id=' . $order['order_number'])) ?>;
                },
                onClose: () => resetButton()
            });
        } catch (error) {
            hideLoading();
            notify(error.message || 'Tidak dapat menghubungi layanan pembayaran.', 'error');
            resetButton();
        }
    });
});
</script>

<?= $this->endSection() ?>
