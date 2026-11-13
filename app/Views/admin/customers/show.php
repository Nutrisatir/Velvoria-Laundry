<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('admin/customers') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-900 bg-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Profil Pelanggan: <?= esc($customer['name']) ?></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Customer Info & Status Toggle Card (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-6">
            <div class="text-center pb-6 border-b border-gray-100">
                <div class="w-20 h-20 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold text-3xl flex items-center justify-center mx-auto mb-3">
                    <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                </div>
                <h3 class="text-base font-bold text-gray-900"><?= esc($customer['name']) ?></h3>
                <span class="text-xs text-gray-400 block"><?= esc($customer['email']) ?></span>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-bold uppercase <?= $customer['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                    Status: <?= esc($customer['status']) ?>
                </span>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-gray-400 block font-semibold text-[10px] uppercase">Nomor Telepon</span>
                    <span class="text-gray-900 font-bold"><?= esc($customer['phone'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold text-[10px] uppercase">Total Belanja Lunas</span>
                    <span class="text-base font-bold text-velvoria-plum"><?= format_rupiah($totalSpent) ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold text-[10px] uppercase">Tanggal Terdaftar</span>
                    <span class="text-gray-600"><?= format_datetime($customer['created_at']) ?></span>
                </div>
            </div>

            <!-- Block/Unblock Form -->
            <div class="pt-4 border-t border-gray-100">
                <form action="<?= base_url('admin/customers/' . $customer['id'] . '/toggle') ?>" method="POST" class="space-y-3 text-xs" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status akun pelanggan ini?')">
                    <?= csrf_field() ?>
                    <input type="text" name="reason" placeholder="Alasan perubahan status..." required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs">
                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold <?= $customer['status'] === 'active' ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' ?>">
                        <?= $customer['status'] === 'active' ? 'Blokir / Nonaktifkan Akun' : 'Aktifkan Kembali Akun' ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Order History (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Riwayat Seluruh Pesanan (<?= count($orders) ?>)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 uppercase text-[10px]">
                            <th class="py-3 px-6">No. Pesanan</th>
                            <th class="py-3 px-6">Tanggal</th>
                            <th class="py-3 px-6">Total Tagihan</th>
                            <th class="py-3 px-6">Status Cucian</th>
                            <th class="py-3 px-6">Pembayaran</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="6" class="py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $o): ?>
                                <tr class="hover:bg-gray-50/70">
                                    <td class="py-3.5 px-6 font-mono font-bold text-velvoria-plum">#<?= esc($o['order_number']) ?></td>
                                    <td class="py-3.5 px-6 text-gray-500"><?= format_datetime($o['created_at']) ?></td>
                                    <td class="py-3.5 px-6 font-bold text-gray-900"><?= format_rupiah($o['grand_total']) ?></td>
                                    <td class="py-3.5 px-6"><?= order_status_badge($o['order_status']) ?></td>
                                    <td class="py-3.5 px-6"><?= payment_status_badge($o['payment_status']) ?></td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="<?= base_url('admin/orders/' . $o['id']) ?>" class="text-xs font-bold text-velvoria-plum hover:underline">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
