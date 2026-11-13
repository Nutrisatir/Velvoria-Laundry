<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-velvoria-border shadow-sm">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-velvoria-ink">Selamat Datang Kembali</h2>
        <p class="text-xs text-velvoria-muted mt-1">Masuk ke akun Velvoria Anda untuk mengelola pesanan.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-600"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Email atau Nomor Telepon</label>
            <input type="text" name="login" value="<?= old('login') ?>" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="Contoh: user@email.com atau 08123456789">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-velvoria-ink">Password</label>
                <a href="<?= base_url('forgot-password') ?>" class="text-[11px] font-medium text-velvoria-plum hover:underline">Lupa Password?</a>
            </div>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="••••••••">
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 transition-all shadow-md mt-2">
            Masuk Sekarang
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-velvoria-border text-center">
        <p class="text-xs text-velvoria-muted">
            Belum memiliki akun?
            <a href="<?= base_url('register') ?>" class="font-bold text-velvoria-plum hover:underline ml-1">Daftar Sekarang</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
