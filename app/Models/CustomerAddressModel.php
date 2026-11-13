<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerAddressModel extends Model
{
    protected $table            = 'customer_addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'address',
        'district',
        'city',
        'postal_code',
        'notes',
        'is_primary',
    ];

    protected $validationRules = [
        'user_id'        => 'required|is_natural_no_zero',
        'label'          => 'required|max_length[50]',
        'recipient_name' => 'required|max_length[120]',
        'phone'          => 'required|max_length[25]',
        'address'        => 'required',
        'district'       => 'permit_empty|max_length[100]',
        'city'           => 'permit_empty|max_length[100]',
        'postal_code'    => 'permit_empty|max_length[10]',
    ];

    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('is_primary', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function setPrimary(int $addressId, int $userId): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->where('user_id', $userId)
             ->set('is_primary', 0)
             ->update();

        $this->where('id', $addressId)
             ->where('user_id', $userId)
             ->set('is_primary', 1)
             ->update();

        $db->transComplete();

        return $db->transStatus();
    }
}
