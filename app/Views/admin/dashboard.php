<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <!-- Today Orders -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Pesanan Hari Ini</span>
            <span class="text-2xl font-bold text-gray-900 mt-1 block"><?= $todayOrders ?></span>
        </div>

        <!-- Pending Pickup -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Menunggu Dijemput</span>
            <span class="text-2xl font-bold text-blue-600 mt-1 block"><?= $pendingPickup ?></span>
        </div>

        <!-- In Process -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Sedang Dicuci/Proses</span>
            <span class="text-2xl font-bold text-indigo-600 mt-1 block"><?= $inProcess ?></span>
        </div>

        <!-- Ready -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Siap Diambil/Diantar</span>
            <span class="text-2xl font-bold text-emerald-600 mt-1 block"><?= $readyOrders ?></span>
        </div>

        <!-- Today Revenue -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Pendapatan Hari Ini</span>
            <span class="text-lg font-bold text-velvoria-plum mt-1 block"><?= format_rupiah($todayRevenue) ?></span>
        </div>

        <!-- Unpaid -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <span class="text-xs text-gray-500 font-medium block">Belum Bayar</span>
            <span class="text-2xl font-bold text-amber-600 mt-1 block"><?= $unpaidCount ?></span>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 7-day revenue line chart -->
        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="trending-up" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Tren Pendapatan 7 Hari Terakhir (Lunas)</span>
                </h3>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Status distribution doughnut -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Distribusi Status Cucian</span>
                </h3>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                <i data-lucide="shopping-bag" class="w-4 h-4 text-velvoria-plum"></i>
                <span>10 Pesanan Terakhir</span>
            </h3>
            <a href="<?= base_url('admin/orders') ?>" class="text-xs font-semibold text-velvoria-plum hover:underline">
                Kelola Semua Pesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-6">No. Pesanan</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Waktu Masuk</th>
                        <th class="py-3 px-6">Penyerahan</th>
                        <th class="py-3 px-6">Total Tagihan</th>
                        <th class="py-3 px-6">Status Cucian</th>
                        <th class="py-3 px-6">Pembayaran</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada data pesanan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $ro): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum">
                                    <a href="<?= base_url('admin/orders/' . $ro['id']) ?>" class="hover:underline">
                                        #<?= esc($ro['order_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">
                                    <?= esc($ro['customer_name']) ?>
                                    <span class="block text-[11px] text-gray-400"><?= esc($ro['customer_phone']) ?></span>
                                </td>
                                <td class="py-3.5 px-6 text-gray-500"><?= format_datetime($ro['created_at']) ?></td>
                                <td class="py-3.5 px-6 text-gray-600">
                                    <?= $ro['handover_method'] === 'drop_off' ? 'Outlet' : 'Kurir' ?>
                                </td>
                                <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($ro['grand_total']) ?></td>
                                <td class="py-3.5 px-6"><?= order_status_badge($ro['order_status']) ?></td>
                                <td class="py-3.5 px-6"><?= payment_status_badge($ro['payment_status']) ?></td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="<?= base_url('admin/orders/' . $ro['id']) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: <?= $chartLabels ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= $chartRevenue ?>,
                    borderColor: '#51415F',
                    backgroundColor: 'rgba(81, 65, 95, 0.08)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#51415F',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                return 'Rp' + Number(val).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Bayar', 'Menjemput', 'Proses Cuci', 'Siap', 'Selesai', 'Batal'],
                datasets: [{
                    data: <?= $statusCounts ?>,
                    backgroundColor: [
                        '#F59E0B',
                        '#3B82F6',
                        '#8B5CF6',
                        '#10B981',
                        '#059669',
                        '#EF4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
