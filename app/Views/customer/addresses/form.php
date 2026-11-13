<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('customer/addresses') ?>" class="p-2 rounded-xl border border-velvoria-border text-velvoria-muted hover:text-velvoria-ink bg-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <h1 class="text-2xl font-bold text-velvoria-ink"><?= esc($title) ?></h1>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-velvoria-border shadow-sm">
        <form action="<?= $address ? base_url('customer/addresses/' . $address['id']) : base_url('customer/addresses') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Label Alamat *</label>
                <input type="text" name="label" value="<?= old('label', $address['label'] ?? '') ?>" required
                       class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                       placeholder="Contoh: Rumah, Kantor, Kost">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nama Penerima / Penghuni *</label>
                    <input type="text" name="recipient_name" value="<?= old('recipient_name', $address['recipient_name'] ?? session()->get('user_name')) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nomor Telepon / WhatsApp *</label>
                    <input type="text" name="phone" value="<?= old('phone', $address['phone'] ?? '') ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="08123456789">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Alamat Lengkap (Jalan, No. Rumah, RT/RW, Patokan) *</label>
                <textarea name="address" rows="3" required
                          class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                          placeholder="Jl. Anggrek No. 12, Pagar hitam sebelah warung"><?= old('address', $address['address'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Kecamatan</label>
                    <input type="text" name="district" value="<?= old('district', $address['district'] ?? '') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Kebayoran Baru">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Kota / Kabupaten</label>
                    <input type="text" name="city" value="<?= old('city', $address['city'] ?? 'Jakarta Selatan') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Kode Pos</label>
                    <input type="text" name="postal_code" value="<?= old('postal_code', $address['postal_code'] ?? '') ?>"
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="12180">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Catatan Khusus untuk Kurir (Opsional)</label>
                <input type="text" name="notes" value="<?= old('notes', $address['notes'] ?? '') ?>"
                       class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                       placeholder="Contoh: Titip ke satpam lobby / bel pagar rusak">
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 text-xs text-velvoria-ink cursor-pointer">
                    <input type="checkbox" name="is_primary" value="1" <?= (!empty($address['is_primary']) || empty($address)) ? 'checked' : '' ?>
                           class="rounded border-velvoria-border text-velvoria-plum focus:ring-velvoria-plum">
                    <span>Jadikan sebagai alamat utama penjemputan</span>
                </label>
            </div>

            <div class="pt-4 border-t border-velvoria-border flex justify-end space-x-3">
                <a href="<?= base_url('customer/addresses') ?>" class="px-5 py-2.5 rounded-xl border border-velvoria-border text-xs font-semibold text-velvoria-muted hover:text-velvoria-ink">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs hover:bg-velvoria-plum/90 shadow-sm">
                    Simpan Alamat
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
