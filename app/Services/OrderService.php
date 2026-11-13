<?php

namespace App\Services;

use App\Models\AdminActivityLogModel;
use App\Models\CustomerAddressModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;
use App\Models\ServiceModel;

class OrderService
{
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected ServiceModel $serviceModel;
    protected OrderStatusHistoryModel $historyModel;
    protected PricingService $pricingService;
    protected PromotionService $promotionService;
    protected AdminActivityLogModel $activityLog;

    public function __construct()
    {
        $this->orderModel       = new OrderModel();
        $this->orderItemModel   = new OrderItemModel();
        $this->serviceModel     = new ServiceModel();
        $this->historyModel     = new OrderStatusHistoryModel();
        $this->pricingService   = new PricingService();
        $this->promotionService = new PromotionService();
        $this->activityLog      = new AdminActivityLogModel();
    }

    /**
     * Create new order with snapshot pricing from database
     */
    public function createOrder(int $userId, array $data): array
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            return ['success' => false, 'error' => 'Pesanan harus memiliki minimal 1 layanan.'];
        }

        $handoverMethod   = $data['handover_method'] ?? 'drop_off';
        $addressId        = ! empty($data['address_id']) ? (int) $data['address_id'] : null;
        $pickupSchedule   = ! empty($data['pickup_schedule']) ? $data['pickup_schedule'] : null;
        $deliverySchedule = ! empty($data['delivery_schedule']) ? $data['delivery_schedule'] : null;
        $customerNote     = $data['customer_note'] ?? null;
        $promoCode        = ! empty($data['promo_code']) ? trim($data['promo_code']) : null;
        $estimatedWeight  = isset($data['estimated_weight']) ? (float) $data['estimated_weight'] : null;

        if (! in_array($handoverMethod, ['drop_off', 'pickup_delivery'], true)) {
            return ['success' => false, 'error' => 'Metode penyerahan tidak valid.'];
        }

        if ($handoverMethod === 'pickup_delivery' && empty($addressId)) {
            return ['success' => false, 'error' => 'Alamat penjemputan wajib dipilih untuk layanan antar-jemput.'];
        }

        // Jangan izinkan pelanggan menggunakan alamat milik akun lain.
        if ($addressId !== null) {
            $address = (new CustomerAddressModel())->where('id', $addressId)->where('user_id', $userId)->first();
            if (! $address) {
                return ['success' => false, 'error' => 'Alamat yang dipilih tidak ditemukan pada akun Anda.'];
            }
        }

        // Fetch services & build snapshot items
        $validatedItems = [];
        $hasKiloan = false;
        $totalKiloanQty = 0.0;

        foreach ($items as $item) {
            $serviceId = (int) ($item['service_id'] ?? 0);
            $qty       = (float) ($item['quantity'] ?? 0);

            if ($qty <= 0) {
                continue;
            }

            $service = $this->serviceModel->find($serviceId);
            if (! $service || (int) $service['is_active'] !== 1) {
                return ['success' => false, 'error' => "Layanan dengan ID {$serviceId} tidak aktif atau tidak ditemukan."];
            }
            if ($service['unit'] !== 'kg' && $qty < (float) $service['minimum_quantity']) {
                return ['success' => false, 'error' => "Jumlah minimal layanan {$service['name']} adalah {$service['minimum_quantity']} {$service['unit']}."];
            }

            if ($service['unit'] === 'kg') {
                $hasKiloan = true;
                $totalKiloanQty += $qty;
            }

            $lineTotal = $this->pricingService->calculateLineTotal((int) $service['price'], $qty);

            $validatedItems[] = [
                'service_id'            => $service['id'],
                'service_code_snapshot' => $service['code'],
                'service_name_snapshot' => $service['name'],
                'unit_snapshot'         => $service['unit'],
                'unit_price'            => (int) $service['price'],
                'quantity'              => $qty,
                'line_total'            => $lineTotal,
            ];
        }

        if (empty($validatedItems)) {
            return ['success' => false, 'error' => 'Kuantitas layanan yang dipilih harus lebih besar dari 0.'];
        }

        // Minimum weight validation for kiloan
        if ($hasKiloan) {
            $minWeightSetting = (float) (new \App\Models\StoreSettingModel())->get('minimum_weight', 2);
            if ($totalKiloanQty < $minWeightSetting) {
                return [
                    'success' => false,
                    'error'   => "Total pesanan kiloan minimal {$minWeightSetting} kg (saat ini: {$totalKiloanQty} kg).",
                ];
            }
            if ($estimatedWeight === null || $estimatedWeight <= 0) {
                $estimatedWeight = $totalKiloanQty;
            }
        }

        // Calculate Subtotal
        $subtotal = 0;
        foreach ($validatedItems as $vItem) {
            $subtotal += $vItem['line_total'];
        }

        // Calculate Delivery Fee
        $deliveryFee = $this->pricingService->calculateDeliveryFee($handoverMethod, $subtotal);

        // Calculate Discount
        $discountAmount = 0;
        $appliedPromo = null;
        if (! empty($promoCode)) {
            $promoResult = $this->promotionService->validatePromo($promoCode, $userId, $subtotal);
            if ($promoResult['valid']) {
                $discountAmount = $promoResult['discount'];
                $appliedPromo   = $promoResult['promotion'];
            } else {
                return ['success' => false, 'error' => 'Kode promo tidak valid: ' . $promoResult['message']];
            }
        }

        $grandTotal = $this->pricingService->calculateGrandTotal($subtotal, $discountAmount, $deliveryFee, 0);

        // Database transaction
        $db = db_connect();
        $db->transStart();

        $orderNumber = $this->orderModel->generateOrderNumber();

        $orderData = [
            'order_number'      => $orderNumber,
            'user_id'           => $userId,
            'address_id'        => $addressId,
            'handover_method'   => $handoverMethod,
            'pickup_schedule'   => $pickupSchedule,
            'delivery_schedule' => $deliverySchedule,
            'estimated_weight'  => $estimatedWeight,
            'actual_weight'     => null,
            'subtotal'          => $subtotal,
            'discount_amount'   => $discountAmount,
            'delivery_fee'      => $deliveryFee,
            'additional_fee'    => 0,
            'grand_total'       => $grandTotal,
            'promo_code'        => $appliedPromo ? $appliedPromo['code'] : null,
            'order_status'      => OrderStatusService::STATUS_AWAITING_PAYMENT,
            'payment_status'    => 'unpaid',
            'customer_note'     => $customerNote,
            'internal_note'     => null,
            'cancel_reason'     => null,
        ];

        $orderId = $this->orderModel->insert($orderData, true);

        // Insert items
        foreach ($validatedItems as $itemRow) {
            $itemRow['order_id'] = $orderId;
            $this->orderItemModel->insert($itemRow);
        }

        // Record initial status history
        $this->historyModel->insert([
            'order_id'   => $orderId,
            'old_status' => null,
            'new_status' => OrderStatusService::STATUS_AWAITING_PAYMENT,
            'changed_by' => $userId,
            'note'       => 'Pesanan baru dibuat oleh pelanggan.',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Record promo usage if any
        if ($appliedPromo && $discountAmount > 0) {
            $this->promotionService->applyPromo($appliedPromo['id'], $userId, $orderId, $discountAmount);
        }

        $db->transComplete();

        if ($db->transStatus() === false || ! $orderId) {
            return ['success' => false, 'error' => 'Gagal menyimpan pesanan ke database. Silakan coba lagi.'];
        }

        $order = $this->orderModel->find($orderId);

        return [
            'success' => true,
            'order'   => $order,
        ];
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(int $orderId, int $userId, string $reason, bool $isAdmin = false): array
    {
        $order = $this->orderModel->find($orderId);
        if (! $order) {
            return ['success' => false, 'error' => 'Pesanan tidak ditemukan.'];
        }

        if (! $isAdmin && (int) $order['user_id'] !== $userId) {
            return ['success' => false, 'error' => 'Anda tidak memiliki hak untuk membatalkan pesanan ini.'];
        }

        // Check if status allows cancellation
        // Allowed before washing: awaiting_payment, pending_pickup, received
        $cancellableStatuses = [
            OrderStatusService::STATUS_AWAITING_PAYMENT,
            OrderStatusService::STATUS_PENDING_PICKUP,
            OrderStatusService::STATUS_RECEIVED,
        ];

        if (! in_array($order['order_status'], $cancellableStatuses, true)) {
            return [
                'success' => false,
                'error'   => 'Pesanan tidak dapat dibatalkan karena sudah dalam proses pengerjaan.',
            ];
        }

        $db = db_connect();
        $db->transStart();

        $this->orderModel->update($orderId, [
            'order_status'  => OrderStatusService::STATUS_CANCELLED,
            'cancel_reason' => $reason,
        ]);

        $this->historyModel->insert([
            'order_id'   => $orderId,
            'old_status' => $order['order_status'],
            'new_status' => OrderStatusService::STATUS_CANCELLED,
            'changed_by' => $userId,
            'note'       => 'Pembatalan: ' . $reason,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['success' => false, 'error' => 'Gagal membatalkan pesanan.'];
        }

        return ['success' => true, 'message' => 'Pesanan berhasil dibatalkan.'];
    }

    /**
     * Admin updates actual weight after weighing
     */
    public function updateActualWeight(int $orderId, float $actualWeight, int $adminId): array
    {
        $order = $this->orderModel->find($orderId);
        if (! $order) {
            return ['success' => false, 'error' => 'Pesanan tidak ditemukan.'];
        }

        if ($actualWeight <= 0) {
            return ['success' => false, 'error' => 'Berat aktual harus lebih besar dari 0.'];
        }

        $this->orderModel->update($orderId, [
            'actual_weight' => $actualWeight,
        ]);

        $this->activityLog->log(
            $adminId,
            'update_weight',
            'orders',
            $orderId,
            "Memperbarui berat aktual pesanan {$order['order_number']} menjadi {$actualWeight} kg."
        );

        return ['success' => true, 'message' => 'Berat aktual berhasil disimpan.'];
    }
}
