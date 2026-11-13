<?= $this->extend('layouts/guest') ?>
<?= $this->section('content') ?>

<div class="py-12 md:py-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12 velvoria-reveal">
        <span class="text-xs font-bold uppercase tracking-widest text-velvoria-plum">Layanan Pelanggan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-velvoria-ink mt-2">Hubungi Tim Velvoria</h1>
        <p class="text-sm text-velvoria-muted mt-3">Ada pertanyaan, kendala cucian, atau kebutuhan kerja sama korporat. Tim kami siap membantu.</p>
    </div>

    <?php
        $outletAddress = trim((string) ($settings['store_address'] ?? 'Jl. Senopati Raya No. 45, Kebayoran Baru, Jakarta Selatan'));
        $mapQuery = rawurlencode($outletAddress);
        $mapSearchUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapQuery;
        $mapEmbedUrl = 'https://maps.google.com/maps?q=' . $mapQuery . '&output=embed';
        $whatsapp = preg_replace('/[^0-9]/', '', (string) ($settings['store_whatsapp'] ?? '6281234567890'));
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">
        <div class="lg:col-span-5 space-y-5">
            <article class="bg-white p-6 rounded-3xl border border-velvoria-border shadow-sm space-y-4 velvoria-reveal">
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center shrink-0">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-velvoria-ink">Alamat Outlet Velvoria</h2>
                        <p class="text-xs text-velvoria-muted leading-relaxed mt-1"><?= esc($outletAddress) ?></p>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden border border-velvoria-border bg-velvoria-cream">
                    <iframe title="Peta lokasi outlet Velvoria" src="<?= esc($mapEmbedUrl) ?>" width="100%" height="230" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
                <a href="<?= esc($mapSearchUrl) ?>" target="_blank" rel="noopener noreferrer"
                   class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-velvoria-plum px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-velvoria-ink hover:-translate-y-0.5 hover:shadow-lg">
                    <i data-lucide="map-pinned" class="h-4 w-4"></i>
                    <span>Lihat Rute di Google Maps</span>
                    <i data-lucide="external-link" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1"></i>
                </a>
            </article>

            <article class="bg-emerald-50 border border-emerald-200 p-6 rounded-3xl text-emerald-950 space-y-3 velvoria-reveal">
                <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center"><i data-lucide="phone" class="w-5 h-5"></i></div>
                <h2 class="text-base font-bold">Chat Langsung WhatsApp</h2>
                <p class="text-xs text-emerald-800 leading-relaxed">Respons cepat untuk konfirmasi penjemputan atau tanya harga langsung ke staf outlet.</p>
                <a href="https://wa.me/<?= esc($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 hover:-translate-y-0.5 hover:shadow-lg">
                    <span>Buka WhatsApp</span><i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </article>

            <article class="bg-white p-6 rounded-3xl border border-velvoria-border shadow-sm space-y-3 velvoria-reveal">
                <div class="w-11 h-11 rounded-2xl bg-velvoria-plum/10 text-velvoria-plum flex items-center justify-center"><i data-lucide="clock" class="w-5 h-5"></i></div>
                <h2 class="text-base font-bold text-velvoria-ink">Jam Operasional</h2>
                <p class="text-xs text-velvoria-muted leading-relaxed"><?= esc($settings['operating_hours'] ?? 'Senin - Sabtu: 07:00 - 21:00 WIB | Minggu: 08:00 - 18:00 WIB') ?></p>
            </article>
        </div>

        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-velvoria-border shadow-sm velvoria-reveal">
            <h2 class="text-xl font-bold text-velvoria-ink mb-2">Kirim Pesan</h2>
            <p class="text-xs text-velvoria-muted mb-6">Kami akan membalas pesan Anda melalui email atau WhatsApp dalam 1x24 jam.</p>
            <form action="<?= base_url('kontak') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="<?= old('name') ?>" required class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30" placeholder="Contoh: Rian Anggara">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Alamat Email *</label>
                        <input type="email" name="email" value="<?= old('email') ?>" required class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30" placeholder="nama@email.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="<?= old('phone') ?>" class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30" placeholder="08123456789">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Pesan atau Pertanyaan *</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30" placeholder="Tuliskan pertanyaan atau kebutuhan Anda..."><?= old('message') ?></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-ink hover:-translate-y-0.5 hover:shadow-lg shadow-md">
                    Kirimkan Pesan
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
