<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Toko & Operasional</h1>
        <p class="text-xs text-gray-500 mt-1">Ubah identitas outlet, nomor WhatsApp, jam buka, tarif antar-jemput, dan kebijakan pembatalan.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm">
        <form action="<?= base_url('admin/settings') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nama Usaha / Brand *</label>
                    <input type="text" name="store_name" value="<?= esc($settings['store_name'] ?? 'Velvoria Laundry') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Tagline Usaha</label>
                    <input type="text" name="store_tagline" value="<?= esc($settings['store_tagline'] ?? 'Baju Bersih, Waktu Lebih Luang.') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Alamat Lengkap Outlet *</label>
                <textarea name="store_address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"><?= esc($settings['store_address'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nomor WhatsApp Pelanggan *</label>
                    <input type="text" name="store_whatsapp" value="<?= esc($settings['store_whatsapp'] ?? '6281234567890') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="62812xxxx">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Nomor Telepon Toko</label>
                    <input type="text" name="store_phone" value="<?= esc($settings['store_phone'] ?? '021-12345678') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Email Toko</label>
                    <input type="email" name="store_email" value="<?= esc($settings['store_email'] ?? 'halo@velvorialaundry.com') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Jam Operasional</label>
                <input type="text" name="operating_hours" value="<?= esc($settings['operating_hours'] ?? 'Senin - Sabtu: 07:00 - 21:00 | Minggu: 08:00 - 18:00') ?>"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
            </div>

            <div class="pt-4 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Minimum Kiloan (Kg) *</label>
                    <input type="number" step="0.5" name="minimum_weight" value="<?= esc($settings['minimum_weight'] ?? '2') ?>" required min="1"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Ongkir Antar-Jemput (Rp) *</label>
                    <input type="number" name="delivery_fee" value="<?= esc($settings['delivery_fee'] ?? '5000') ?>" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">Ambang Gratis Ongkir (Rp) *</label>
                    <input type="number" name="free_delivery_threshold" value="<?= esc($settings['free_delivery_threshold'] ?? '50000') ?>" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1.5">Kebijakan Pembatalan Pesanan</label>
                <textarea name="cancellation_policy" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"><?= esc($settings['cancellation_policy'] ?? '') ?></textarea>
            </div>

            <div class="pt-6 border-t border-gray-200 flex justify-end">
                <button type="submit" class="px-7 py-3 rounded-xl bg-velvoria-plum text-white font-bold hover:bg-velvoria-plum/90 shadow-sm transition-all">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
