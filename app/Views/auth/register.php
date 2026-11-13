<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-velvoria-border shadow-sm">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-velvoria-ink">Daftar Akun Baru</h2>
        <p class="text-xs text-velvoria-muted mt-1">Hanya butuh 1 menit untuk memulai pesanan pertama Anda.</p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0 text-rose-600"></i>
                    <span><?= esc($err) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nama Lengkap *</label>
            <input type="text" name="name" value="<?= old('name') ?>" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="Contoh: Budi Santoso">
        </div>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Alamat Email *</label>
            <input type="email" name="email" value="<?= old('email') ?>" required
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="budi@example.com">
        </div>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nomor WhatsApp / Telepon *</label>
            <input type="text" name="phone" value="<?= old('phone') ?>" required
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="081234567890">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Password *</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                       placeholder="Min. 8 karakter">
            </div>
            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Ulangi Password *</label>
                <input type="password" name="password_confirm" required minlength="8"
                       class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                       placeholder="Ketik ulang password">
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-start space-x-2 text-xs text-velvoria-muted cursor-pointer">
                <input type="checkbox" name="terms" value="1" required class="mt-0.5 rounded border-velvoria-border text-velvoria-plum focus:ring-velvoria-plum">
                <span>Saya menyetujui <a href="<?= base_url('terms') ?>" target="_blank" class="text-velvoria-plum font-semibold hover:underline">Syarat & Ketentuan Layanan</a> Velvoria Laundry.</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 transition-all shadow-md mt-2">
            Buat Akun Sekarang
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-velvoria-border text-center">
        <p class="text-xs text-velvoria-muted">
            Sudah memiliki akun?
            <a href="<?= base_url('login') ?>" class="font-bold text-velvoria-plum hover:underline ml-1">Masuk</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
