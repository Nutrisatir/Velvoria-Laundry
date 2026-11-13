<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Transaksi & Pembayaran</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar transaksi pembayaran Midtrans Snap dan pencatatan manual.</p>
        </div>

        <form action="<?= base_url('admin/payments') ?>" method="GET" class="relative max-w-xs w-full">
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari no order, id transaksi..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-xs focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum bg-white">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">ID Pembayaran</th>
                        <th class="py-3.5 px-6">No. Pesanan</th>
                        <th class="py-3.5 px-6">Pelanggan</th>
                        <th class="py-3.5 px-6">Metode</th>
                        <th class="py-3.5 px-6">Nominal</th>
                        <th class="py-3.5 px-6">Status Bayar</th>
                        <th class="py-3.5 px-6">Waktu Bayar</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="8" class="py-12 text-center text-gray-400">Tidak ada riwayat pembayaran</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3.5 px-6 font-mono text-[11px] text-gray-500"><?= esc($p['provider_order_id']) ?></td>
                                <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum">#<?= esc($p['order_number']) ?></td>
                                <td class="py-3.5 px-6 font-medium text-gray-900"><?= esc($p['customer_name']) ?></td>
                                <td class="py-3.5 px-6 text-gray-600 uppercase font-semibold text-[10px]"><?= esc($p['payment_type'] ?? 'Midtrans Snap') ?></td>
                                <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($p['gross_amount']) ?></td>
                                <td class="py-3.5 px-6"><?= payment_status_badge($p['payment_status']) ?></td>
                                <td class="py-3.5 px-6 text-gray-500"><?= $p['paid_at'] ? format_datetime($p['paid_at']) : '-' ?></td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="<?= base_url('admin/payments/' . $p['id']) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager->getPageCount() > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
