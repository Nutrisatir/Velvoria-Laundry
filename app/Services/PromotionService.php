<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class PromotionService
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function validatePromo(string $code, int $userId, int $subtotal): array
    {
        $promo = $this->db->table('promotions')
            ->where('code', strtoupper(trim($code)))
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if (! $promo) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Kode promo tidak ditemukan.', 'promotion' => []];
        }

        $now = date('Y-m-d H:i:s');

        if (! empty($promo['starts_at']) && $now < $promo['starts_at']) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Promo belum berlaku.', 'promotion' => $promo];
        }

        if (! empty($promo['ends_at']) && $now > $promo['ends_at']) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Promo sudah berakhir.', 'promotion' => $promo];
        }

        if (! empty($promo['usage_limit']) && $promo['usage_limit'] > 0) {
            $totalUsage = $this->db->table('promotion_usages')
                ->where('promotion_id', $promo['id'])
                ->countAllResults();

            if ($totalUsage >= (int) $promo['usage_limit']) {
                return ['valid' => false, 'discount' => 0, 'message' => 'Kuota promo sudah habis.', 'promotion' => $promo];
            }
        }

        if (! empty($promo['usage_per_user']) && $promo['usage_per_user'] > 0) {
            $userUsage = $this->db->table('promotion_usages')
                ->where('promotion_id', $promo['id'])
                ->where('user_id', $userId)
                ->countAllResults();

            if ($userUsage >= (int) $promo['usage_per_user']) {
                return ['valid' => false, 'discount' => 0, 'message' => 'Anda sudah menggunakan promo ini.', 'promotion' => $promo];
            }
        }

        if (! empty($promo['minimum_order']) && $subtotal < (int) $promo['minimum_order']) {
            $minFormatted = number_format((int) $promo['minimum_order'], 0, ',', '.');
            return [
                'valid'     => false,
                'discount'  => 0,
                'message'   => "Minimum order Rp{$minFormatted} untuk promo ini.",
                'promotion' => $promo,
            ];
        }

        $discount = $this->calculateDiscount($promo, $subtotal);

        return [
            'valid'     => true,
            'discount'  => $discount,
            'message'   => 'Promo berhasil diterapkan!',
            'promotion' => $promo,
        ];
    }

    public function calculateDiscount(array $promo, int $subtotal): int
    {
        $discountValue = (int) $promo['discount_value'];

        if ($promo['discount_type'] === 'fixed') {
            return min($discountValue, $subtotal);
        }

        $calculated = (int) floor($subtotal * $discountValue / 100);
        $maxDiscount = ! empty($promo['maximum_discount']) ? (int) $promo['maximum_discount'] : PHP_INT_MAX;

        return min($calculated, $maxDiscount, $subtotal);
    }

    public function applyPromo(int $promoId, int $userId, int $orderId, int $discountApplied): void
    {
        $this->db->table('promotion_usages')->insert([
            'promotion_id'    => $promoId,
            'user_id'         => $userId,
            'order_id'        => $orderId,
            'discount_applied' => $discountApplied,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
    }
}
