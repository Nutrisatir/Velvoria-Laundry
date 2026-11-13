<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminActivityLogModel extends Model
{
    protected $table            = 'admin_activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
        'created_at',
    ];

    protected $validationRules = [
        'action' => 'required|max_length[100]',
    ];

    public function log(
        ?int $userId,
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
    ): bool {
        $request = service('request');

        return $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    public function getRecent(int $limit = 20): array
    {
        return $this->select('admin_activity_logs.*, users.name AS user_name')
                    ->join('users', 'users.id = admin_activity_logs.user_id', 'left')
                    ->orderBy('admin_activity_logs.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
