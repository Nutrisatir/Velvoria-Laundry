<?= $this->extend('layouts/customer') ?>
<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <p class="text-xs uppercase tracking-widest text-velvoria-muted">Pusat bantuan</p>
    <h1 class="text-2xl font-bold text-velvoria-ink mt-1">Bantuan Pelanggan</h1>
    <p class="text-sm text-velvoria-muted mt-2">Informasi singkat mengenai pemesanan dan layanan Velvoria Laundry.</p>
    <div class="grid gap-4 mt-7">
        <?php foreach ([['Bagaimana cara memesan?', 'Pilih layanan, masukkan jumlah atau berat perkiraan, pilih metode penyerahan, lalu konfirmasi pesanan.'], ['Kapan pesanan mulai diproses?', 'Pesanan mulai diproses setelah diterima outlet dan pembayaran terverifikasi sesuai ketentuan layanan.'], ['Bagaimana memantau cucian?', 'Buka menu Pesanan Saya atau Riwayat Pesanan untuk melihat status terbaru.'], ['Bagaimana jika berat aktual berbeda?', 'Petugas akan mencatat berat aktual. Perubahan biaya harus diinformasikan dan disetujui sesuai kebijakan toko.']] as [$title, $body]): ?>
        <section class="rounded-2xl border border-velvoria-border bg-white p-5"><h2 class="font-semibold text-velvoria-ink"><?= esc($title) ?></h2><p class="text-sm text-velvoria-muted mt-2"><?= esc($body) ?></p></section>
        <?php endforeach ?>
    </div>
    <?php $wa = $settings['store_whatsapp'] ?? ''; ?>
    <?php if ($wa): ?><a target="_blank" rel="noopener" href="https://wa.me/<?= esc(preg_replace('/\D+/', '', $wa)) ?>" class="inline-flex mt-6 px-5 py-3 rounded-xl bg-velvoria-plum text-white text-sm font-semibold">Hubungi WhatsApp</a><?php endif ?>
</div>
<?= $this->endSection() ?>
