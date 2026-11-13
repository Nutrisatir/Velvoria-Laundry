<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6" x-data="{ modalOpen: false, isEdit: false, id: '', name: '', slug: '', description: '', sort_order: 0 }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kategori Layanan</h1>
            <p class="text-xs text-gray-500 mt-1">Pengelompokan layanan (Kiloan, Satuan, Perawatan Khusus).</p>
        </div>
        <button type="button" @click="isEdit = false; id = ''; name = ''; slug = ''; description = ''; sort_order = 0; modalOpen = true" 
                class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-medium text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center justify-center space-x-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Categories List -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Nama Kategori</th>
                        <th class="py-3.5 px-6">Slug URL</th>
                        <th class="py-3.5 px-6">Deskripsi</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-gray-400"><?= esc($cat['sort_order']) ?></td>
                            <td class="py-3.5 px-6 font-bold text-gray-900"><?= esc($cat['name']) ?></td>
                            <td class="py-3.5 px-6 font-mono text-velvoria-plum"><?= esc($cat['slug']) ?></td>
                            <td class="py-3.5 px-6 text-gray-500 max-w-xs truncate"><?= esc($cat['description'] ?? '-') ?></td>
                            <td class="py-3.5 px-6">
                                <form action="<?= base_url('admin/service-categories/' . $cat['id'] . '/toggle') ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $cat['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= $cat['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <button type="button" @click="isEdit = true; id = '<?= $cat['id'] ?>'; name = '<?= esc($cat['name']) ?>'; slug = '<?= esc($cat['slug']) ?>'; description = '<?= esc($cat['description'] ?? '') ?>'; sort_order = <?= $cat['sort_order'] ?>; modalOpen = true"
                                        class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-velvoria-plum hover:text-white font-semibold text-[11px] transition-colors">
                                    Ubah
                                </button>
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
            <h3 class="text-lg font-bold text-gray-900" x-text="isEdit ? 'Ubah Kategori' : 'Tambah Kategori Baru'"></h3>
            
            <form :action="isEdit ? ('<?= base_url('admin/service-categories') ?>/' + id) : '<?= base_url('admin/service-categories') ?>'" method="POST" class="space-y-3 text-xs">
                <?= csrf_field() ?>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" x-model="name" required class="w-full px-3 py-2 rounded-xl border border-gray-200">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Slug URL</label>
                    <input type="text" name="slug" x-model="slug" class="w-full px-3 py-2 rounded-xl border border-gray-200 font-mono" placeholder="Biarkan kosong untuk otomatis">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-200"></textarea>
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nomor Urut</label>
                    <input type="number" name="sort_order" x-model="sort_order" class="w-full px-3 py-2 rounded-xl border border-gray-200">
                </div>
                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-velvoria-plum text-white font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
