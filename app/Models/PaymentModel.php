<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'order_id',
        'provider',
        'provider_order_id',
        'transaction_id',
        'payment_type',
        'snap_token',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'payment_status',
        'paid_at',
    ];

    protected $validationRules = [
        'order_id'          => 'required|is_natural_no_zero',
        'provider_order_id' => 'required|max_length[60]|is_unique[payments.provider_order_id,id,{id}]',
        'gross_amount'      => 'required|is_natural_no_zero',
    ];

    public function getByOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function findByProviderOrderId(string $providerOrderId): ?array
    {
        return $this->where('provider_order_id', $providerOrderId)->first();
    }
}
