<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Tentang Kami</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Mengenal Velvoria Laundry</h1>
        <p class="text-sm text-velvoria-muted mt-3">
            Hadir untuk mengubah cara Anda merawat pakaian favorit dengan standar mutu profesional dan teknologi modern.
        </p>
    </div>

    <!-- Story Card -->
    <div class="bg-white rounded-3xl p-8 md:p-12 border border-velvoria-border shadow-sm space-y-6 mb-12">
        <h2 class="text-2xl font-bold text-velvoria-ink">"Baju Bersih, Waktu Lebih Luang."</h2>
        <p class="text-sm text-velvoria-muted leading-relaxed">
            Velvoria Laundry didirikan atas pemahaman mendalam tentang berharganya waktu Anda di tengah rutinitas harian yang padat. Mencuci, mengeringkan, dan menyetrika pakaian menyita waktu berjam-jam setiap minggunya yang seharusnya bisa Anda nikmati bersama keluarga atau istirahat berkualitas.
        </p>
        <p class="text-sm text-velvoria-muted leading-relaxed">
            Kami menghadirkan perpaduan antara teknologi pencucian terkini, detergen ramah serat ramah lingkungan, dan sistem pemesanan online terintegrasi. Setiap helai pakaian ditangani dengan teliti dan penuh kehati-hatian.
        </p>

        <div class="pt-6 border-t border-velvoria-border grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-4 rounded-2xl bg-velvoria-cream">
                <span class="block text-2xl font-extrabold text-velvoria-plum">100%</span>
                <span class="text-xs text-velvoria-muted mt-1 block">Air Bersih Terfiltrasi</span>
            </div>
            <div class="p-4 rounded-2xl bg-velvoria-cream">
                <span class="block text-2xl font-extrabold text-velvoria-plum">3 Tahap</span>
                <span class="text-xs text-velvoria-muted mt-1 block">Quality Control Ketat</span>
            </div>
            <div class="p-4 rounded-2xl bg-velvoria-cream">
                <span class="block text-2xl font-extrabold text-velvoria-plum">24 Jam</span>
                <span class="text-xs text-velvoria-muted mt-1 block">Layanan Express Tersedia</span>
            </div>
        </div>
    </div>

    <!-- Outlet Info -->
    <div class="bg-velvoria-plum text-white rounded-3xl p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <h3 class="text-xl font-bold">Kunjungi Outlet Resmi Kami</h3>
            <p class="text-xs sm:text-sm text-velvoria-cream/80 max-w-lg leading-relaxed">
                <?= esc($settings['store_address'] ?? 'Jl. Senopati Raya No. 45, Kebayoran Baru, Jakarta Selatan') ?>
            </p>
            <p class="text-xs text-velvoria-lilac font-medium">
                <?= esc($settings['operating_hours'] ?? 'Senin - Sabtu: 07:00 - 21:00 WIB | Minggu: 08:00 - 18:00 WIB') ?>
            </p>
        </div>
        <a href="<?= base_url('kontak') ?>" class="px-6 py-3 rounded-xl bg-white text-velvoria-plum font-bold text-xs hover:bg-velvoria-cream transition-all shrink-0">
            Hubungi & Lokasi
        </a>
    </div>
</div>

<?= $this->endSection() ?>
