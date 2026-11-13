<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan Laundry</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar seluruh pesanan masuk, pembaruan status pengerjaan, dan pencatatan berat cucian.</p>
        </div>
    </div>

    <!-- Filters & Search Form -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
        <form action="<?= base_url('admin/orders') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-gray-500 mb-1">Cari Pesanan / Pelanggan</label>
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="No. pesanan, nama, no hp..."
                       class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-gray-500 mb-1">Status Cucian</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum bg-white">
                    <option value="">Semua Status</option>
                    <option value="awaiting_payment" <?= $status === 'awaiting_payment' ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                    <option value="pending_pickup" <?= $status === 'pending_pickup' ? 'selected' : '' ?>>Menunggu Penjemputan</option>
                    <option value="received" <?= $status === 'received' ? 'selected' : '' ?>>Cucian Diterima</option>
                    <option value="washing" <?= $status === 'washing' ? 'selected' : '' ?>>Sedang Dicuci</option>
                    <option value="drying" <?= $status === 'drying' ? 'selected' : '' ?>>Sedang Dikeringkan</option>
                    <option value="ironing" <?= $status === 'ironing' ? 'selected' : '' ?>>Sedang Disetrika</option>
                    <option value="ready" <?= $status === 'ready' ? 'selected' : '' ?>>Siap Diambil/Diantar</option>
                    <option value="delivering" <?= $status === 'delivering' ? 'selected' : '' ?>>Sedang Diantar</option>
                    <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Selesai</option>
                    <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-gray-500 mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum bg-white">
                    <option value="">Semua</option>
                    <option value="unpaid" <?= $paymentStatus === 'unpaid' ? 'selected' : '' ?>>Belum Bayar</option>
                    <option value="pending" <?= $paymentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $paymentStatus === 'paid' ? 'selected' : '' ?>>Lunas</option>
                    <option value="failed" <?= $paymentStatus === 'failed' ? 'selected' : '' ?>>Gagal</option>
                </select>
            </div>

            <!-- Date Range Start -->
            <div>
                <label class="block text-gray-500 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
            </div>

            <!-- Submit Filter -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2 rounded-xl bg-velvoria-plum text-white font-semibold hover:bg-velvoria-plum/90 transition-all">
                    Filter
                </button>
                <a href="<?= base_url('admin/orders') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-800" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-6">No. Pesanan</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Tanggal Masuk</th>
                        <th class="py-3 px-6">Metode</th>
                        <th class="py-3 px-6">Berat</th>
                        <th class="py-3 px-6">Total Tagihan</th>
                        <th class="py-3 px-6">Status Pengerjaan</th>
                        <th class="py-3 px-6">Pembayaran</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="9" class="py-12 text-center text-gray-400">Tidak ada pesanan yang sesuai filter</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum">
                                    <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="hover:underline">
                                        #<?= esc($order['order_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="font-bold text-gray-900 block"><?= esc($order['customer_name']) ?></span>
                                    <span class="text-[11px] text-gray-400 block"><?= esc($order['customer_phone']) ?></span>
                                </td>
                                <td class="py-3.5 px-6 text-gray-500"><?= format_datetime($order['created_at']) ?></td>
                                <td class="py-3.5 px-6 text-gray-600">
                                    <?= $order['handover_method'] === 'drop_off' ? 'Outlet' : 'Antar Jemput' ?>
                                </td>
                                <td class="py-3.5 px-6 text-gray-700">
                                    <?= $order['actual_weight'] ? $order['actual_weight'] . ' kg (Riil)' : ($order['estimated_weight'] ? $order['estimated_weight'] . ' kg (Est)' : '-') ?>
                                </td>
                                <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($order['grand_total']) ?></td>
                                <td class="py-3.5 px-6"><?= order_status_badge($order['order_status']) ?></td>
                                <td class="py-3.5 px-6"><?= payment_status_badge($order['payment_status']) ?></td>
                                <td class="py-3.5 px-6 text-right space-x-2">
                                    <a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="px-3 py-1.5 rounded-lg bg-velvoria-plum text-white font-semibold text-[11px] hover:bg-velvoria-plum/90 transition-all">
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
