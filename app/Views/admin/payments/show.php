<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('admin/payments') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-900 bg-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Rincian Pembayaran</h1>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-6 border-b border-gray-100">
            <div>
                <span class="text-xs text-gray-400 block font-semibold uppercase">Provider Order ID:</span>
                <span class="font-mono font-bold text-lg text-velvoria-plum block"><?= esc($payment['provider_order_id']) ?></span>
            </div>
            <?= payment_status_badge($payment['payment_status']) ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">No. Pesanan Laundry</span>
                <span class="font-mono font-bold text-gray-900 mt-1 block">#<?= esc($payment['order_number']) ?></span>
            </div>
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">Pelanggan</span>
                <span class="font-bold text-gray-900 mt-1 block"><?= esc($payment['customer_name']) ?></span>
                <span class="text-gray-500 block"><?= esc($payment['customer_email']) ?></span>
            </div>
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">Total Tagihan</span>
                <span class="text-lg font-bold text-gray-900 mt-1 block"><?= format_rupiah($payment['gross_amount']) ?></span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs pt-4 border-t border-gray-100">
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">Provider</span>
                <span class="font-semibold text-gray-900 mt-1 block"><?= strtoupper(esc($payment['provider'])) ?></span>
            </div>
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">Tipe Pembayaran</span>
                <span class="font-semibold text-gray-900 mt-1 block"><?= esc($payment['payment_type'] ?? '-') ?></span>
            </div>
            <div>
                <span class="text-gray-400 block font-semibold text-[10px] uppercase">Waktu Bayar</span>
                <span class="font-semibold text-gray-900 mt-1 block"><?= $payment['paid_at'] ? format_datetime($payment['paid_at']) : 'Belum Lunas' ?></span>
            </div>
        </div>

        <!-- Manual Confirmation (If unpaid) -->
        <?php if ($payment['payment_status'] !== 'paid'): ?>
            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-xs space-y-3">
                <div class="flex items-start space-x-2 text-amber-900 font-bold">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                    <span>Konfirmasi Pembayaran Manual (Tunai / Transfer Langsung)</span>
                </div>
                <p class="text-amber-800 leading-relaxed">
                    Jika pelanggan membayar langsung secara tunai di outlet atau transfer manual, tandai sebagai lunas di sini. Tindakan ini akan tercatat di log audit.
                </p>
                <form action="<?= base_url('admin/payments/' . $payment['id'] . '/mark-paid') ?>" method="POST" class="space-y-2" onsubmit="return confirm('Konfirmasi bahwa uang telah diterima secara fisik/rekening?')">
                    <?= csrf_field() ?>
                    <input type="text" name="note" required placeholder="Catatan bukti: Tunai diterima kasir / transfer manual bank..." class="w-full px-3 py-2 rounded-xl border border-amber-300 text-xs">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-sm">
                        Konfirmasi Pembayaran Lunas
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Webhook Notification Audit Log -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
            <i data-lucide="bell" class="w-4 h-4 text-velvoria-plum"></i>
            <span>Log Notifikasi Webhook Midtrans (<?= count($notifications) ?>)</span>
        </h3>

        <?php if (empty($notifications)): ?>
            <p class="text-xs text-gray-400 italic">Belum ada notifikasi webhook yang tercatat untuk transaksi ini.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($notifications as $n): ?>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 uppercase">Event: <?= esc($n['event_status']) ?></span>
                            <span class="text-gray-400 text-[10px]"><?= format_datetime($n['created_at']) ?></span>
                        </div>
                        <p class="text-gray-600 font-mono text-[11px] truncate">Hash: <?= esc($n['notification_hash']) ?></p>
                        <p class="text-emerald-700 font-medium">Hasil: <?= esc($n['processing_result'] ?? '-') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
