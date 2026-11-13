<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Transparan & Terjangkau</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Daftar Harga & Biaya</h1>
        <p class="text-sm text-velvoria-muted mt-3">
            Semua tarif dihitung transparan dari sistem. Tanpa biaya tersembunyi.
        </p>
    </div>

    <!-- Notice Box -->
    <div class="mb-10 p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-start space-x-3">
        <i data-lucide="info" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
        <div class="space-y-1">
            <span class="font-bold">Ketentuan Penimbangan & Antar-Jemput:</span>
            <p class="text-amber-800 leading-relaxed">
                Biaya kiloan saat checkout merupakan estimasi awal. Total tagihan disesuaikan dengan berat riil saat ditimbang oleh staf kami di outlet. Minimum cucian kiloan adalah <strong><?= esc($settings['minimum_weight'] ?? 2) ?> kg</strong>. Ongkos antar-jemput <strong><?= format_rupiah((int)($settings['delivery_fee'] ?? 5000)) ?></strong> per pesanan, dan <strong>GRATIS</strong> untuk pesanan di atas <strong><?= format_rupiah((int)($settings['free_delivery_threshold'] ?? 50000)) ?></strong>.
            </p>
        </div>
    </div>

    <!-- Table 1: Kiloan -->
    <div class="bg-white rounded-2xl border border-velvoria-border shadow-sm mb-12 overflow-hidden">
        <div class="px-6 py-4 bg-velvoria-cream/50 border-b border-velvoria-border flex items-center justify-between">
            <h3 class="text-base font-bold text-velvoria-ink flex items-center space-x-2">
                <i data-lucide="scale" class="w-4 h-4 text-velvoria-plum"></i>
                <span>Layanan Laundry Kiloan</span>
            </h3>
            <span class="text-xs text-velvoria-muted">Min. <?= esc($settings['minimum_weight'] ?? 2) ?> kg</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-velvoria-border text-velvoria-muted text-[11px] uppercase tracking-wider">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Nama Layanan</th>
                        <th class="py-3.5 px-6">Estimasi Waktu</th>
                        <th class="py-3.5 px-6 text-right">Harga per Kg</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-velvoria-border/60">
                    <?php if (! empty($groupedServices['kiloan'])): ?>
                        <?php foreach ($groupedServices['kiloan'] as $srv): ?>
                            <tr class="hover:bg-velvoria-cream/30 transition-colors">
                                <td class="py-4 px-6 font-mono font-semibold text-velvoria-plum"><?= esc($srv['code']) ?></td>
                                <td class="py-4 px-6 font-medium text-velvoria-ink">
                                    <?= esc($srv['name']) ?>
                                    <?php if ($srv['is_express']): ?>
                                        <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Express</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-velvoria-muted"><?= esc($srv['estimated_hours']) ?> Jam</td>
                                <td class="py-4 px-6 text-right font-bold text-velvoria-ink"><?= format_rupiah($srv['price']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table 2: Satuan -->
    <div class="bg-white rounded-2xl border border-velvoria-border shadow-sm mb-12 overflow-hidden">
        <div class="px-6 py-4 bg-velvoria-cream/50 border-b border-velvoria-border">
            <h3 class="text-base font-bold text-velvoria-ink flex items-center space-x-2">
                <i data-lucide="tag" class="w-4 h-4 text-velvoria-plum"></i>
                <span>Layanan Laundry Satuan (Dry Cleaning & Care)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-velvoria-border text-velvoria-muted text-[11px] uppercase tracking-wider">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Item / Pakaian</th>
                        <th class="py-3.5 px-6">Estimasi Waktu</th>
                        <th class="py-3.5 px-6 text-right">Tarif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-velvoria-border/60">
                    <?php if (! empty($groupedServices['satuan'])): ?>
                        <?php foreach ($groupedServices['satuan'] as $srv): ?>
                            <tr class="hover:bg-velvoria-cream/30 transition-colors">
                                <td class="py-4 px-6 font-mono font-semibold text-velvoria-plum"><?= esc($srv['code']) ?></td>
                                <td class="py-4 px-6 font-medium text-velvoria-ink"><?= esc($srv['name']) ?></td>
                                <td class="py-4 px-6 text-velvoria-muted"><?= esc($srv['estimated_hours']) ?> Jam</td>
                                <td class="py-4 px-6 text-right font-bold text-velvoria-ink">
                                    <?= format_rupiah($srv['price']) ?> <span class="text-xs font-normal text-velvoria-muted">/ <?= esc($srv['unit']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CTA Box -->
    <div class="text-center p-8 bg-velvoria-plum text-white rounded-3xl space-y-4">
        <h3 class="text-xl font-bold">Siap Mengirimkan Pakaian Anda?</h3>
        <p class="text-xs sm:text-sm text-velvoria-cream/80 max-w-md mx-auto">
            Pesan sekarang dan tentukan jadwal penjemputan sesuai kenyamanan Anda.
        </p>
        <div class="pt-2">
            <a href="<?= base_url('customer/orders/create') ?>" class="px-7 py-3 rounded-xl bg-white text-velvoria-plum font-bold text-sm hover:bg-velvoria-cream transition-all inline-block shadow-md">
                Pesan Laundry Sekarang
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
