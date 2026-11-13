<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Transaksi & Pendapatan</h1>
            <p class="text-xs text-gray-500 mt-1">Laporan rekapitulasi operasional dan keuangan berdasarkan rentang tanggal.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="<?= base_url('admin/reports/export-csv?start_date=' . $startDate . '&end_date=' . $endDate . '&status=' . $status) ?>" 
               class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-medium text-xs sm:text-sm hover:bg-emerald-700 shadow-sm flex items-center justify-center space-x-2 transition-all">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Ekspor CSV (Excel)</span>
            </a>
        </div>
    </div>

    <!-- Date Range Filter Form -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
        <form action="<?= base_url('admin/reports') ?>" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3 text-xs">
            <div>
                <label class="block text-gray-500 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="px-3 py-2 rounded-xl border border-gray-200">
            </div>
            <div>
                <label class="block text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="px-3 py-2 rounded-xl border border-gray-200">
            </div>
            <div>
                <label class="block text-gray-500 mb-1">Status Pesanan</label>
                <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 bg-white">
                    <option value="">Semua Status</option>
                    <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Selesai</option>
                    <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-velvoria-plum text-white font-semibold hover:bg-velvoria-plum/90">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Metrics Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Total Pesanan</span>
            <span class="text-2xl font-bold text-gray-900 mt-1 block"><?= $totalOrders ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Total Pendapatan (Lunas)</span>
            <span class="text-xl font-bold text-emerald-600 mt-1 block"><?= format_rupiah($totalRevenue) ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Total Diskon Diberikan</span>
            <span class="text-xl font-bold text-rose-600 mt-1 block"><?= format_rupiah($totalDiscount) ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Pesanan Selesai / Batal</span>
            <span class="text-lg font-bold text-gray-800 mt-1 block"><?= $completedCount ?> / <?= $cancelledCount ?></span>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-6">No. Pesanan</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Metode</th>
                        <th class="py-3 px-6">Subtotal</th>
                        <th class="py-3 px-6">Diskon</th>
                        <th class="py-3 px-6">Total Tagihan</th>
                        <th class="py-3 px-6">Status Cucian</th>
                        <th class="py-3 px-6">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="9" class="py-12 text-center text-gray-400">Tidak ada transaksi pada periode ini</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum">
                                    <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="hover:underline">
                                        #<?= esc($order['order_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 px-6 text-gray-500"><?= format_datetime($order['created_at']) ?></td>
                                <td class="py-3.5 px-6 font-medium text-gray-900"><?= esc($order['customer_name']) ?></td>
                                <td class="py-3.5 px-6 text-gray-600"><?= $order['handover_method'] === 'drop_off' ? 'Outlet' : 'Antar Jemput' ?></td>
                                <td class="py-3.5 px-6 text-gray-600"><?= format_rupiah($order['subtotal']) ?></td>
                                <td class="py-3.5 px-6 text-rose-600"><?= $order['discount_amount'] > 0 ? '-' . format_rupiah($order['discount_amount']) : '-' ?></td>
                                <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($order['grand_total']) ?></td>
                                <td class="py-3.5 px-6"><?= order_status_badge($order['order_status']) ?></td>
                                <td class="py-3.5 px-6"><?= payment_status_badge($order['payment_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
