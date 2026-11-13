<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-velvoria-ink">Pesanan Saya</h1>
            <p class="text-xs text-velvoria-muted mt-1">Daftar seluruh riwayat transaksi dan status pengerjaan cucian Anda.</p>
        </div>
        <a href="<?= base_url('customer/orders/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Pesanan Baru</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-velvoria-border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center space-x-1 sm:space-x-2 overflow-x-auto pb-1 text-xs font-medium">
            <a href="<?= base_url('customer/orders?tab=all') ?>" class="px-3.5 py-2 rounded-xl transition-colors <?= $tab === 'all' ? 'bg-velvoria-plum text-white font-bold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                Semua
            </a>
            <a href="<?= base_url('customer/orders?tab=unpaid') ?>" class="px-3.5 py-2 rounded-xl transition-colors <?= $tab === 'unpaid' ? 'bg-velvoria-plum text-white font-bold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                Belum Bayar
            </a>
            <a href="<?= base_url('customer/orders?tab=processing') ?>" class="px-3.5 py-2 rounded-xl transition-colors <?= $tab === 'processing' ? 'bg-velvoria-plum text-white font-bold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                Diproses
            </a>
            <a href="<?= base_url('customer/orders?tab=completed') ?>" class="px-3.5 py-2 rounded-xl transition-colors <?= $tab === 'completed' ? 'bg-velvoria-plum text-white font-bold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                Selesai
            </a>
            <a href="<?= base_url('customer/orders?tab=cancelled') ?>" class="px-3.5 py-2 rounded-xl transition-colors <?= $tab === 'cancelled' ? 'bg-velvoria-plum text-white font-bold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                Dibatalkan
            </a>
        </div>

        <!-- Search Form -->
        <form action="<?= base_url('customer/orders') ?>" method="GET" class="relative max-w-xs w-full">
            <input type="hidden" name="tab" value="<?= esc($tab) ?>">
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari no. pesanan..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-velvoria-border text-xs focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum bg-velvoria-cream/30">
            <i data-lucide="search" class="w-4 h-4 text-velvoria-muted absolute left-3 top-2.5"></i>
        </form>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-3xl border border-velvoria-border shadow-sm overflow-hidden">
        <?php if (empty($orders)): ?>
            <div class="p-16 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-velvoria-cream flex items-center justify-center mx-auto text-velvoria-muted">
                    <i data-lucide="package-search" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-bold text-velvoria-ink">Tidak Ada Pesanan Ditemukan</h3>
                <p class="text-xs text-velvoria-muted max-w-sm mx-auto">
                    Belum ada riwayat transaksi pada kategori filter ini.
                </p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-velvoria-border">
                <?php foreach ($orders as $order): ?>
                    <div class="p-5 sm:p-6 hover:bg-velvoria-cream/30 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center space-x-3">
                                <a href="<?= base_url('customer/orders/' . $order['order_number']) ?>" class="font-mono text-sm font-bold text-velvoria-plum hover:underline">
                                    #<?= esc($order['order_number']) ?>
                                </a>
                                <?= order_status_badge($order['order_status']) ?>
                                <?= payment_status_badge($order['payment_status']) ?>
                            </div>
                            <div class="text-xs text-velvoria-muted flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="flex items-center space-x-1">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    <span><?= format_datetime($order['created_at']) ?></span>
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center space-x-1">
                                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                    <span><?= $order['handover_method'] === 'drop_off' ? 'Antar Sendiri ke Outlet' : 'Antar - Jemput' ?></span>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end sm:space-x-4">
                            <div class="text-right sm:text-right">
                                <span class="text-[11px] text-velvoria-muted block">Total Tagihan</span>
                                <span class="text-base font-bold text-velvoria-ink"><?= format_rupiah($order['grand_total']) ?></span>
                            </div>
                            <a href="<?= base_url('customer/orders/' . $order['order_number']) ?>" class="px-4 py-2 rounded-xl bg-velvoria-plum text-white text-xs font-semibold hover:bg-velvoria-plum/90 transition-all shadow-sm">
                                Detail Pesanan
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pager->getPageCount() > 1): ?>
                <div class="px-6 py-4 border-t border-velvoria-border bg-velvoria-cream/20">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
