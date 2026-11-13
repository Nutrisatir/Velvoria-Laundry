<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'order_number',
        'user_id',
        'address_id',
        'handover_method',
        'pickup_schedule',
        'delivery_schedule',
        'estimated_weight',
        'actual_weight',
        'subtotal',
        'discount_amount',
        'delivery_fee',
        'additional_fee',
        'grand_total',
        'promo_code',
        'order_status',
        'payment_status',
        'customer_note',
        'internal_note',
        'cancel_reason',
        'completed_at',
    ];

    protected $validationRules = [
        'order_number' => 'required|max_length[40]|is_unique[orders.order_number,id,{id}]',
        'user_id'      => 'required|is_natural_no_zero',
    ];

    public function generateOrderNumber(): string
    {
        $date   = date('Ymd');
        $random = strtoupper(bin2hex(random_bytes(3)));
        $random = substr($random, 0, 5);

        $orderNumber = "VL-{$date}-{$random}";

        while ($this->where('order_number', $orderNumber)->countAllResults() > 0) {
            $random      = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            $orderNumber = "VL-{$date}-{$random}";
        }

        return $orderNumber;
    }

    public function getByUser(int $userId, ?string $status = null, int $perPage = 10): array
    {
        $builder = $this->where('user_id', $userId);

        if ($status !== null) {
            $builder->where('order_status', $status);
        }

        return [
            'orders' => $builder->orderBy('created_at', 'DESC')->paginate($perPage),
            'pager'  => $this->pager,
        ];
    }

    public function findByOrderNumber(string $orderNumber): ?array
    {
        return $this->where('order_number', $orderNumber)->first();
    }

    public function getWithDetails(int $id): ?array
    {
        $order = $this->select('orders.*, users.name AS customer_name, users.email AS customer_email, users.phone AS customer_phone')
                      ->join('users', 'users.id = orders.user_id', 'left')
                      ->where('orders.id', $id)
                      ->first();

        if ($order === null) {
            return null;
        }

        $itemModel = new OrderItemModel();
        $order['items'] = $itemModel->getByOrder($id);

        if ($order['address_id']) {
            $addressModel      = new CustomerAddressModel();
            $order['address']  = $addressModel->find($order['address_id']);
        } else {
            $order['address'] = null;
        }

        return $order;
    }

    public function getTodayStats(): array
    {
        $today = date('Y-m-d');

        $rows = $this->select('order_status, COUNT(*) as total')
                     ->where('DATE(created_at)', $today)
                     ->groupBy('order_status')
                     ->findAll();

        $stats = [];
        foreach ($rows as $row) {
            $stats[$row['order_status']] = (int) $row['total'];
        }

        return $stats;
    }

    public function getRevenueByDateRange(string $startDate, string $endDate): array
    {
        return $this->select("DATE(created_at) as date, SUM(grand_total) as revenue, COUNT(*) as order_count")
                    ->where('payment_status', 'paid')
                    ->where('DATE(created_at) >=', $startDate)
                    ->where('DATE(created_at) <=', $endDate)
                    ->groupBy('DATE(created_at)')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
}
