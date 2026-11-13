<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionUsageModel extends Model
{
    protected $table            = 'promotion_usages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'promotion_id',
        'user_id',
        'order_id',
        'discount_applied',
        'created_at',
    ];

    protected $validationRules = [
        'promotion_id'    => 'required|is_natural_no_zero',
        'user_id'         => 'required|is_natural_no_zero',
        'order_id'        => 'required|is_natural_no_zero',
        'discount_applied' => 'required|is_natural_no_zero',
    ];

    public function getUserUsageCount(int $promoId, int $userId): int
    {
        return $this->where('promotion_id', $promoId)
                    ->where('user_id', $userId)
                    ->countAllResults();
    }

    public function getTotalUsageCount(int $promoId): int
    {
        return $this->where('promotion_id', $promoId)
                    ->countAllResults();
    }
}
