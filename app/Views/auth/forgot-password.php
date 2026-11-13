<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-velvoria-border shadow-sm">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-velvoria-ink">Lupa Password</h2>
        <p class="text-xs text-velvoria-muted mt-1">Masukkan email akun Anda untuk mendapatkan tautan pembaruan password.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('forgot-password') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Alamat Email Terdaftar</label>
            <input type="email" name="email" value="<?= old('email') ?>" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="nama@email.com">
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 transition-all shadow-md">
            Kirim Tautan Reset
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-velvoria-border text-center">
        <a href="<?= base_url('login') ?>" class="text-xs font-semibold text-velvoria-plum hover:underline inline-flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Halaman Masuk</span>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
