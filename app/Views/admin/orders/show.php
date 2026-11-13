<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="<?= base_url('admin/orders') ?>" class="p-2 rounded-xl border border-gray-200 text-gray-500 hover:text-gray-900 bg-white">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold font-mono text-gray-900">#<?= esc($order['order_number']) ?></h1>
                    <?= order_status_badge($order['order_status']) ?>
                    <?= payment_status_badge($order['payment_status']) ?>
                </div>
                <span class="text-xs text-gray-400 mt-0.5 block">Dibuat: <?= format_datetime($order['created_at']) ?></span>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="<?= base_url('admin/orders/' . $order['id'] . '/receipt') ?>" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center space-x-1.5 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 text-velvoria-plum"></i>
                <span>Cetak Nota</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Customer, Items, Weight & Internal Notes (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="user" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Informasi Pelanggan</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block font-semibold text-[10px] uppercase">Nama Pelanggan</span>
                        <span class="text-gray-900 font-bold mt-0.5 block"><?= esc($order['customer_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold text-[10px] uppercase">Telepon / WhatsApp</span>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>" target="_blank" class="text-emerald-600 font-bold mt-0.5 block hover:underline">
                            <?= esc($order['customer_phone']) ?>
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold text-[10px] uppercase">Email</span>
                        <span class="text-gray-700 mt-0.5 block"><?= esc($order['customer_email']) ?></span>
                    </div>
                </div>

                <?php if ($address): ?>
                    <div class="pt-3 border-t border-gray-100 text-xs">
                        <span class="text-gray-400 block font-semibold text-[10px] uppercase">Alamat Antar-Jemput:</span>
                        <p class="text-gray-700 mt-1 leading-relaxed"><?= esc($address['address']) ?>, <?= esc($address['district']) ?>, <?= esc($address['city']) ?></p>
                        <?php if (!empty($address['notes'])): ?>
                            <span class="text-[11px] text-gray-500 mt-1 block italic">Catatan: <?= esc($address['notes']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Items Table Card -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="package" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Rincian Layanan yang Dipesan</span>
                </h3>

                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 uppercase text-[10px]">
                            <th class="pb-2">Layanan</th>
                            <th class="pb-2 text-center">Kuantitas</th>
                            <th class="pb-2 text-right">Tarif</th>
                            <th class="pb-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="py-3 font-medium text-gray-900"><?= esc($item['service_name_snapshot']) ?></td>
                                <td class="py-3 text-center text-gray-600"><?= $item['quantity'] ?> <?= esc($item['unit_snapshot']) ?></td>
                                <td class="py-3 text-right text-gray-600"><?= format_rupiah($item['unit_price']) ?></td>
                                <td class="py-3 text-right font-bold text-gray-900"><?= format_rupiah($item['line_total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pt-3 border-t border-gray-100 space-y-1.5 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal:</span>
                        <span class="text-gray-900 font-medium"><?= format_rupiah($order['subtotal']) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="flex justify-between text-emerald-600">
                            <span>Diskon Voucher (<?= esc($order['promo_code'] ?? '') ?>):</span>
                            <span>-<?= format_rupiah($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Antar-Jemput:</span>
                        <span class="text-gray-900 font-medium"><?= format_rupiah($order['delivery_fee']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total Tagihan:</span>
                        <span class="text-base text-velvoria-plum"><?= format_rupiah($order['grand_total']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Actual Weight Form -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="scale" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Penimbangan Berat Cucian Aktual</span>
                </h3>
                <p class="text-xs text-gray-500">Masukkan berat riil hasil timbangan outlet saat pakaian diterima.</p>

                <form action="<?= base_url('admin/orders/' . $order['id'] . '/weight') ?>" method="POST" class="flex items-center space-x-3">
                    <?= csrf_field() ?>
                    <div class="relative w-48">
                        <input type="number" step="0.1" min="0.1" name="actual_weight" value="<?= old('actual_weight', $order['actual_weight'] ?? '') ?>" required
                               class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum font-bold"
                               placeholder="Contoh: 3.5">
                        <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-semibold">kg</span>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-velvoria-plum text-white font-semibold text-xs hover:bg-velvoria-plum/90 transition-all shadow-sm">
                        Simpan Berat Aktual
                    </button>
                </form>
            </div>

            <!-- Internal Note Form -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Catatan Internal Petugas (Hanya Terlihat Oleh Admin/Staf)</span>
                </h3>
                <form action="<?= base_url('admin/orders/' . $order['id'] . '/note') ?>" method="POST" class="space-y-3">
                    <?= csrf_field() ?>
                    <textarea name="internal_note" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum" placeholder="Tuliskan catatan pengerjaan, noda pakaian, atau nomor rak..."><?= old('internal_note', $order['internal_note'] ?? '') ?></textarea>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gray-800 text-white font-semibold text-xs hover:bg-black transition-all">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Status Transition & Timeline (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Update Status Box -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="git-commit" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Perbarui Status Pengerjaan</span>
                </h3>

                <?php if (empty($nextStatuses)): ?>
                    <div class="p-3.5 rounded-xl bg-gray-50 text-gray-500 text-xs">
                        Pesanan ini berada pada status akhir (<strong><?= order_status_label($order['order_status']) ?></strong>). Tidak ada perubahan status lanjutan.
                    </div>
                <?php else: ?>
                    <form action="<?= base_url('admin/orders/' . $order['id'] . '/status') ?>" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 mb-1">Pilih Status Baru:</label>
                            <select name="status" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs bg-white focus:border-velvoria-plum focus:ring-1 focus:ring-velvoria-plum">
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($nextStatuses as $ns): ?>
                                    <option value="<?= esc($ns) ?>"><?= $statusService->getStatusLabel($ns) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 mb-1">Catatan Perubahan (Opsional):</label>
                            <input type="text" name="note" placeholder="Contoh: Selesai dicuci, masuk pengering rak 2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-velvoria-plum text-white font-bold text-xs hover:bg-velvoria-plum/90 transition-all shadow-sm">
                            Perbarui Status
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Status Timeline History -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center space-x-2">
                    <i data-lucide="clock" class="w-4 h-4 text-velvoria-plum"></i>
                    <span>Riwayat Audit Status</span>
                </h3>

                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    <?php foreach ($histories as $idx => $hist): ?>
                        <div class="relative text-xs">
                            <div class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full <?= $idx === 0 ? 'bg-velvoria-plum ring-4 ring-velvoria-plum/20' : 'bg-gray-300' ?>"></div>
                            <span class="font-bold text-gray-900 block"><?= order_status_label($hist['new_status']) ?></span>
                            <span class="text-[10px] text-gray-400 block mt-0.5"><?= format_datetime($hist['created_at']) ?></span>
                            <?php if (!empty($hist['note'])): ?>
                                <p class="text-[11px] text-gray-600 mt-1 bg-gray-50 p-2 rounded-lg"><?= esc($hist['note']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
