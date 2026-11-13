<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 md:pt-20 md:pb-32 velvoria-hero-loop">
    <!-- Subtle Background Elements -->
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-velvoria-lilac/15 blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-[400px] h-[400px] rounded-full bg-velvoria-sage/20 blur-3xl -z-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <!-- Left Headline & CTA -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-velvoria-plum/10 text-velvoria-plum text-xs font-semibold tracking-wide">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-velvoria-plum"></i>
                    <span>Solusi Praktis Pakaian Bersih & Higienis</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-velvoria-ink leading-[1.15]">
                    Urusan Cucian?<br>
                    <span class="text-velvoria-plum">Biar Kami yang Bereskan.</span>
                </h1>

                <p class="text-base sm:text-lg text-velvoria-muted max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Layanan laundry kiloan dan satuan berstandar profesional. Nikmati kemudahan pesan online, antar-jemput tepat waktu, dan pakaian yang kembali bersih, rapi, serta wangi menenangkan.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                    <a href="<?= base_url('register') ?>" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-velvoria-plum text-white font-semibold text-sm hover:bg-velvoria-plum/90 shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2">
                        <span>Pesan Sekarang</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="<?= base_url('harga') ?>" class="w-full sm:w-auto px-7 py-4 rounded-xl bg-white text-velvoria-ink border border-velvoria-border font-medium text-sm hover:bg-velvoria-cream hover:border-velvoria-plum/30 transition-all flex items-center justify-center space-x-2 shadow-sm">
                        <i data-lucide="tag" class="w-4 h-4 text-velvoria-plum"></i>
                        <span>Lihat Daftar Harga</span>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-8 border-t border-velvoria-border/60 grid grid-cols-2 sm:grid-cols-4 gap-4 text-left">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span class="text-xs font-medium text-velvoria-ink">Higienis & Rapih</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="clock" class="w-4 h-4 text-velvoria-plum shrink-0"></i>
                        <span class="text-xs font-medium text-velvoria-ink">Tepat Waktu</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="truck" class="w-4 h-4 text-blue-600 shrink-0"></i>
                        <span class="text-xs font-medium text-velvoria-ink">Antar - Jemput</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-purple-600 shrink-0"></i>
                        <span class="text-xs font-medium text-velvoria-ink">Midtrans Aman</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-velvoria-border/80 velvoria-care-card">
                    <div class="flex items-center justify-between pb-6 border-b border-velvoria-border">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center font-bold">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-velvoria-plum"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-velvoria-ink velvoria-care-heading">Velvoria Care System</h3>
                                <p class="text-xs text-velvoria-muted velvoria-care-subheading">3 Tahap Kontrol Kualitas</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-semibold">Tersertifikasi</span>
                    </div>

                    <div class="py-6 space-y-4">
                        <div class="p-3.5 rounded-2xl bg-velvoria-cream flex items-start space-x-3 velvoria-care-step">
                            <div class="w-7 h-7 rounded-lg bg-velvoria-plum text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</div>
                            <div>
                                <h4 class="text-xs font-bold text-velvoria-ink">Sortir & Inspeksi Noda</h4>
                                <p class="text-[11px] text-velvoria-muted mt-0.5">Pemisahan warna, jenis bahan, dan spot treatment noda membandel.</p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-velvoria-cream flex items-start space-x-3 velvoria-care-step">
                            <div class="w-7 h-7 rounded-lg bg-velvoria-plum text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</div>
                            <div>
                                <h4 class="text-xs font-bold text-velvoria-ink">Detergen Khusus & Pewangi Lembut</h4>
                                <p class="text-[11px] text-velvoria-muted mt-0.5">Formula antibakteri ramah serat kain dengan aroma mewah tahan lama.</p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-velvoria-cream flex items-start space-x-3 velvoria-care-step">
                            <div class="w-7 h-7 rounded-lg bg-velvoria-plum text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</div>
                            <div>
                                <h4 class="text-xs font-bold text-velvoria-ink">Setrika Uap & Packing Higienis</h4>
                                <p class="text-[11px] text-velvoria-muted mt-0.5">Setrika uap tanpa merusak serat, dikemas rapi terlindung debu.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-velvoria-border flex items-center justify-between text-xs">
                        <span class="text-velvoria-muted velvoria-care-price-label">Mulai dari</span>
                        <span class="text-lg font-bold text-velvoria-plum velvoria-care-price">Rp7.000 <span class="text-xs font-normal text-velvoria-muted">/ kg</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="py-16 md:py-24 bg-white border-y border-velvoria-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Layanan Unggulan</span>
            <h2 class="text-3xl font-bold tracking-tight text-velvoria-ink mt-2">Pilihan Perawatan Cucian Anda</h2>
            <p class="text-sm text-velvoria-muted mt-3">Tersedia layanan reguler hemat dan express kilat sesuai kebutuhan mobilitas harian Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach (array_slice($services, 0, 6) as $service): ?>
                <div class="p-6 rounded-2xl bg-velvoria-cream/50 border border-velvoria-border hover:border-velvoria-plum/40 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 rounded-md bg-white text-velvoria-plum text-xs font-bold tracking-wide uppercase border border-velvoria-border">
                                <?= esc($service['code']) ?>
                            </span>
                            <?php if ($service['is_express']): ?>
                                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-semibold flex items-center space-x-1">
                                    <i data-lucide="zap" class="w-3 h-3 text-amber-600"></i>
                                    <span>Express 24 Jam</span>
                                </span>
                            <?php else: ?>
                                <span class="text-xs text-velvoria-muted flex items-center space-x-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span><?= esc($service['estimated_hours']) ?> Jam</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-lg font-bold text-velvoria-ink group-hover:text-velvoria-plum transition-colors">
                            <?= esc($service['name']) ?>
                        </h3>
                        <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                            <?= esc($service['description'] ?? 'Pembersihan profesional berstandar mutu tinggi.') ?>
                        </p>
                    </div>

                    <div class="pt-6 mt-6 border-t border-velvoria-border/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-velvoria-muted block">Harga</span>
                            <span class="text-lg font-bold text-velvoria-ink">
                                <?= format_rupiah($service['price']) ?> <span class="text-xs font-normal text-velvoria-muted">/ <?= esc($service['unit']) ?></span>
                            </span>
                        </div>
                        <a href="<?= base_url('customer/orders/create') ?>" class="px-4 py-2 rounded-xl bg-velvoria-plum text-white text-xs font-semibold hover:bg-velvoria-plum/90 transition-all">
                            Pesan
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-10">
            <a href="<?= base_url('layanan') ?>" class="inline-flex items-center space-x-2 text-sm font-semibold text-velvoria-plum hover:text-velvoria-plum/80">
                <span>Lihat Semua Layanan & Paket Satuan</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</section>

<!-- Cara Kerja (4 Langkah) -->
<section class="py-16 md:py-24 bg-velvoria-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Alur Mudah</span>
            <h2 class="text-3xl font-bold tracking-tight text-velvoria-ink mt-2">Hanya 4 Langkah Praktis</h2>
            <p class="text-sm text-velvoria-muted mt-3">Tidak perlu antre atau membuang waktu di jalan. Seluruh proses terpantau dari ponsel Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm text-center relative group">
                <div class="w-12 h-12 rounded-xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    1
                </div>
                <h3 class="text-base font-bold text-velvoria-ink">Pilih Layanan</h3>
                <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                    Pilih kategori kiloan atau satuan, masukkan estimasi jumlah cucian dan catatan khusus.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm text-center relative group">
                <div class="w-12 h-12 rounded-xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    2
                </div>
                <h3 class="text-base font-bold text-velvoria-ink">Antar atau Jemput</h3>
                <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                    Pilih antar langsung ke outlet atau request penjemputan oleh kurir Velvoria ke alamat Anda.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm text-center relative group">
                <div class="w-12 h-12 rounded-xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    3
                </div>
                <h3 class="text-base font-bold text-velvoria-ink">Proses & Pantau</h3>
                <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                    Petugas menimbang, mencuci, dan menyetrika. Pantau update setiap tahapan secara transparan.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm text-center relative group">
                <div class="w-12 h-12 rounded-xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center mx-auto mb-4 font-bold text-lg">
                    4
                </div>
                <h3 class="text-base font-bold text-velvoria-ink">Terima Bersih</h3>
                <p class="text-xs text-velvoria-muted mt-2 leading-relaxed">
                    Cucian wangi, steril, dan rapi siap Anda ambil di outlet atau diantar kembali ke rumah Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Active Promos (if any) -->
<?php if (! empty($promotions)): ?>
<section class="py-12 bg-white border-b border-velvoria-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Penawaran Spesial</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Gunakan Promo & Hemat Lebih Banyak</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($promotions as $promo): ?>
                <div class="p-6 rounded-2xl bg-gradient-to-r from-velvoria-plum to-velvoria-ink text-white shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="inline-block px-2.5 py-0.5 rounded bg-white/20 text-velvoria-lilac text-[10px] font-bold uppercase tracking-wide">
                            Kode Voucher
                        </span>
                        <div class="text-2xl font-mono font-bold tracking-wider text-white">
                            <?= esc($promo['code']) ?>
                        </div>
                        <h4 class="text-sm font-semibold text-white/90"><?= esc($promo['name']) ?></h4>
                        <p class="text-xs text-white/70">
                            Diskon <?= $promo['discount_type'] === 'percent' ? $promo['discount_value'] . '%' : format_rupiah($promo['discount_value']) ?>
                            (Min. Order <?= format_rupiah($promo['minimum_order']) ?>)
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-white/60">
                        <span>Berlaku s/d <?= date('d M Y', strtotime($promo['ends_at'])) ?></span>
                        <a href="<?= base_url('customer/orders/create') ?>" class="text-velvoria-lilac font-bold hover:underline">Gunakan &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonial Section -->
<section class="py-16 md:py-24 bg-velvoria-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Ulasan Pelanggan</span>
            <h2 class="text-3xl font-bold tracking-tight text-velvoria-ink mt-2">Dipercaya Ratusan Keluarga</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 mb-3">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-xs text-velvoria-ink leading-relaxed italic">
                        "Layanan antar-jemputnya sangat tepat waktu. Wangi pakaiannya mewah dan tidak menyengat. Sangat membantu saya yang sibuk kerja setiap hari."
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-velvoria-border flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold text-xs flex items-center justify-center">S</div>
                    <div>
                        <span class="block text-xs font-bold text-velvoria-ink">Siti Rahma</span>
                        <span class="block text-[10px] text-velvoria-muted">Kebayoran Baru</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 mb-3">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-xs text-velvoria-ink leading-relaxed italic">
                        "Pencucian kemeja dan jasnya sangat rapi! Setrika uapnya bikin kerah kemeja tegak sempurna. Tracking pesanan via website juga sangat informatif."
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-velvoria-border flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold text-xs flex items-center justify-center">D</div>
                    <div>
                        <span class="block text-xs font-bold text-velvoria-ink">Dimas Pratama</span>
                        <span class="block text-[10px] text-velvoria-muted">Senopati</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 mb-3">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-xs text-velvoria-ink leading-relaxed italic">
                        "Cuci bed cover dan selimut hasilnya bersih maksimal, bebas debu tungau. Pembayaran pakai QRIS Midtrans instan langsung konfirmasi."
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-velvoria-border flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold text-xs flex items-center justify-center">A</div>
                    <div>
                        <span class="block text-xs font-bold text-velvoria-ink">Anisa Wulandari</span>
                        <span class="block text-[10px] text-velvoria-muted">Pancoran</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bottom CTA Banner -->
<section class="py-16 bg-velvoria-plum text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Siap Membebaskan Waktu Anda dari Urusan Cucian?</h2>
        <p class="text-sm sm:text-base text-velvoria-cream/80 max-w-xl mx-auto leading-relaxed">
            Daftar akun sekarang dan rasakan pengalaman laundry modern dengan standar kualitas hotel bintang lima.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="<?= base_url('register') ?>" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white text-velvoria-plum font-bold text-sm hover:bg-velvoria-cream transition-all shadow-lg">
                Daftar Akun Baru
            </a>
            <a href="<?= base_url('kontak') ?>" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-sm transition-all border border-white/20">
                Hubungi Kami via WhatsApp
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
