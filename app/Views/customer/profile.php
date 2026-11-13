<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto space-y-8">
    <div class="velvoria-reveal">
        <h1 class="text-2xl font-bold text-velvoria-ink">Profil Saya</h1>
        <p class="text-xs text-velvoria-muted mt-1">Perbarui informasi kontak, foto, dan keamanan kata sandi akun Anda.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-velvoria-border shadow-sm space-y-6 velvoria-reveal">
        <div class="flex items-center space-x-4 pb-6 border-b border-velvoria-border">
            <div class="w-20 h-20 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold text-2xl flex items-center justify-center shrink-0 overflow-hidden ring-4 ring-velvoria-plum/5">
                <img id="avatar-preview"
                     src="<?= ! empty($user['avatar']) ? base_url('uploads/avatars/' . rawurlencode(basename($user['avatar']))) : '' ?>"
                     alt="Foto profil"
                     class="w-full h-full object-cover <?= empty($user['avatar']) ? 'hidden' : '' ?>">
                <span id="avatar-fallback" class="<?= ! empty($user['avatar']) ? 'hidden' : '' ?>"><?= esc(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?></span>
            </div>
            <div class="min-w-0">
                <h3 class="text-base font-bold text-velvoria-ink"><?= esc($user['name']) ?></h3>
                <span class="text-xs text-velvoria-muted block mt-0.5"><?= esc($user['email']) ?></span>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">
                    Akun <?= esc($user['status']) ?>
                </span>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <?php foreach ((array) session()->getFlashdata('errors') as $error): ?><p><?= esc($error) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('customer/profile') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="avatar" class="block text-xs font-semibold text-velvoria-ink mb-1.5">Foto Profil</label>
                <div class="flex flex-wrap items-center gap-3">
                    <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-velvoria-border text-xs font-semibold text-velvoria-plum cursor-pointer hover:bg-velvoria-cream hover:border-velvoria-plum/40">
                        <i data-lucide="image-plus" class="w-4 h-4"></i>
                        Pilih Foto
                    </label>
                    <span id="avatar-filename" class="text-xs text-velvoria-muted">JPG, PNG, WEBP. Maksimal 2 MB.</span>
                    <input id="avatar" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" value="<?= esc(old('name', $user['name'])) ?>" required
                       class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Alamat Email</label>
                    <input type="email" value="<?= esc($user['email']) ?>" disabled
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Nomor Telepon / WhatsApp *</label>
                    <input type="text" name="phone" value="<?= esc(old('phone', $user['phone'])) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs hover:bg-velvoria-ink hover:-translate-y-0.5 hover:shadow-lg shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-velvoria-border shadow-sm space-y-6 velvoria-reveal">
        <div>
            <h3 class="text-base font-bold text-velvoria-ink">Keamanan Kata Sandi</h3>
            <p class="text-xs text-velvoria-muted mt-1">Gunakan password yang kuat dengan minimal 8 karakter.</p>
        </div>

        <?php if (session()->getFlashdata('password_success')): ?>
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs"><?= esc(session()->getFlashdata('password_success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('password_error')): ?>
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs"><?= esc(session()->getFlashdata('password_error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('customer/profile/password') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Password Saat Ini *</label>
                <input type="password" name="current_password" required autocomplete="current-password"
                       class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Password Baru *</label>
                    <input type="password" name="new_password" required minlength="8" autocomplete="new-password"
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum"
                           placeholder="Min. 8 karakter">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Ulangi Password Baru *</label>
                    <input type="password" name="confirm_password" required minlength="8" autocomplete="new-password"
                           class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30 focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-velvoria-ink text-white font-semibold text-xs hover:bg-black hover:-translate-y-0.5 hover:shadow-lg shadow-sm">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    const fallback = document.getElementById('avatar-fallback');
    const filename = document.getElementById('avatar-filename');
    if (!input) return;

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) return;

        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type) || file.size > 2 * 1024 * 1024) {
            this.value = '';
            filename.textContent = 'File tidak valid. Gunakan JPG, PNG, WEBP maksimal 2 MB.';
            filename.classList.add('text-rose-600');
            return;
        }

        filename.textContent = file.name;
        filename.classList.remove('text-rose-600');
        const reader = new FileReader();
        reader.onload = event => {
            preview.src = event.target.result;
            preview.classList.remove('hidden');
            fallback.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
