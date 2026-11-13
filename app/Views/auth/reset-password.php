<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-velvoria-border shadow-sm">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-velvoria-ink">Atur Password Baru</h2>
        <p class="text-xs text-velvoria-muted mt-1">Masukkan password baru untuk akun Anda.</p>
    </div>

    <form action="<?= base_url('reset-password') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>

        <input type="hidden" name="token" value="<?= esc($token ?? old('token')) ?>">

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Password Baru *</label>
            <input type="password" name="password" required minlength="8"
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="Min. 8 karakter">
        </div>

        <div>
            <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Konfirmasi Password Baru *</label>
            <input type="password" name="password_confirm" required minlength="8"
                   class="w-full px-4 py-3 rounded-xl border border-velvoria-border focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum text-xs sm:text-sm bg-velvoria-cream/30"
                   placeholder="Ketik ulang password">
        </div>

        <button type="submit" class="w-full py-3.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 transition-all shadow-md mt-2">
            Simpan Password Baru
        </button>
    </form>
</div>

<?= $this->endSection() ?>
