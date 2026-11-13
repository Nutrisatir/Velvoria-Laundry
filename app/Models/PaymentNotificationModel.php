<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentNotificationModel extends Model
{
    protected $table            = 'payment_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'payment_id',
        'provider_order_id',
        'notification_hash',
        'event_status',
        'payload_json',
        'processed_at',
        'processing_result',
        'created_at',
    ];

    protected $validationRules = [
        'provider_order_id' => 'required|max_length[60]',
        'notification_hash' => 'required|max_length[128]|is_unique[payment_notifications.notification_hash]',
        'event_status'      => 'required|max_length[40]',
    ];

    protected bool $allowEmptyInserts = false;

    public function hashExists(string $hash): bool
    {
        return $this->where('notification_hash', $hash)->countAllResults() > 0;
    }
}
