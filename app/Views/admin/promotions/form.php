<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('admin/promotions') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-900 bg-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= esc($title) ?></h1>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
        <form action="<?= $promo ? base_url('admin/promotions/' . $promo['id']) : base_url('admin/promotions') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Kode Promo (Voucher) *</label>
                    <input type="text" name="code" value="<?= old('code', $promo['code'] ?? '') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 font-mono uppercase focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Contoh: HEMAT10">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nama Promo *</label>
                    <input type="text" name="name" value="<?= old('name', $promo['name'] ?? '') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Contoh: Diskon Pelanggan Baru 10%">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tipe Diskon *</label>
                    <select name="discount_type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                        <option value="percent" <?= old('discount_type', $promo['discount_type'] ?? '') === 'percent' ? 'selected' : '' ?>>Persentase (%)</option>
                        <option value="fixed" <?= old('discount_type', $promo['discount_type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Nominal Tetap (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nilai Diskon *</label>
                    <input type="number" name="discount_value" value="<?= old('discount_value', $promo['discount_value'] ?? '') ?>" required min="1"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Contoh: 10 untuk 10% atau 5000 untuk Rp5.000">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Minimal Order (Rp)</label>
                    <input type="number" name="minimum_order" value="<?= old('minimum_order', $promo['minimum_order'] ?? '0') ?>" min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="20000">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Maksimal Potongan (Rp, untuk %)</label>
                    <input type="number" name="maximum_discount" value="<?= old('maximum_discount', $promo['maximum_discount'] ?? '') ?>" min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="15000 (Kosongkan jika tanpa batas)">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tanggal Mulai Berlaku *</label>
                    <input type="datetime-local" name="starts_at" value="<?= old('starts_at', isset($promo['starts_at']) ? date('Y-m-d\TH:i', strtotime($promo['starts_at'])) : date('Y-m-d\T00:00')) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tanggal Berakhir *</label>
                    <input type="datetime-local" name="ends_at" value="<?= old('ends_at', isset($promo['ends_at']) ? date('Y-m-d\TH:i', strtotime($promo['ends_at'])) : date('Y-m-d\T23:59', strtotime('+1 year'))) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($promo) || !empty($promo['is_active'])) ? 'checked' : '' ?> class="rounded border-gray-300 text-velvoria-plum focus:ring-velvoria-plum">
                    <span class="text-gray-700 font-semibold">Aktifkan Kode Promo Sekarang</span>
                </label>
            </div>

            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?= base_url('admin/promotions') ?>" class="px-5 py-2.5 rounded-xl border border-gray-200 font-semibold text-gray-600 hover:text-gray-900">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-velvoria-plum text-white font-semibold hover:bg-velvoria-plum/90 shadow-sm">
                    Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
