<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('admin/services') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-900 bg-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= esc($title) ?></h1>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div>&bull; <?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
        <form action="<?= $service ? base_url('admin/services/' . $service['id']) : base_url('admin/services') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kategori Layanan *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (old('category_id', $service['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Unik Layanan *</label>
                    <input type="text" name="code" value="<?= old('code', $service['code'] ?? '') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 uppercase font-mono focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Contoh: KG-REG">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Nama Layanan *</label>
                <input type="text" name="name" value="<?= old('name', $service['name'] ?? '') ?>" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                       placeholder="Contoh: Cuci Kering Lipat Reguler">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum" placeholder="Jelaskan proses dan keunggulan..."><?= old('description', $service['description'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Satuan Ukur *</label>
                    <select name="unit" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                        <option value="kg" <?= old('unit', $service['unit'] ?? '') === 'kg' ? 'selected' : '' ?>>Kilogram (kg)</option>
                        <option value="pcs" <?= old('unit', $service['unit'] ?? '') === 'pcs' ? 'selected' : '' ?>>Satuan (pcs)</option>
                        <option value="pasang" <?= old('unit', $service['unit'] ?? '') === 'pasang' ? 'selected' : '' ?>>Pasang</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tarif / Harga (Rp) *</label>
                    <input type="number" name="price" value="<?= old('price', $service['price'] ?? '') ?>" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="7000">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Minimum Kuantitas *</label>
                    <input type="number" step="0.5" name="minimum_quantity" value="<?= old('minimum_quantity', $service['minimum_quantity'] ?? '1') ?>" required min="0.1"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Estimasi Pengerjaan (Jam) *</label>
                    <input type="number" name="estimated_hours" value="<?= old('estimated_hours', $service['estimated_hours'] ?? '48') ?>" required min="1"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="<?= old('sort_order', $service['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div class="pt-2 flex items-center space-x-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_express" value="1" <?= !empty($service['is_express']) ? 'checked' : '' ?> class="rounded border-gray-300 text-velvoria-plum focus:ring-velvoria-plum">
                    <span class="text-gray-700 font-semibold">Tandai sebagai Layanan Express</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($service) || !empty($service['is_active'])) ? 'checked' : '' ?> class="rounded border-gray-300 text-velvoria-plum focus:ring-velvoria-plum">
                    <span class="text-gray-700 font-semibold">Aktifkan Layanan di Katalog</span>
                </label>
            </div>

            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?= base_url('admin/services') ?>" class="px-5 py-2.5 rounded-xl border border-gray-200 font-semibold text-gray-600 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-velvoria-plum text-white font-semibold hover:bg-velvoria-plum/90 shadow-sm">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
