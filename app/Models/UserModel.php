<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'password_hash',
        'role',
        'status',
        'email_verified_at',
        'avatar',
    ];

    protected $validationRules = [
        'name'          => 'required|min_length[2]|max_length[120]',
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'phone'         => 'permit_empty|max_length[25]',
        'password_hash' => 'required|min_length[8]',
        'role'          => 'permit_empty|in_list[customer,admin,staff]',
        'status'        => 'permit_empty|in_list[active,inactive,blocked]',
    ];

    protected $validationMessages = [];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByPhone(string $phone): ?array
    {
        return $this->where('phone', $phone)->first();
    }

    public function getCustomers(): array
    {
        return $this->where('role', 'customer')->findAll();
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
