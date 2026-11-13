<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Panduan Pelanggan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Cara Kerja Layanan Velvoria</h1>
        <p class="text-sm text-velvoria-muted mt-3">
            Dari pemesanan online hingga pakaian kembali bersih dan harum di lemari Anda.
        </p>
    </div>

    <div class="space-y-8 relative before:absolute before:inset-0 before:left-8 md:before:left-1/2 before:w-0.5 before:bg-velvoria-border before:-z-0">
        <!-- Step 1 -->
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 group">
            <div class="md:w-5/12 text-left md:text-right">
                <span class="text-xs font-bold uppercase text-velvoria-plum tracking-wider">Langkah 1</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Pilih Layanan & Masukkan Estimasi</h3>
                <p class="text-xs sm:text-sm text-velvoria-muted mt-2 leading-relaxed">
                    Masuk ke website Velvoria dan pilih jenis layanan (kiloan atau satuan). Masukkan perkiraan berat atau jumlah pakaian yang akan dicuci.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-velvoria-plum text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                1
            </div>
            <div class="md:w-5/12 bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm">
                <div class="flex items-center space-x-3 text-xs text-velvoria-muted">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Tersedia layanan Reguler & Express</span>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="relative z-10 flex flex-col md:flex-row-reverse items-center justify-between gap-8 group">
            <div class="md:w-5/12 text-left">
                <span class="text-xs font-bold uppercase text-velvoria-plum tracking-wider">Langkah 2</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Tentukan Antar Sendiri atau Penjemputan</h3>
                <p class="text-xs sm:text-sm text-velvoria-muted mt-2 leading-relaxed">
                    Pilih antar cucian ke outlet terdekat atau request kurir kami untuk menjemput pakaian kotor langsung ke rumah, kantor, atau apartemen Anda.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-velvoria-plum text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                2
            </div>
            <div class="md:w-5/12 bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm">
                <div class="flex items-center space-x-3 text-xs text-velvoria-muted">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Gratis ongkir untuk order di atas Rp50.000</span>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 group">
            <div class="md:w-5/12 text-left md:text-right">
                <span class="text-xs font-bold uppercase text-velvoria-plum tracking-wider">Langkah 3</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Pembayaran Digital via Midtrans Snap</h3>
                <p class="text-xs sm:text-sm text-velvoria-muted mt-2 leading-relaxed">
                    Bayar dengan aman dan instan menggunakan metode favorit Anda: QRIS (GoPay, OVO, ShopeePay), Transfer Bank Virtual Account, atau Kartu Kredit.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-velvoria-plum text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                3
            </div>
            <div class="md:w-5/12 bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm">
                <div class="flex items-center space-x-3 text-xs text-velvoria-muted">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Otomatis terverifikasi tanpa upload bukti transfer</span>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="relative z-10 flex flex-col md:flex-row-reverse items-center justify-between gap-8 group">
            <div class="md:w-5/12 text-left">
                <span class="text-xs font-bold uppercase text-velvoria-plum tracking-wider">Langkah 4</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Proses Pencucian & Pantau Status Real-Time</h3>
                <p class="text-xs sm:text-sm text-velvoria-muted mt-2 leading-relaxed">
                    Pakaian ditimbang akurat, dicuci higienis, dikeringkan mesin, disetrika uap, dan dilipat rapi. Pantau status cucian Anda langsung di dashboard.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-velvoria-plum text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                4
            </div>
            <div class="md:w-5/12 bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm">
                <div class="flex items-center space-x-3 text-xs text-velvoria-muted">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Detergen antibakteri & pelembut ramah kain</span>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 group">
            <div class="md:w-5/12 text-left md:text-right">
                <span class="text-xs font-bold uppercase text-velvoria-plum tracking-wider">Langkah 5</span>
                <h3 class="text-xl font-bold text-velvoria-ink mt-1">Pakaian Bersih Siap Digunakan</h3>
                <p class="text-xs sm:text-sm text-velvoria-muted mt-2 leading-relaxed">
                    Cucian dikemas dalam plastik kedap debu. Ambil di outlet atau tunggu kurir mengantarkan kembali ke pintu rumah Anda.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-lg border-4 border-white">
                5
            </div>
            <div class="md:w-5/12 bg-white p-6 rounded-2xl border border-velvoria-border shadow-sm">
                <div class="flex items-center space-x-3 text-xs text-velvoria-muted">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Garansi cuci ulang jika hasil tidak bersih</span>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-16">
        <a href="<?= base_url('customer/orders/create') ?>" class="px-8 py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-sm hover:bg-velvoria-plum/90 shadow-md inline-block">
            Mulai Pesanan Pertama Anda
        </a>
    </div>
</div>

<?= $this->endSection() ?>
