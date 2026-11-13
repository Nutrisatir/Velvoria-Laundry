<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Katalog Layanan & Harga</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola daftar layanan kiloan dan satuan, tarif, minimum kuantitas, dan estimasi waktu kerja.</p>
        </div>
        <a href="<?= base_url('admin/services/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Layanan Baru</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Nama Layanan</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Tarif</th>
                        <th class="py-3.5 px-6">Min. Qty</th>
                        <th class="py-3.5 px-6">Estimasi</th>
                        <th class="py-3.5 px-6">Express</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($services as $srv): ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum"><?= esc($srv['code']) ?></td>
                            <td class="py-3.5 px-6 font-bold text-gray-900"><?= esc($srv['name']) ?></td>
                            <td class="py-3.5 px-6 text-gray-600"><?= esc($srv['category_name'] ?? '-') ?></td>
                            <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($srv['price']) ?> / <?= esc($srv['unit']) ?></td>
                            <td class="py-3.5 px-6 text-gray-600"><?= $srv['minimum_quantity'] ?> <?= esc($srv['unit']) ?></td>
                            <td class="py-3.5 px-6 text-gray-600"><?= esc($srv['estimated_hours']) ?> Jam</td>
                            <td class="py-3.5 px-6">
                                <?= $srv['is_express'] ? '<span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Express</span>' : '<span class="text-gray-400">Reguler</span>' ?>
                            </td>
                            <td class="py-3.5 px-6">
                                <form action="<?= base_url('admin/services/' . $srv['id'] . '/toggle') ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold cursor-pointer <?= $srv['is_active'] ? 'bg-emerald-100 text-emerald-800 hover:bg-rose-100 hover:text-rose-800' : 'bg-gray-100 text-gray-600 hover:bg-emerald-100 hover:text-emerald-800' ?>">
                                        <?= $srv['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="<?= base_url('admin/services/' . $srv['id'] . '/edit') ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
