<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'all' }">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Katalog Perawatan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Daftar Layanan Velvoria</h1>
        <p class="text-sm text-velvoria-muted mt-3">
            Pilih jenis pencucian yang Anda butuhkan. Semua pakaian dicuci menggunakan air terfiltrasi dan detergen ramah lingkungan.
        </p>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex justify-center space-x-2 sm:space-x-4 mb-10 overflow-x-auto pb-2">
        <button @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-velvoria-plum text-white font-semibold' : 'bg-white text-velvoria-muted hover:text-velvoria-ink border border-velvoria-border'"
                class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all shadow-sm">
            Semua Layanan
        </button>
        <?php foreach ($categories as $cat): ?>
            <button @click="activeTab = '<?= esc($cat['slug']) ?>'" 
                    :class="activeTab === '<?= esc($cat['slug']) ?>' ? 'bg-velvoria-plum text-white font-semibold' : 'bg-white text-velvoria-muted hover:text-velvoria-ink border border-velvoria-border'"
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all shadow-sm">
                <?= esc($cat['name']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Service Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($services as $srv): ?>
            <div x-show="activeTab === 'all' || activeTab === '<?= esc($srv['category_slug'] ?? '') ?>'"
                 class="bg-white rounded-2xl p-6 border border-velvoria-border hover:border-velvoria-plum/40 hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded bg-velvoria-cream text-velvoria-plum font-mono text-xs font-bold border border-velvoria-border">
                            <?= esc($srv['code']) ?>
                        </span>
                        <?php if ($srv['is_express']): ?>
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold flex items-center space-x-1">
                                <i data-lucide="zap" class="w-3 h-3 text-amber-600"></i>
                                <span>Express 24 Jam</span>
                            </span>
                        <?php else: ?>
                            <span class="text-xs text-velvoria-muted flex items-center space-x-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <span><?= esc($srv['estimated_hours']) ?> Jam</span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="text-lg font-bold text-velvoria-ink group-hover:text-velvoria-plum transition-colors">
                        <?= esc($srv['name']) ?>
                    </h3>
                    <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                        <?= esc($srv['description'] ?? 'Pembersihan higienis berkualitas tinggi.') ?>
                    </p>

                    <div class="mt-4 pt-3 border-t border-velvoria-border/60 text-xs text-velvoria-muted space-y-1">
                        <div>Min. Pemesanan: <span class="font-semibold text-velvoria-ink"><?= $srv['minimum_quantity'] ?> <?= esc($srv['unit']) ?></span></div>
                        <div>Kategori: <span class="font-semibold text-velvoria-ink"><?= esc($srv['category_name'] ?? '-') ?></span></div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-velvoria-border flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-velvoria-muted block">Tarif</span>
                        <span class="text-xl font-bold text-velvoria-plum">
                            <?= format_rupiah($srv['price']) ?> <span class="text-xs font-normal text-velvoria-muted">/ <?= esc($srv['unit']) ?></span>
                        </span>
                    </div>
                    <a href="<?= base_url('customer/orders/create') ?>" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white text-xs font-bold hover:bg-velvoria-plum/90 transition-all flex items-center space-x-1 shadow-sm">
                        <span>Pesan</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
