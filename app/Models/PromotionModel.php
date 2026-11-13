<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table            = 'promotions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'minimum_order',
        'maximum_discount',
        'usage_limit',
        'usage_per_user',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $validationRules = [
        'code'           => 'required|max_length[40]|is_unique[promotions.code,id,{id}]',
        'name'           => 'required|max_length[120]',
        'discount_type'  => 'required|in_list[fixed,percent]',
        'discount_value' => 'required|is_natural_no_zero',
        'starts_at'      => 'required|valid_date',
        'ends_at'        => 'required|valid_date',
    ];

    public function findActiveByCode(string $code): ?array
    {
        $now = date('Y-m-d H:i:s');

        $promo = $this->where('code', $code)
                      ->where('is_active', 1)
                      ->where('starts_at <=', $now)
                      ->where('ends_at >=', $now)
                      ->first();

        if ($promo === null) {
            return null;
        }

        if ($promo['usage_limit'] !== null) {
            $usageModel = new PromotionUsageModel();
            $totalUsed  = $usageModel->getTotalUsageCount((int) $promo['id']);
            if ($totalUsed >= (int) $promo['usage_limit']) {
                return null;
            }
        }

        return $promo;
    }

    public function getActive(): array
    {
        $now = date('Y-m-d H:i:s');

        return $this->where('is_active', 1)
                    ->where('starts_at <=', $now)
                    ->where('ends_at >=', $now)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
