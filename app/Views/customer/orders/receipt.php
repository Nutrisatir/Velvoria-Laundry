<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #<?= esc($order['order_number']) ?> - Velvoria Laundry</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'velvoria-ink': '#282331',
                        'velvoria-plum': '#51415F',
                        'velvoria-border': '#E8E3EA',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background-color: #F8F9FA; color: #282331; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .print-shadow-none { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <div class="max-w-2xl mx-auto bg-white p-8 sm:p-12 rounded-2xl border border-velvoria-border shadow-sm print-shadow-none">
        <!-- Print Trigger Bar -->
        <div class="no-print mb-8 pb-4 border-b border-gray-200 flex items-center justify-between">
            <span class="text-xs text-gray-500">Pratinjau Nota Resmi Velvoria Laundry</span>
            <div class="flex space-x-3">
                <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-velvoria-plum text-white font-bold text-xs shadow hover:bg-velvoria-plum/90">
                    Cetak / Simpan PDF
                </button>
                <button onclick="window.close()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-600 hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Receipt Header -->
        <div class="flex items-start justify-between pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-velvoria-ink">VELVORIA LAUNDRY</h1>
                <p class="text-xs text-gray-500 mt-1"><?= esc($settings['store_tagline'] ?? 'Baju Bersih, Waktu Lebih Luang.') ?></p>
                <p class="text-xs text-gray-500 mt-0.5"><?= esc($settings['store_address'] ?? 'Jakarta Selatan') ?></p>
                <p class="text-xs text-gray-500">WA: <?= esc($settings['store_whatsapp'] ?? '081234567890') ?></p>
            </div>

            <div class="text-right">
                <span class="text-xs uppercase tracking-wider text-gray-400 block font-semibold">Bukti Transaksi</span>
                <span class="text-base font-mono font-bold text-velvoria-plum block mt-0.5">#<?= esc($order['order_number']) ?></span>
                <span class="text-xs text-gray-500 block mt-1"><?= format_datetime($order['created_at']) ?></span>
                <span class="inline-block mt-2 px-2.5 py-0.5 rounded text-[11px] font-bold uppercase <?= $order['payment_status'] === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                    <?= $order['payment_status'] === 'paid' ? 'LUNAS' : 'BELUM DIBAYAR' ?>
                </span>
            </div>
        </div>

        <!-- Customer & Order Meta -->
        <div class="py-6 grid grid-cols-2 gap-4 text-xs border-b border-gray-200">
            <div>
                <span class="text-gray-400 block font-semibold uppercase text-[10px]">Pelanggan:</span>
                <span class="font-bold text-gray-900 block mt-0.5"><?= esc($user['name'] ?? '-') ?></span>
                <span class="text-gray-600 block"><?= esc($user['phone'] ?? '-') ?></span>
                <span class="text-gray-600 block"><?= esc($user['email'] ?? '-') ?></span>
            </div>
            <div>
                <span class="text-gray-400 block font-semibold uppercase text-[10px]">Penyerahan Cucian:</span>
                <span class="font-bold text-gray-900 block mt-0.5">
                    <?= $order['handover_method'] === 'drop_off' ? 'Antar Sendiri ke Outlet' : 'Antar - Jemput Kurir' ?>
                </span>
                <?php if ($address): ?>
                    <p class="text-gray-600 mt-0.5 leading-relaxed"><?= esc($address['address']) ?>, <?= esc($address['district']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6 border-b border-gray-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 uppercase text-[10px]">
                        <th class="pb-2">Layanan</th>
                        <th class="pb-2 text-center">Qty</th>
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
        </div>

        <!-- Totals Calculation -->
        <div class="py-6 space-y-2 text-xs border-b border-gray-200">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal Cucian:</span>
                <span class="font-medium text-gray-900"><?= format_rupiah($order['subtotal']) ?></span>
            </div>
            <?php if ($order['discount_amount'] > 0): ?>
                <div class="flex justify-between text-emerald-600">
                    <span>Diskon (<?= esc($order['promo_code'] ?? '') ?>):</span>
                    <span>-<?= format_rupiah($order['discount_amount']) ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between text-gray-600">
                <span>Biaya Antar-Jemput:</span>
                <span class="font-medium text-gray-900"><?= $order['delivery_fee'] == 0 ? 'GRATIS' : format_rupiah($order['delivery_fee']) ?></span>
            </div>
            <div class="flex justify-between text-sm font-bold text-gray-900 pt-3 border-t border-gray-200">
                <span>Total Tagihan:</span>
                <span class="text-base text-velvoria-plum"><?= format_rupiah($order['grand_total']) ?></span>
            </div>
        </div>

        <!-- Footer / Policy Notice -->
        <div class="pt-6 text-center text-[10px] text-gray-400 space-y-1">
            <p>Terima kasih atas kepercayaan Anda mencuci di Velvoria Laundry.</p>
            <p>Harap periksa pakaian Anda saat serah terima. Komplain dilayani maksimal 1x24 jam setelah cucian diterima.</p>
        </div>
    </div>

</body>
</html>
