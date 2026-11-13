<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-16 md:py-24 max-w-lg mx-auto px-4 text-center space-y-6">
    <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
        <i data-lucide="check-circle" class="w-10 h-10"></i>
    </div>

    <div class="space-y-2">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-velvoria-ink">Pembayaran Berhasil!</h1>
        <p class="text-xs sm:text-sm text-velvoria-muted leading-relaxed">
            Terima kasih! Pembayaran Anda telah kami terima dan diverifikasi oleh sistem Midtrans.
        </p>
        <?php if (!empty($orderId)): ?>
            <p class="text-xs font-mono font-bold text-velvoria-plum pt-2">
                Nomor Referensi: #<?= esc($orderId) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="<?= base_url('customer/orders') ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm transition-all">
            Lihat Pesanan Saya
        </a>
        <a href="<?= base_url('customer/dashboard') ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white border border-velvoria-border text-velvoria-ink font-medium text-xs sm:text-sm hover:bg-velvoria-cream transition-all">
            Ke Dashboard
        </a>
    </div>
</div>

<?= $this->endSection() ?>
