<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreSettingModel extends Model
{
    protected $table            = 'store_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'updated_at',
    ];

    protected $validationRules = [
        'setting_key' => 'required|max_length[100]',
    ];

    public function get(string $key, ?string $default = null): ?string
    {
        $row = $this->where('setting_key', $key)->first();

        return $row !== null ? $row['setting_value'] : $default;
    }

    public function saveSetting(string $key, ?string $value): bool
    {
        $existing = $this->where('setting_key', $key)->first();

        if ($existing !== null) {
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    public function getMultiple(array $keys): array
    {
        $rows = $this->whereIn('setting_key', $keys)->findAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }

        return $result;
    }

    public function getAllSettings(): array
    {
        $rows = $this->findAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }

        return $result;
    }
}
