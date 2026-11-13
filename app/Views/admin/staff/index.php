<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6" x-data="{ modalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Staf & Akses Sistem</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar pengguna dengan hak akses operasional (Admin & Staf Kasir).</p>
        </div>
        <button type="button" @click="modalOpen = true" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Akun Staf</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Nama Pengguna</th>
                        <th class="py-3.5 px-6">Email Login</th>
                        <th class="py-3.5 px-6">Telepon</th>
                        <th class="py-3.5 px-6">Peran (Role)</th>
                        <th class="py-3.5 px-6">Status Akun</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($staff as $s): ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-gray-900"><?= esc($s['name']) ?></td>
                            <td class="py-3.5 px-6 text-gray-600 font-mono text-[11px]"><?= esc($s['email']) ?></td>
                            <td class="py-3.5 px-6 text-gray-600"><?= esc($s['phone'] ?? '-') ?></td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $s['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= esc($s['role']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $s['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                    <?= esc($s['status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <?php if ($s['id'] != session()->get('user_id')): ?>
                                    <form action="<?= base_url('admin/staff/' . $s['id'] . '/toggle') ?>" method="POST" class="inline" onsubmit="return confirm('Ubah status akun ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                            <?= $s['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400 italic text-[11px]">Akun Anda</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" style="display: none;">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full space-y-4" @click.away="modalOpen = false">
            <h3 class="text-lg font-bold text-gray-900">Tambah Akun Staf Baru</h3>
            <form action="<?= base_url('admin/staff') ?>" method="POST" class="space-y-3 text-xs">
                <?= csrf_field() ?>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-xl border border-gray-200" placeholder="Nama staf...">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 rounded-xl border border-gray-200" placeholder="staf@velvoria.test">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nomor Telepon *</label>
                    <input type="text" name="phone" required class="w-full px-3 py-2 rounded-xl border border-gray-200" placeholder="08123456789">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Peran (Role) *</label>
                    <select name="role" required class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white">
                        <option value="staff">Staf Operasional</option>
                        <option value="admin">Administrator Penuh</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Password Awal *</label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 rounded-xl border border-gray-200" placeholder="Min. 8 karakter">
                </div>
                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-velvoria-plum text-white font-bold">Simpan Staf</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
