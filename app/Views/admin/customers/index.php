<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Pelanggan</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar pelanggan terdaftar, riwayat jumlah pesanan, dan kontrol status akun.</p>
        </div>

        <form action="<?= base_url('admin/customers') ?>" method="GET" class="relative max-w-xs w-full">
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama, email, hp..."
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
                        <th class="py-3.5 px-6">Pelanggan</th>
                        <th class="py-3.5 px-6">Kontak</th>
                        <th class="py-3.5 px-6">Bergabung Sejak</th>
                        <th class="py-3.5 px-6">Total Order Lunas</th>
                        <th class="py-3.5 px-6">Total Transaksi</th>
                        <th class="py-3.5 px-6">Status Akun</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($customers)): ?>
                        <tr><td colspan="7" class="py-12 text-center text-gray-400">Tidak ada pelanggan ditemukan</td></tr>
                    <?php else: ?>
                        <?php foreach ($customers as $c): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3.5 px-6">
                                    <span class="font-bold text-gray-900 block"><?= esc($c['name']) ?></span>
                                    <span class="text-[11px] text-gray-400 block"><?= esc($c['email']) ?></span>
                                </td>
                                <td class="py-3.5 px-6 text-gray-600"><?= esc($c['phone'] ?? '-') ?></td>
                                <td class="py-3.5 px-6 text-gray-500"><?= format_date($c['created_at']) ?></td>
                                <td class="py-3.5 px-6 font-bold text-gray-900"><?= $c['total_orders'] ?> pesanan</td>
                                <td class="py-3.5 px-6 font-bold text-velvoria-plum"><?= format_rupiah($c['total_spend']) ?></td>
                                <td class="py-3.5 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $c['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                        <?= esc($c['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="<?= base_url('admin/customers/' . $c['id']) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                        Riwayat
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
