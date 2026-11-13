<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6 velvoria-reveal">
        <div>
            <p class="text-xs uppercase tracking-widest text-velvoria-muted">Aktivitas akun</p>
            <h1 class="text-2xl font-bold text-velvoria-ink">Riwayat Pesanan</h1>
            <p class="text-sm text-velvoria-muted mt-1">Seluruh transaksi laundry yang pernah kamu buat.</p>
        </div>
        <a href="<?= base_url('customer/orders/create') ?>" class="px-5 py-3 rounded-xl bg-velvoria-plum text-white text-sm font-semibold hover:bg-velvoria-ink hover:-translate-y-0.5 hover:shadow-lg">
            Buat Pesanan
        </a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-velvoria-border bg-white shadow-sm velvoria-reveal">
        <table class="w-full text-sm">
            <thead class="bg-velvoria-cream text-left">
                <tr>
                    <th class="p-4">Kode</th><th class="p-4">Tanggal</th><th class="p-4">Total</th>
                    <th class="p-4">Status Pesanan</th><th class="p-4">Pembayaran</th><th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="6" class="p-8 text-center text-velvoria-muted">Belum ada riwayat pesanan.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-t border-velvoria-border hover:bg-velvoria-cream/50">
                            <td class="p-4 font-semibold whitespace-nowrap"><?= esc($order['order_number']) ?></td>
                            <td class="p-4 whitespace-nowrap"><?= esc(date('d M Y H:i', strtotime($order['created_at']))) ?></td>
                            <td class="p-4 whitespace-nowrap">Rp<?= number_format((int) $order['grand_total'], 0, ',', '.') ?></td>
                            <td class="p-4"><?= esc(ucwords(str_replace('_', ' ', $order['order_status']))) ?></td>
                            <td class="p-4"><?= esc(ucwords(str_replace('_', ' ', $order['payment_status']))) ?></td>
                            <td class="p-4"><a class="text-velvoria-plum font-semibold hover:text-velvoria-ink hover:underline" href="<?= base_url('customer/orders/' . $order['order_number']) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    // CI4 pager outputs a lone "1" for a one-page result set. Do not render it.
    $historyPageCount = isset($pager) && method_exists($pager, 'getPageCount')
        ? (int) $pager->getPageCount()
        : 1;
    ?>
    <?php if ($historyPageCount > 1): ?>
        <nav class="mt-6 flex justify-center" aria-label="Navigasi riwayat pesanan">
            <?= $pager->links('default', 'default_full') ?>
        </nav>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
