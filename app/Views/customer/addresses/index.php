<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-velvoria-ink">Alamat Antar - Jemput</h1>
            <p class="text-xs text-velvoria-muted mt-1">Kelola daftar lokasi penjemputan cucian Anda (rumah, kantor, kost, apartemen).</p>
        </div>
        <a href="<?= base_url('customer/addresses/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Alamat Baru</span>
        </a>
    </div>

    <?php if (empty($addresses)): ?>
        <div class="bg-white rounded-3xl p-16 text-center border border-velvoria-border shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-full bg-velvoria-cream flex items-center justify-center mx-auto text-velvoria-muted">
                <i data-lucide="map-pin-off" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-velvoria-ink">Belum Ada Alamat Tersimpan</h3>
            <p class="text-xs text-velvoria-muted max-w-sm mx-auto">
                Tambahkan alamat rumah atau kantor agar kurir Velvoria dapat menjemput cucian Anda secara otomatis.
            </p>
            <div class="pt-2">
                <a href="<?= base_url('customer/addresses/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white text-xs font-semibold inline-block">
                    Tambah Alamat Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php foreach ($addresses as $addr): ?>
                <div class="bg-white p-6 rounded-3xl border border-velvoria-border shadow-sm flex flex-col justify-between space-y-4 relative <?= $addr['is_primary'] ? 'ring-2 ring-velvoria-plum/20' : '' ?>">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-velvoria-ink"><?= esc($addr['label']) ?></span>
                            <?php if ($addr['is_primary']): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-velvoria-plum/10 text-velvoria-plum text-[10px] font-bold">
                                    Alamat Utama
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="text-xs text-velvoria-ink font-semibold">
                            <?= esc($addr['recipient_name']) ?> <span class="font-normal text-velvoria-muted">(<?= esc($addr['phone']) ?>)</span>
                        </div>

                        <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                            <?= esc($addr['address']) ?>
                            <?php if (!empty($addr['district'])): ?>, <?= esc($addr['district']) ?><?php endif; ?>
                            <?php if (!empty($addr['city'])): ?>, <?= esc($addr['city']) ?><?php endif; ?>
                            <?php if (!empty($addr['postal_code'])): ?> <?= esc($addr['postal_code']) ?><?php endif; ?>
                        </p>

                        <?php if (!empty($addr['notes'])): ?>
                            <p class="text-[11px] text-velvoria-plum mt-2 bg-velvoria-cream/50 p-2 rounded-lg">
                                <strong>Catatan Kurir:</strong> <?= esc($addr['notes']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="pt-4 border-t border-velvoria-border flex items-center justify-between text-xs">
                        <div>
                            <?php if (! $addr['is_primary']): ?>
                                <form action="<?= base_url('customer/addresses/' . $addr['id'] . '/primary') ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-xs font-semibold text-velvoria-plum hover:underline">
                                        Jadikan Utama
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="<?= base_url('customer/addresses/' . $addr['id'] . '/edit') ?>" class="text-velvoria-muted hover:text-velvoria-ink font-medium">
                                Ubah
                            </a>
                            <form action="<?= base_url('customer/addresses/' . $addr['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus alamat ini?')" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
