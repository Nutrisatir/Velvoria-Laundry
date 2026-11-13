<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<div class="py-12 md:py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Pusat Bantuan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Tanya Jawab (FAQ)</h1>
        <p class="text-sm text-velvoria-muted mt-3">
            Pertanyaan yang sering diajukan pelanggan seputar layanan, penimbangan, pembayaran, dan operasional Velvoria.
        </p>
    </div>

    <div class="space-y-4" x-data="{ activeAccordion: 1 }">
        <!-- Q1 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 1 ? null : 1)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Berapa minimum berat untuk pemesanan laundry kiloan?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 1 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 1" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Minimum pemesanan laundry kiloan di Velvoria adalah 2 kilogram. Jika cucian Anda di bawah 2 kg, sistem akan tetap menghitung tarif minimum seberat 2 kg.
            </div>
        </div>

        <!-- Q2 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 2 ? null : 2)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Bagaimana jika berat aktual berbeda dengan estimasi berat saat checkout?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 2 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 2" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Saat pakaian tiba di outlet atau dijemput kurir, tim kami akan menimbang ulang dengan timbangan digital terkalibrasi. Berat riil tersebut akan dicatat di sistem sebagai berat aktual. Jika terdapat selisih tagihan, admin akan mengonfirmasi kepada Anda sebelum proses pencucian.
            </div>
        </div>

        <!-- Q3 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 3 ? null : 3)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Berapa lama estimasi proses pengerjaan cucian?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 3 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 3" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Untuk layanan Reguler, waktu pengerjaan adalah 2-3 hari kerja (48-72 jam). Tersedia layanan Express dengan jaminan selesai dalam 1 hari kerja (24 jam) untuk cucian kilat.
            </div>
        </div>

        <!-- Q4 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 4 ? null : 4)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Metode pembayaran apa saja yang didukung?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 4 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 4" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Kami terintegrasi resmi dengan Midtrans Snap. Anda dapat membayar melalui QRIS (GoPay, OVO, Dana, ShopeePay), Transfer Bank Virtual Account (BCA, Mandiri, BNI, BRI, Permata), serta Kartu Kredit/Debit berlogo Visa dan Mastercard.
            </div>
        </div>

        <!-- Q5 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 5 ? null : 5)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Bagaimana kebijakan pembatalan pesanan?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 5 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 5" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Pesanan hanya dapat dibatalkan jika belum memasuki tahap pencucian (washing). Jika pakaian sudah dijemput oleh kurir dan dibatalkan sebelum dicuci, biaya penjemputan tetap berlaku dan pakaian akan dikembalikan.
            </div>
        </div>

        <!-- Q6 -->
        <div class="bg-white rounded-2xl border border-velvoria-border overflow-hidden">
            <button @click="activeAccordion = (activeAccordion === 6 ? null : 6)" 
                    class="w-full px-6 py-5 text-left flex items-center justify-between font-bold text-sm sm:text-base text-velvoria-ink">
                <span>Apakah pakaian luntur atau rawan rusak dipisah?</span>
                <i data-lucide="chevron-down" class="w-5 h-5 text-velvoria-muted transition-transform" :class="activeAccordion === 6 ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="activeAccordion === 6" x-collapse class="px-6 pb-5 text-xs sm:text-sm text-velvoria-muted leading-relaxed border-t border-velvoria-border/60 pt-4">
                Ya! Setiap pakaian melewati tahap sortir awal untuk memisahkan warna terang, gelap, dan bahan sensitif. Anda juga dapat menyertakan instruksi khusus pada kolom catatan saat membuat pesanan.
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
