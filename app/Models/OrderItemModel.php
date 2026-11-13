<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'order_id',
        'service_id',
        'service_code_snapshot',
        'service_name_snapshot',
        'unit_snapshot',
        'unit_price',
        'quantity',
        'line_total',
    ];

    protected $validationRules = [
        'order_id'              => 'required|is_natural_no_zero',
        'service_code_snapshot' => 'required|max_length[30]',
        'service_name_snapshot' => 'required|max_length[150]',
        'unit_snapshot'         => 'required|max_length[20]',
        'unit_price'            => 'required|is_natural_no_zero',
        'quantity'              => 'required|numeric|greater_than[0]',
        'line_total'            => 'required|is_natural',
    ];

    public function getByOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
