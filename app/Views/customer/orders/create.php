<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8" 
     x-data="{
        step: 1,
        submitting: false,
        toast: { show: false, message: '', type: 'info' },
        toastTimer: null,
        notify(message, type = 'info') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => this.toast.show = false, 3600);
        },
        handoverMethod: 'drop_off',
        addressId: '<?= !empty($addresses[0]) ? $addresses[0]['id'] : '' ?>',
        pickupSchedule: '',
        deliverySchedule: '',
        customerNote: '',
        promoCode: '',
        discountAmount: 0,
        estimatedWeight: 2,
        
        // Items list: { service_id, name, unit, price, quantity, min_qty, is_kiloan }
        items: [
            <?php foreach ($services as $s): ?>
            <?php
                $serviceSlug = strtolower(trim((string) ($s['category_slug'] ?? '')));
                $serviceName = strtolower((string) ($s['category_name'] ?? ''));
                if (in_array($serviceSlug, ['kiloan', 'laundry-kiloan', 'layanan-kiloan'], true) || str_contains($serviceName, 'kilo')) {
                    $serviceCategory = 'kiloan';
                } elseif (in_array($serviceSlug, ['satuan', 'laundry-satuan', 'layanan-satuan'], true) || str_contains($serviceName, 'satuan')) {
                    $serviceCategory = 'satuan';
                } else {
                    $serviceCategory = (($s['unit'] ?? '') === 'kg') ? 'kiloan' : 'satuan';
                }
            ?>
            {
                service_id: <?= (int) $s['id'] ?>,
                code: <?= htmlspecialchars(json_encode((string) $s['code'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>,
                name: <?= htmlspecialchars(json_encode((string) $s['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>,
                unit: <?= htmlspecialchars(json_encode((string) $s['unit'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>,
                price: <?= (int) $s['price'] ?>,
                quantity: 0,
                min_qty: <?= (float) $s['minimum_quantity'] ?>,
                is_kiloan: <?= $s['unit'] === 'kg' ? 'true' : 'false' ?>,
                category: <?= htmlspecialchars(json_encode($serviceCategory, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            },
            <?php endforeach; ?>
        ],

        selectedTab: 'kiloan',
        deliveryFeeDefault: <?= (int)($settings['delivery_fee'] ?? 5000) ?>,
        freeDeliveryThreshold: <?= (int)($settings['free_delivery_threshold'] ?? 50000) ?>,

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get deliveryFee() {
            if (this.handoverMethod === 'drop_off') return 0;
            if (this.subtotal >= this.freeDeliveryThreshold && this.freeDeliveryThreshold > 0) return 0;
            return this.deliveryFeeDefault;
        },

        get grandTotal() {
            let total = this.subtotal - this.discountAmount + this.deliveryFee;
            return total < 0 ? 0 : total;
        },

        get selectedItems() {
            return this.items.filter(i => i.quantity > 0);
        },

        formatRupiah(num) {
            return 'Rp' + Number(num).toLocaleString('id-ID');
        },

        increaseQty(item) {
            if (item.is_kiloan) {
                item.quantity = Number((item.quantity + 0.5).toFixed(1));
            } else {
                item.quantity += 1;
            }
        },

        decreaseQty(item) {
            if (item.quantity <= 0) return;
            if (item.is_kiloan) {
                let next = Number((item.quantity - 0.5).toFixed(1));
                item.quantity = next < 0 ? 0 : next;
            } else {
                item.quantity -= 1;
            }
        },

        applyPromo() {
            let code = this.promoCode.trim().toUpperCase();
            if (!code) {
                this.discountAmount = 0;
                return;
            }
            // Demo promo auto calculation: VELVORIA10 = 10%
            if (code === 'VELVORIA10') {
                if (this.subtotal < 20000) {
                    this.notify('Minimal transaksi untuk voucher VELVORIA10 adalah Rp20.000.', 'warning');
                    return;
                }
                let disc = Math.round(this.subtotal * 0.10);
                this.discountAmount = Math.min(disc, 15000);
                this.notify('Voucher berhasil diterapkan. Potongan ' + this.formatRupiah(this.discountAmount) + '.', 'success');
            } else {
                this.discountAmount = 0;
                this.notify('Kode voucher tidak ditemukan atau sudah kedaluwarsa.', 'error');
            }
        },

        validateStep1() {
            if (this.selectedItems.length === 0) {
                this.notify('Pilih minimal satu layanan laundry untuk melanjutkan.', 'warning');
                return false;
            }
            const invalidItem = this.selectedItems.find(item => item.quantity < item.min_qty);
            if (invalidItem) {
                this.notify('Jumlah minimal ' + invalidItem.name + ' adalah ' + invalidItem.min_qty + ' ' + invalidItem.unit + '.', 'warning');
                return false;
            }
            // Check kiloan min weight if kiloan selected
            let kiloanTotal = this.items.filter(i => i.is_kiloan).reduce((sum, i) => sum + i.quantity, 0);
            if (kiloanTotal > 0 && kiloanTotal < 2) {
                this.notify('Pesanan kiloan minimal 2 kg. Jumlah saat ini ' + kiloanTotal + ' kg.', 'warning');
                return false;
            }
            this.step = 2;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        validateStep2() {
            if (this.handoverMethod === 'pickup_delivery' && !this.addressId) {
                this.notify('Silakan pilih alamat penjemputan terlebih dahulu.', 'warning');
                return false;
            }
            this.step = 3;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
     }">

    <!-- Elegant in-app notification, replaces browser localhost alerts -->
    <div class="fixed top-5 right-5 z-[100] w-[min(92vw,390px)] pointer-events-none" aria-live="polite" aria-atomic="true">
        <div x-cloak x-show="toast.show" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="pointer-events-auto flex items-start gap-3 rounded-2xl border bg-white/95 p-4 shadow-2xl backdrop-blur"
             :class="toast.type === 'success' ? 'border-emerald-200' : (toast.type === 'error' ? 'border-rose-200' : 'border-amber-200')">
            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                  :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-600' : (toast.type === 'error' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600')">
                <i x-show="toast.type === 'success'" data-lucide="check-circle-2" class="h-5 w-5"></i>
                <i x-show="toast.type === 'error'" data-lucide="x-circle" class="h-5 w-5"></i>
                <i x-show="toast.type !== 'success' && toast.type !== 'error'" data-lucide="info" class="h-5 w-5"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-velvoria-ink" x-text="toast.type === 'success' ? 'Berhasil' : (toast.type === 'error' ? 'Ada kendala' : 'Perhatian')"></p>
                <p class="mt-1 text-xs leading-relaxed text-velvoria-muted" x-text="toast.message"></p>
            </div>
            <button type="button" @click="toast.show = false" class="rounded-lg p-1 text-velvoria-muted transition hover:bg-velvoria-cream hover:text-velvoria-ink" aria-label="Tutup notifikasi">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    </div>

    <!-- Page Title -->
    <div>
        <h1 class="text-2xl font-bold text-velvoria-ink">Buat Pesanan Baru</h1>
        <p class="text-xs text-velvoria-muted mt-1">Lengkapi rincian cucian dan jadwal penjemputan Anda.</p>
    </div>

    <!-- Step Progress Indicator -->
    <div class="bg-white p-4 sm:p-6 rounded-3xl border border-velvoria-border shadow-sm">
        <div class="flex items-center justify-between max-w-xl mx-auto">
            <!-- Step 1 -->
            <div class="flex flex-col items-center space-y-1">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                     :class="step >= 1 ? 'bg-velvoria-plum text-white shadow-sm' : 'bg-gray-100 text-gray-400'">
                    1
                </div>
                <span class="text-[11px] font-semibold" :class="step >= 1 ? 'text-velvoria-ink' : 'text-velvoria-muted'">Layanan</span>
            </div>

            <div class="flex-grow h-0.5 mx-3" :class="step >= 2 ? 'bg-velvoria-plum' : 'bg-gray-200'"></div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center space-y-1">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                     :class="step >= 2 ? 'bg-velvoria-plum text-white shadow-sm' : 'bg-gray-100 text-gray-400'">
                    2
                </div>
                <span class="text-[11px] font-semibold" :class="step >= 2 ? 'text-velvoria-ink' : 'text-velvoria-muted'">Penyerahan</span>
            </div>

            <div class="flex-grow h-0.5 mx-3" :class="step >= 3 ? 'bg-velvoria-plum' : 'bg-gray-200'"></div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center space-y-1">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                     :class="step >= 3 ? 'bg-velvoria-plum text-white shadow-sm' : 'bg-gray-100 text-gray-400'">
                    3
                </div>
                <span class="text-[11px] font-semibold" :class="step >= 3 ? 'text-velvoria-ink' : 'text-velvoria-muted'">Konfirmasi</span>
            </div>
        </div>
    </div>

    <!-- Form container -->
    <form action="<?= base_url('customer/orders') ?>" method="POST" id="orderForm">
        <?= csrf_field() ?>
        
        <!-- Hidden input storing selected items JSON -->
        <input type="hidden" name="items" :value="JSON.stringify(selectedItems)">
        <input type="hidden" name="handover_method" :value="handoverMethod">
        <input type="hidden" name="address_id" :value="addressId">
        <input type="hidden" name="pickup_schedule" :value="pickupSchedule">
        <input type="hidden" name="delivery_schedule" :value="deliverySchedule">
        <input type="hidden" name="customer_note" :value="customerNote">
        <input type="hidden" name="promo_code" :value="promoCode">
        <input type="hidden" name="estimated_weight" :value="estimatedWeight">

        <!-- STEP 1: Pilih Layanan -->
        <div x-show="step === 1" class="space-y-6">
            <!-- Tabs -->
            <div class="flex space-x-2 border-b border-velvoria-border pb-2">
                <button type="button" @click="selectedTab = 'kiloan'"
                        :class="selectedTab === 'kiloan' ? 'bg-velvoria-plum text-white font-bold' : 'bg-white text-velvoria-muted border border-velvoria-border'"
                        class="px-4 py-2 rounded-xl text-xs transition-all duration-300 ease-out hover:-translate-y-0.5 active:scale-[0.98]">
                    Laundry Kiloan
                </button>
                <button type="button" @click="selectedTab = 'satuan'"
                        :class="selectedTab === 'satuan' ? 'bg-velvoria-plum text-white font-bold' : 'bg-white text-velvoria-muted border border-velvoria-border'"
                        class="px-4 py-2 rounded-xl text-xs transition-all duration-300 ease-out hover:-translate-y-0.5 active:scale-[0.98]">
                    Laundry Satuan (Item)
                </button>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <template x-if="items.filter(i => i.category === selectedTab).length === 0">
                    <div class="sm:col-span-2 rounded-2xl border border-dashed border-velvoria-border bg-white p-8 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-velvoria-cream text-velvoria-plum"><i data-lucide="washing-machine" class="h-6 w-6"></i></div>
                        <p class="text-sm font-bold text-velvoria-ink">Layanan belum tersedia</p>
                        <p class="mt-1 text-xs text-velvoria-muted">Belum ada data layanan aktif pada database. Jalankan <strong>php spark db:seed DatabaseSeeder</strong> dari terminal proyek, lalu muat ulang halaman ini.</p>
                    </div>
                </template>
                <template x-for="item in items.filter(i => i.category === selectedTab)" :key="item.service_id">
                    <div class="bg-white p-5 rounded-2xl border transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between"
                         :class="item.quantity > 0 ? 'border-velvoria-plum shadow-sm bg-velvoria-cream/20' : 'border-velvoria-border'">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-xs font-bold text-velvoria-plum bg-velvoria-cream px-2 py-0.5 rounded border border-velvoria-border" x-text="item.code"></span>
                                <span class="text-xs font-bold text-velvoria-ink" x-text="formatRupiah(item.price) + ' / ' + item.unit"></span>
                            </div>
                            <h4 class="text-sm font-bold text-velvoria-ink" x-text="item.name"></h4>
                            <p class="text-[11px] text-velvoria-muted mt-1" x-text="'Min. ' + item.min_qty + ' ' + item.unit"></p>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="mt-4 pt-4 border-t border-velvoria-border/60 flex items-center justify-between">
                            <span class="text-xs text-velvoria-muted font-medium">Kuantitas:</span>
                            <div class="flex items-center space-x-2">
                                <button type="button" @click="decreaseQty(item)" 
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 active:scale-90">
                                    -
                                </button>
                                <span class="w-12 text-center text-xs font-bold text-velvoria-ink" x-text="item.quantity + ' ' + item.unit"></span>
                                <button type="button" @click="increaseQty(item)" 
                                        class="w-7 h-7 rounded-lg bg-velvoria-plum hover:bg-velvoria-plum/90 text-white flex items-center justify-center font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 active:scale-90">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Step 1 Summary Footer -->
            <div class="bg-white p-5 rounded-2xl border border-velvoria-border shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs text-velvoria-muted block">Estimasi Subtotal:</span>
                    <span class="text-xl font-bold text-velvoria-plum" x-text="formatRupiah(subtotal)"></span>
                </div>
                <button type="button" @click="validateStep1()" class="px-6 py-3 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 transition-all flex items-center space-x-2 shadow-sm">
                    <span>Lanjut ke Penyerahan</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Penyerahan & Alamat -->
        <div x-show="step === 2" class="space-y-6" style="display: none;">
            <div class="bg-white p-6 rounded-3xl border border-velvoria-border shadow-sm space-y-6">
                <h3 class="text-base font-bold text-velvoria-ink">Metode Penyerahan Cucian</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Drop off -->
                    <label class="p-5 rounded-2xl border cursor-pointer transition-all flex items-start space-x-3"
                           :class="handoverMethod === 'drop_off' ? 'border-velvoria-plum bg-velvoria-cream/30' : 'border-velvoria-border'">
                        <input type="radio" name="method_radio" value="drop_off" x-model="handoverMethod" class="mt-1 text-velvoria-plum focus:ring-velvoria-plum">
                        <div>
                            <span class="block text-sm font-bold text-velvoria-ink">Antar Langsung ke Outlet</span>
                            <span class="block text-xs text-velvoria-muted mt-1 leading-relaxed">
                                Anda mengantarkan pakaian ke outlet Velvoria dan mengambilnya kembali saat sudah selesai.
                            </span>
                            <span class="inline-block mt-2 px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Biaya Rp0</span>
                        </div>
                    </label>

                    <!-- Pickup delivery -->
                    <label class="p-5 rounded-2xl border cursor-pointer transition-all flex items-start space-x-3"
                           :class="handoverMethod === 'pickup_delivery' ? 'border-velvoria-plum bg-velvoria-cream/30' : 'border-velvoria-border'">
                        <input type="radio" name="method_radio" value="pickup_delivery" x-model="handoverMethod" class="mt-1 text-velvoria-plum focus:ring-velvoria-plum">
                        <div>
                            <span class="block text-sm font-bold text-velvoria-ink">Antar - Jemput Kurir</span>
                            <span class="block text-xs text-velvoria-muted mt-1 leading-relaxed">
                                Kurir Velvoria akan datang menjemput pakaian kotor dan mengantarkan kembali pakaian bersih.
                            </span>
                            <span class="inline-block mt-2 px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-bold">
                                <?= format_rupiah((int)($settings['delivery_fee'] ?? 5000)) ?> (Gratis di atas Rp50k)
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Address selection (only if pickup_delivery) -->
                <div x-show="handoverMethod === 'pickup_delivery'" class="pt-4 border-t border-velvoria-border space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-velvoria-ink">Pilih Alamat Penjemputan:</label>
                        <a href="<?= base_url('customer/addresses/create') ?>" target="_blank" class="text-xs font-semibold text-velvoria-plum hover:underline">
                            + Tambah Alamat Baru
                        </a>
                    </div>

                    <?php if (empty($addresses)): ?>
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                            Anda belum memiliki alamat tersimpan. Silakan <a href="<?= base_url('customer/addresses/create') ?>" target="_blank" class="font-bold underline">tambah alamat penjemputan</a> terlebih dahulu.
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($addresses as $addr): ?>
                                <label class="p-4 rounded-xl border flex items-start space-x-3 cursor-pointer"
                                       :class="addressId == <?= $addr['id'] ?> ? 'border-velvoria-plum bg-velvoria-cream/30' : 'border-velvoria-border'">
                                    <input type="radio" name="address_radio" value="<?= $addr['id'] ?>" x-model="addressId" class="mt-1 text-velvoria-plum focus:ring-velvoria-plum">
                                    <div class="text-xs leading-relaxed">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-velvoria-ink"><?= esc($addr['label']) ?></span>
                                            <span class="text-velvoria-muted">&bull; <?= esc($addr['recipient_name']) ?> (<?= esc($addr['phone']) ?>)</span>
                                        </div>
                                        <p class="text-velvoria-muted mt-1"><?= esc($addr['address']) ?>, <?= esc($addr['district']) ?>, <?= esc($addr['city']) ?></p>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-velvoria-ink mb-1">Jadwal Penjemputan</label>
                            <input type="datetime-local" x-model="pickupSchedule" class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-velvoria-ink mb-1">Catatan Khusus untuk Kurir/Staf</label>
                            <input type="text" x-model="customerNote" placeholder="Contoh: Baju putih mohon dipisah, noda kopi di kemeja biru" class="w-full px-4 py-2.5 rounded-xl border border-velvoria-border text-xs bg-velvoria-cream/30">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Navigation -->
            <div class="flex items-center justify-between">
                <button type="button" @click="step = 1; window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-5 py-2.5 rounded-xl border border-velvoria-border text-xs font-semibold text-velvoria-ink hover:bg-white">
                    &larr; Kembali
                </button>
                <button type="button" @click="validateStep2()" class="px-6 py-3 rounded-xl bg-velvoria-plum text-white font-semibold text-xs sm:text-sm hover:bg-velvoria-plum/90 shadow-sm flex items-center space-x-2">
                    <span>Lanjut ke Ringkasan</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Konfirmasi & Voucher -->
        <div x-show="step === 3" class="space-y-6" style="display: none;">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-velvoria-border shadow-sm space-y-6">
                <h3 class="text-base font-bold text-velvoria-ink border-b border-velvoria-border pb-3">Ringkasan Pesanan</h3>

                <!-- Selected items summary table -->
                <div class="divide-y divide-velvoria-border text-xs">
                    <template x-for="item in selectedItems" :key="item.service_id">
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-velvoria-ink block" x-text="item.name"></span>
                                <span class="text-velvoria-muted text-[11px]" x-text="item.quantity + ' ' + item.unit + ' @ ' + formatRupiah(item.price)"></span>
                            </div>
                            <span class="font-bold text-velvoria-ink" x-text="formatRupiah(item.price * item.quantity)"></span>
                        </div>
                    </template>
                </div>

                <!-- Promo Voucher Input -->
                <div class="pt-4 border-t border-velvoria-border">
                    <label class="block text-xs font-semibold text-velvoria-ink mb-1.5">Punya Kode Voucher?</label>
                    <div class="flex space-x-2">
                        <input type="text" x-model="promoCode" placeholder="Masukkan kode (Cth: VELVORIA10)" class="flex-grow px-4 py-2.5 rounded-xl border border-velvoria-border text-xs uppercase font-mono bg-velvoria-cream/30">
                        <button type="button" @click="applyPromo()" class="px-4 py-2.5 rounded-xl bg-velvoria-ink text-white font-bold text-xs hover:bg-black transition-all">
                            Terapkan
                        </button>
                    </div>
                </div>

                <!-- Breakdown Calculation -->
                <div class="pt-4 border-t border-velvoria-border space-y-2 text-xs">
                    <div class="flex items-center justify-between text-velvoria-muted">
                        <span>Subtotal Layanan:</span>
                        <span class="font-bold text-velvoria-ink" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between text-velvoria-muted" x-show="discountAmount > 0">
                        <span>Potongan Diskon:</span>
                        <span class="font-bold text-emerald-600" x-text="'-' + formatRupiah(discountAmount)"></span>
                    </div>
                    <div class="flex items-center justify-between text-velvoria-muted">
                        <span>Ongkos Antar-Jemput:</span>
                        <span class="font-bold text-velvoria-ink" x-text="deliveryFee === 0 ? 'GRATIS' : formatRupiah(deliveryFee)"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm font-bold text-velvoria-ink pt-3 border-t border-velvoria-border">
                        <span>Total Tagihan:</span>
                        <span class="text-xl text-velvoria-plum" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between">
                <button type="button" @click="step = 2; window.scrollTo({ top: 0, behavior: 'smooth' })" class="px-5 py-2.5 rounded-xl border border-velvoria-border text-xs font-semibold text-velvoria-ink hover:bg-white">
                    &larr; Kembali
                </button>
                <button type="submit" :disabled="submitting"
                        class="px-8 py-3.5 rounded-xl bg-velvoria-plum text-white font-bold text-sm hover:bg-velvoria-plum/90 shadow-md transition-all duration-300 flex items-center space-x-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span x-text="submitting ? 'Menyimpan Pesanan...' : 'Konfirmasi & Buat Pesanan'">Konfirmasi &amp; Buat Pesanan</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
