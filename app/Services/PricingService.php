<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class PricingService
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function calculateLineTotal(int $unitPrice, float $quantity): int
    {
        return (int) round($unitPrice * $quantity);
    }

    public function calculateSubtotal(array $items): int
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $this->calculateLineTotal(
                (int) $item['unit_price'],
                (float) $item['quantity']
            );
        }

        return $subtotal;
    }

    public function calculateDeliveryFee(string $handoverMethod, int $subtotal): int
    {
        if ($handoverMethod === 'drop_off') {
            return 0;
        }

        $deliveryFee = $this->getStoreSetting('delivery_fee', 0);
        $freeThreshold = $this->getStoreSetting('free_delivery_threshold', 0);

        if ($freeThreshold > 0 && $subtotal >= $freeThreshold) {
            return 0;
        }

        return (int) $deliveryFee;
    }

    public function calculateDiscount(string $promoCode, int $subtotal, int $userId): array
    {
        $result = [
            'discount_amount' => 0,
            'promo_code'      => '',
            'promo_name'      => '',
            'promo_id'        => null,
        ];

        if ($promoCode === '') {
            return $result;
        }

        $promoService = new PromotionService();
        $validation = $promoService->validatePromo($promoCode, $userId, $subtotal);

        if (! $validation['valid']) {
            return $result;
        }

        return [
            'discount_amount' => $validation['discount'],
            'promo_code'      => $validation['promotion']['code'],
            'promo_name'      => $validation['promotion']['name'],
            'promo_id'        => $validation['promotion']['id'],
        ];
    }

    public function calculateGrandTotal(int $subtotal, int $discountAmount, int $deliveryFee, int $additionalFee = 0): int
    {
        $total = $subtotal - $discountAmount + $deliveryFee + $additionalFee;

        return max(0, $total);
    }

    protected function getStoreSetting(string $key, mixed $default = null): mixed
    {
        $row = $this->db->table('store_settings')
            ->where('setting_key', $key)
            ->get()
            ->getRowArray();

        return $row['setting_value'] ?? $default;
    }
}
