<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl p-8 md:p-12 border border-velvoria-border shadow-sm space-y-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-velvoria-ink">Syarat & Ketentuan Layanan</h1>
        <p class="text-xs text-velvoria-muted">Terakhir diperbarui: 12 November 2026</p>

        <div class="space-y-4 text-xs sm:text-sm text-velvoria-ink leading-relaxed">
            <h3 class="font-bold text-base text-velvoria-plum">1. Ketentuan Umum</h3>
            <p class="text-velvoria-muted">
                Dengan membuat pesanan melalui situs web Velvoria Laundry, pelanggan dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.
            </p>

            <h3 class="font-bold text-base text-velvoria-plum">2. Penimbangan & Penghitungan Cucian</h3>
            <p class="text-velvoria-muted">
                Jumlah dan berat cucian yang diisi pelanggan saat checkout bersifat estimasi. Berat dan kuantitas akhir yang menjadi acuan tagihan adalah hasil penimbangan fisik oleh petugas Velvoria di outlet. Minimum transaksi kiloan adalah 2 kg.
            </p>

            <h3 class="font-bold text-base text-velvoria-plum">3. Barang Berharga & Kelalaian</h3>
            <p class="text-velvoria-muted">
                Pelanggan wajib memeriksa dan mengosongkan seluruh saku pakaian dari uang, perhiasan, pulpen, atau benda tajam sebelum menyerahkan cucian. Velvoria Laundry tidak bertanggung jawab atas kehilangan atau kerusakan barang berharga yang tertinggal di dalam saku.
            </p>

            <h3 class="font-bold text-base text-velvoria-plum">4. Kebijakan Kerusakan & Luntur</h3>
            <p class="text-velvoria-muted">
                Kami menerapkan prosedur sortir dan detergen berstandar tinggi. Untuk pakaian dengan instruksi pencucian khusus (Dry Clean Only) atau potensi luntur tinggi, pelanggan wajib memberitahukan secara tertulis pada catatan pesanan.
            </p>

            <h3 class="font-bold text-base text-velvoria-plum">5. Pembatalan & Pengembalian Dana</h3>
            <p class="text-velvoria-muted">
                Pesanan hanya dapat dibatalkan sebelum memasuki proses pencucian (washing). Pengembalian dana untuk transaksi yang dibatalkan diproses sesuai kebijakan merchant Midtrans dan prosedur administrasi toko.
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
