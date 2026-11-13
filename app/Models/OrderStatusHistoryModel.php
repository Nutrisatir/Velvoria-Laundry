<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusHistoryModel extends Model
{
    protected $table            = 'order_status_histories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'note',
        'created_at',
    ];

    protected $validationRules = [
        'order_id'   => 'required|is_natural_no_zero',
        'new_status' => 'required|max_length[30]',
    ];

    public function getByOrder(int $orderId): array
    {
        return $this->select('order_status_histories.*, users.name AS changed_by_name')
                    ->join('users', 'users.id = order_status_histories.changed_by', 'left')
                    ->where('order_status_histories.order_id', $orderId)
                    ->orderBy('order_status_histories.created_at', 'DESC')
                    ->findAll();
    }
}
