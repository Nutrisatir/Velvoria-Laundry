<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kode Promo & Diskon</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola kupon diskon nominal atau persentase, batas penggunaan, dan masa berlaku.</p>
        </div>
        <a href="<?= base_url('admin/promotions/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buat Promo Baru</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Kode Promo</th>
                        <th class="py-3.5 px-6">Nama Promo</th>
                        <th class="py-3.5 px-6">Nilai Diskon</th>
                        <th class="py-3.5 px-6">Min. Order</th>
                        <th class="py-3.5 px-6">Maks. Potongan</th>
                        <th class="py-3.5 px-6">Periode Berlaku</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($promotions)): ?>
                        <tr><td colspan="8" class="py-12 text-center text-gray-400">Belum ada promo yang dibuat</td></tr>
                    <?php else: ?>
                        <?php foreach ($promotions as $promo): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum text-sm"><?= esc($promo['code']) ?></td>
                                <td class="py-3.5 px-6 font-semibold text-gray-900"><?= esc($promo['name']) ?></td>
                                <td class="py-3.5 px-6 font-bold text-emerald-600">
                                    <?= $promo['discount_type'] === 'percent' ? $promo['discount_value'] . '%' : format_rupiah($promo['discount_value']) ?>
                                </td>
                                <td class="py-3.5 px-6 text-gray-600"><?= format_rupiah($promo['minimum_order']) ?></td>
                                <td class="py-3.5 px-6 text-gray-600"><?= $promo['maximum_discount'] ? format_rupiah($promo['maximum_discount']) : 'Tanpa Batas' ?></td>
                                <td class="py-3.5 px-6 text-gray-500 text-[11px]">
                                    <?= date('d M Y', strtotime($promo['starts_at'])) ?> - <?= date('d M Y', strtotime($promo['ends_at'])) ?>
                                </td>
                                <td class="py-3.5 px-6">
                                    <form action="<?= base_url('admin/promotions/' . $promo['id'] . '/toggle') ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $promo['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                            <?= $promo['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="<?= base_url('admin/promotions/' . $promo['id'] . '/edit') ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                        Ubah
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
