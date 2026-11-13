<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetTokenModel extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['user_id', 'token_hash', 'expires_at', 'used_at', 'created_at'];

    public function findValidToken(string $token): ?array
    {
        return $this->where('token_hash', hash('sha256', $token))
            ->where('used_at', null)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();
    }
}
