<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Greeting & Quick Action Banner -->
    <div class="bg-gradient-to-r from-velvoria-plum to-velvoria-ink text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-velvoria-lilac text-xs font-semibold tracking-wide uppercase">
                Portal Pelanggan
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                Halo, <?= esc(session()->get('user_name')) ?>.
            </h1>
            <p class="text-xs sm:text-sm text-velvoria-cream/80 max-w-lg leading-relaxed">
                Cucianmu biar kami yang urus. Pantau perkembangan pakaian bersihmu atau buat pesanan baru dalam beberapa klik.
            </p>
        </div>
        <a href="<?= base_url('customer/orders/create') ?>" class="px-6 py-3.5 rounded-xl bg-white text-velvoria-plum font-bold text-xs sm:text-sm hover:bg-velvoria-cream shadow-md transition-all flex items-center space-x-2 shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Pesan Laundry Sekarang</span>
        </a>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Active Orders -->
        <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i data-lucide="refresh-cw" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs text-velvoria-muted font-medium block">Pesanan Sedang Diproses</span>
                <span class="text-2xl font-bold text-velvoria-ink"><?= $activeOrders ?></span>
            </div>
        </div>

        <!-- Awaiting Payment -->
        <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="credit-card" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs text-velvoria-muted font-medium block">Menunggu Pembayaran</span>
                <span class="text-2xl font-bold text-velvoria-ink"><?= $awaitingPayment ?></span>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs text-velvoria-muted font-medium block">Pesanan Selesai</span>
                <span class="text-2xl font-bold text-velvoria-ink"><?= $completedOrders ?></span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-3xl border border-velvoria-border shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-velvoria-border flex items-center justify-between">
            <h3 class="text-base font-bold text-velvoria-ink flex items-center space-x-2">
                <i data-lucide="clock" class="w-4 h-4 text-velvoria-plum"></i>
                <span>Pesanan Terbaru</span>
            </h3>
            <a href="<?= base_url('customer/orders') ?>" class="text-xs font-semibold text-velvoria-plum hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-velvoria-cream flex items-center justify-center mx-auto text-velvoria-muted">
                    <i data-lucide="package-open" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-velvoria-ink">Belum Ada Pesanan</h4>
                <p class="text-xs text-velvoria-muted max-w-sm mx-auto">
                    Mulai pengalaman cucian bersih dan harum pertama Anda bersama Velvoria Laundry.
                </p>
                <div class="pt-2">
                    <a href="<?= base_url('customer/orders/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white text-xs font-semibold inline-block">
                        Buat Pesanan Pertama
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="divide-y divide-velvoria-border">
                <?php foreach ($recentOrders as $order): ?>
                    <div class="p-5 sm:p-6 hover:bg-velvoria-cream/30 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-3">
                                <a href="<?= base_url('customer/orders/' . $order['order_number']) ?>" class="font-mono text-sm font-bold text-velvoria-plum hover:underline">
                                    #<?= esc($order['order_number']) ?>
                                </a>
                                <?= order_status_badge($order['order_status']) ?>
                                <?= payment_status_badge($order['payment_status']) ?>
                            </div>
                            <div class="text-xs text-velvoria-muted flex items-center space-x-3 pt-1">
                                <span><?= format_datetime($order['created_at']) ?></span>
                                <span>&bull;</span>
                                <span><?= $order['handover_method'] === 'drop_off' ? 'Antar ke Outlet' : 'Antar-Jemput Kurir' ?></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end sm:space-x-4">
                            <span class="text-sm font-bold text-velvoria-ink"><?= format_rupiah($order['grand_total']) ?></span>
                            <a href="<?= base_url('customer/orders/' . $order['order_number']) ?>" class="px-4 py-2 rounded-xl bg-white border border-velvoria-border text-xs font-semibold text-velvoria-ink hover:bg-velvoria-cream transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
